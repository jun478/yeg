<?php

add_shortcode( 'top_schedule', 'yeg_top_schedule_shortcode' );
function yeg_top_schedule_get_table_value( $event_id, $label ) {
  $table_list = get_post_meta( $event_id, 'table_list', true );

  if ( is_string( $table_list ) ) {
    $unserialized = maybe_unserialize( $table_list );
    if ( is_array( $unserialized ) ) {
      $table_list = $unserialized;
    }
  }

  if ( ! is_array( $table_list ) ) {
    return '';
  }

  foreach ( $table_list as $table_item ) {
    if ( isset( $table_item['label'], $table_item['cell'] ) && $label === $table_item['label'] ) {
      return (string) $table_item['cell'];
    }
  }

  return '';
}

function yeg_top_schedule_get_events_by_day( $year, $month, $day ) {
  $calendar_options = is_array( get_option( 'tcd_calender_options' ) ) ? get_option( 'tcd_calender_options' ) : array();
  $ids              = $calendar_options[ $year ][ $month ][ $day ]['ids'] ?? array();

  if ( empty( $ids ) || ! is_array( $ids ) ) {
    return array();
  }

  $events = array();
  foreach ( array_keys( $ids ) as $event_id ) {
    $event_id = (int) $event_id;

    if ( 'publish' !== get_post_status( $event_id ) ) {
      continue;
    }

    $event = get_post( $event_id );
    if ( $event ) {
      $events[] = $event;
    }
  }

  if ( function_exists( 'yeg_sort_events_by_opening_hours' ) ) {
    return yeg_sort_events_by_opening_hours( $events );
  }

  usort( $events, function( $event_a, $event_b ) {
    return yeg_top_schedule_opening_minutes( $event_a->ID ) <=> yeg_top_schedule_opening_minutes( $event_b->ID );
  } );

  return $events;
}

function yeg_top_schedule_opening_minutes( $event_id ) {
  if ( function_exists( 'yeg_event_opening_hours_sort_value' ) ) {
    return yeg_event_opening_hours_sort_value( $event_id );
  }

  $opening_hours = (string) get_post_meta( $event_id, 'opening_hours', true );
  if ( preg_match( '/(\d{1,2})[:：](\d{2})/', $opening_hours, $matches ) ) {
    return ( (int) $matches[1] * 60 ) + (int) $matches[2];
  }

  return PHP_INT_MAX;
}

function yeg_top_schedule_move_event_after( $events, $target_title_part, $after_title_part ) {
  $target_index = null;
  $after_index  = null;

  foreach ( $events as $index => $event ) {
    $title = get_the_title( $event );

    if ( null === $target_index && false !== mb_strpos( $title, $target_title_part ) ) {
      $target_index = $index;
    }

    if ( null === $after_index && false !== mb_strpos( $title, $after_title_part ) ) {
      $after_index = $index;
    }
  }

  if ( null === $target_index || null === $after_index || $target_index === $after_index ) {
    return $events;
  }

  $target_event = $events[ $target_index ];
  array_splice( $events, $target_index, 1 );

  if ( $target_index < $after_index ) {
    $after_index--;
  }

  array_splice( $events, $after_index + 1, 0, array( $target_event ) );

  return $events;
}

function yeg_top_schedule_render_event_column( $events ) {
  ?>
  <div class="yeg-top-schedule__column">
    <?php if ( $events ) : ?>
      <ul class="yeg-top-schedule__events">
        <?php foreach ( $events as $event ) : ?>
          <?php
          $opening_hours = get_post_meta( $event->ID, 'opening_hours', true );
          $place         = yeg_top_schedule_get_table_value( $event->ID, '開催場所' );
          ?>
          <li class="yeg-top-schedule__event">
            <span class="yeg-top-schedule__event-link">
            <!-- a class="yeg-top-schedule__event-link" href="<?php echo esc_url( get_permalink( $event ) ); ?>" -->
              <?php if ( $opening_hours ) : ?>
                <span class="yeg-top-schedule__event-time"><?php echo esc_html( $opening_hours ); ?></span>
              <?php endif; ?>
              <span class="yeg-top-schedule__event-title"><?php echo esc_html( get_the_title( $event ) ); ?></span>
              <?php if ( $place ) : ?>
                <span class="yeg-top-schedule__event-place"><?php echo esc_html( $place ); ?></span>
              <?php endif; ?>
            <!-- /a -->
              </span>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else : ?>
      <p class="yeg-top-schedule__no-event">現在準備中です。</p>
    <?php endif; ?>
  </div>
  <?php
}

function yeg_top_schedule_render_day( $year, $month, $day ) {
  $events       = yeg_top_schedule_get_events_by_day( $year, $month, $day );

  if ( 2026 === (int) $year && 11 === (int) $month && 14 === (int) $day ) {
    $events = yeg_top_schedule_move_event_after( $events, '分科会I', '分科会H' );
  }

  $column_count = 2;
  $first_count  = $events ? (int) ceil( count( $events ) / $column_count ) : 0;
  $columns      = $events ? array_chunk( $events, $first_count ) : array( array(), array() );
  $week_labels  = array( '日', '月', '火', '水', '木', '金', '土' );
  $week_label   = $week_labels[ (int) date( 'w', mktime( 0, 0, 0, $month, $day, $year ) ) ] ?? '';

  $events_link = add_query_arg(
    array( 'calender' => sprintf( '%04d-%02d-%02d', $year, $month, $day ) ),
    get_post_type_archive_link( 'events' )
  );
  ?>
  <div class="yeg-top-schedule__day">
    <div class="yeg-top-schedule__day-head">
      <h3 class="yeg-top-schedule__day-title"><?php echo esc_html( sprintf( '%d月%d日（%s）', $month, $day, $week_label ) ); ?></h3>
      <p class="yeg-top-schedule__day-label">
        <?php //echo esc_html( 13 === (int) $day ? 'DAY 1' : 'DAY 2' ); ?>
        <?php if(13 === (int) $day ){?>
          会議・研修・交流を通じ、全国YEGの結束を深める一日。
        <?php }else{?>
          分科会や研修で学びを深め、地域の魅力に触れる一日。
        <?php }?>
      </p>
    </div>
    <div class="yeg-top-schedule__columns">
      <?php yeg_top_schedule_render_event_column( $columns[0] ?? array() ); ?>
      <?php yeg_top_schedule_render_event_column( $columns[1] ?? array() ); ?>
    </div>
    <div class="yeg-top-schedule__more">
      <a class="yeg-top-schedule__more-link c-button" href="<?php echo esc_url( $events_link ); ?>">一覧を見る</a>
    </div>
  </div>
  <?php
}

function yeg_top_schedule_shortcode( $atts ) {
  static $script_printed = false;

  $atts = shortcode_atts(
    array(
      'headline'     => 'スケジュール',
      'sub_headline' => '研修・交流・分科会を通じて、未来への一歩を描く2日間',
      'layout'       => 'type1',
    ),
    $atts,
    'top_schedule'
  );

  $tabs = array(
    'day-20261113' => array(
      'label' => '11月13日',
      'date'  => array( 2026, 11, 13 ),
    ),
    'day-20261114' => array(
      'label' => '11月14日',
      'date'  => array( 2026, 11, 14 ),
    ),
  );

  $layout_class = 'type1' === $atts['layout'] ? ' is-slider' : '';

  ob_start();
  ?>
  <section class="p-fp-news p-fp-section yeg-top-schedule<?php echo esc_attr( $layout_class ); ?>">
    <div class="p-fp-news__inner l-inner">
      <?php if ( $atts['headline'] || $atts['sub_headline'] ) : ?>
        <div class="p-fp-section__header">
          <?php if ( $atts['headline'] ) : ?>
            <h2 class="p-fp-section__headline c-headline"><?php echo esc_html( $atts['headline'] ); ?></h2>
          <?php endif; ?>
          <?php if ( $atts['sub_headline'] ) : ?>
            <p class="p-fp-section__desc"><?php echo esc_html( $atts['sub_headline'] ); ?></p>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <div class="p-fp-news__tab yeg-top-schedule__tab">
        <ul class="p-fp-news__tab-labels p-archive--news-category u-flex c-h-scroll">
          <?php foreach ( $tabs as $tab_id => $tab ) : ?>
            <li id="js-top-schedule-tab-<?php echo esc_attr( $tab_id ); ?>" class="p-fp-news__tab-labels-item p-archive--news-category__item js-top-schedule-tab-label <?php echo 'day-20261113' === $tab_id ? 'is-active' : ''; ?>">
              <span class="p-fp-news__tab-labels-link p-archive--news-category__item-link u-flex-center">
                <?php echo esc_html( $tab['label'] ); ?>
              </span>
            </li>
          <?php endforeach; ?>
        </ul>

        <div class="p-fp-news__tab-area yeg-top-schedule__tab-area">
          <?php foreach ( $tabs as $tab_id => $tab ) : ?>
            <div id="js-top-schedule-tab-<?php echo esc_attr( $tab_id ); ?>-area" class="p-fp-news__tab-area__item splide yeg-top-schedule__tab-area-item js-top-schedule-tab-area js-top-schedule-tab-slider <?php echo 'day-20261113' === $tab_id ? 'is-show' : ''; ?>">
              <div class="splide__track p-fp-news__tab-area__item-track">
                <div class="splide__list p-fp-news__tab-area__item-list c-h-scroll yeg-top-schedule__empty-list">
                  <?php
                  yeg_top_schedule_render_day( $tab['date'][0], $tab['date'][1], $tab['date'][2] );
                  ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
  <?php if ( ! $script_printed ) : ?>
  <script id="top-schedule-shortcode-js">
  document.addEventListener('DOMContentLoaded', function() {
    var scheduleBlocks = document.querySelectorAll('.yeg-top-schedule');
    for (var i = 0; i < scheduleBlocks.length; i++) {
      scheduleBlocks[i].addEventListener('click', function(e) {
        var tabLabel = e.target.closest('.js-top-schedule-tab-label');
        if (!tabLabel || !this.contains(tabLabel)) {
          return;
        }

        e.preventDefault();

        var activeLabel = this.querySelector('.js-top-schedule-tab-label.is-active');
        var activeArea = this.querySelector('.js-top-schedule-tab-area.is-show');
        var targetArea = this.querySelector('#' + tabLabel.id + '-area');

        if (activeLabel) {
          activeLabel.classList.remove('is-active');
        }
        if (activeArea) {
          activeArea.classList.remove('is-show');
        }

        tabLabel.classList.add('is-active');
        if (targetArea) {
          targetArea.classList.add('is-show');
        }
      });

      var scheduleSliders = scheduleBlocks[i].querySelectorAll('.js-top-schedule-tab-slider.splide');
      if (typeof Splide === 'undefined' || scheduleSliders.length === 0) {
        continue;
      }

      for (var j = 0; j < scheduleSliders.length; j++) {
        if (scheduleSliders[j].classList.contains('is-yeg-top-schedule-mounted')) {
          continue;
        }

        var targetSlides = scheduleSliders[j].querySelectorAll('.splide__slide');
        if (targetSlides.length <= 3) {
          continue;
        }

        var splideExtensions = window.splide && window.splide.Extensions ? window.splide.Extensions : {};

        new Splide(scheduleSliders[j], {
          type: 'loop',
          speed: 900,
          perPage: 3,
          perMove: 3,
          gap: '15px',
          autoplay: 'pause',
          interval: 5000,
          pagination: false,
          breakpoints: {
            992: {
              perPage: 2,
              perMove: 2
            },
            767: {
              destroy: true,
              arrows: false
            }
          }
        }).mount(splideExtensions);

        scheduleSliders[j].classList.add('is-yeg-top-schedule-mounted');
      }
    }
  });
  </script>
  <?php $script_printed = true; ?>
  <?php endif; ?>
  <?php

  return ob_get_clean();
}
