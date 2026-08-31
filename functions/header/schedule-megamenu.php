<?php

add_action( 'wp', 'yeg_replace_header_bar_with_schedule_megamenu', 20 );
function yeg_replace_header_bar_with_schedule_megamenu() {
  $body_priority = has_action( 'tcd_body_start', 'render_header_bar' );
  if ( false !== $body_priority ) {
    remove_action( 'tcd_body_start', 'render_header_bar', $body_priority );
    add_action( 'tcd_body_start', 'yeg_render_header_bar', $body_priority );
  }

  $container_priority = has_action( 'tcd_container_start', 'render_header_bar' );
  if ( false !== $container_priority ) {
    remove_action( 'tcd_container_start', 'render_header_bar', $container_priority );
    add_action( 'tcd_container_start', 'yeg_render_header_bar', $container_priority );
  }
}

add_filter( 'nav_menu_link_attributes', 'yeg_fix_schedule_menu_link_on_mobile', 20, 4 );
function yeg_fix_schedule_menu_link_on_mobile( $atts, $menu_item, $args, $depth ) {
  if ( 0 !== $depth ) {
    return $atts;
  }

  $is_drawer_menu = isset( $args->menu_class ) && false !== strpos( $args->menu_class, 'p-drawer-menu__nav' );
  $is_mobile      = function_exists( 'is_mobile' ) && is_mobile();
  $menu_location  = isset( $args->theme_location ) ? (string) $args->theme_location : '';
  $is_target_menu = $is_drawer_menu || 'global-menu' === $menu_location || 0 === strpos( $menu_location, 'footer-menu' );

  if ( ! $is_target_menu || ( ! $is_mobile && ! $is_drawer_menu ) ) {
    return $atts;
  }

  if ( ! yeg_is_schedule_megamenu_item( $menu_item->ID ) ) {
    return $atts;
  }

  $atts['href'] = yeg_get_schedule_menu_url();

  unset( $atts['data-menu-type'], $atts['data-megamenu'] );

  return $atts;
}

add_action( 'tcd_footer_after', 'yeg_fix_schedule_button_links_before_render', 25 );
function yeg_fix_schedule_button_links_before_render() {
  global $dp_options;

  if ( ! $dp_options ) {
    $dp_options = get_design_plus_option();
  }

  $schedule_url = yeg_get_schedule_menu_url();

  if ( ! empty( $dp_options['side_button_contents'] ) && is_array( $dp_options['side_button_contents'] ) ) {
    foreach ( $dp_options['side_button_contents'] as $key => $button ) {
      if ( isset( $button['title'] ) && 'スケジュール' === $button['title'] ) {
        $dp_options['side_button_contents'][ $key ]['url'] = $schedule_url;
      }
    }
  }

  if ( ! empty( $dp_options['footer_bar_btns'] ) && is_array( $dp_options['footer_bar_btns'] ) ) {
    foreach ( $dp_options['footer_bar_btns'] as $key => $button ) {
      if ( isset( $button['label'] ) && 'スケジュール' === $button['label'] ) {
        $dp_options['footer_bar_btns'][ $key ]['url'] = $schedule_url;
      }
    }
  }
}

function yeg_get_schedule_menu_url() {
  return add_query_arg(
    array( 'calender' => '2026-11' ),
    get_post_type_archive_link( 'events' )
  );
}

function yeg_render_header_bar( $dp_options ) {
  ?>
  <header id="js-header" class="l-header p-drawer-animation">
    <div class="l-header__inner u-flex">
      <div class="l-header__logo u-flex-align-center">
        <?php tcd_output_logo( 'l-header__logo-image u-flex-align-center', is_front_page() ? 'h1' : 'div', 'header' ); ?>
      </div>

      <?php if ( has_nav_menu( 'global-menu' ) ) : ?>
        <div class="l-header__nav">
          <?php
          wp_nav_menu(
            array(
              'container'       => 'nav',
              'container_class' => 'p-global__nav-wrapper',
              'container_id'    => '',
              'depth'           => 2,
              'menu_class'      => 'p-global__nav u-flex',
              'menu_id'         => 'js-global-nav',
              'theme_location'  => 'global-menu',
              'link_after'      => '',
            )
          );
          ?>
        </div>
      <?php endif; ?>

      <div class="l-header__nav--sp u-flex-center">
        <button class="p-menu-button u-button-reset js-menu-button" type="button">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>

    <?php yeg_render_megamenu(); ?>
  </header>
  <?php
}

function yeg_render_megamenu() {
  global $dp_options;
  if ( ! $dp_options ) {
    $dp_options = get_design_plus_option();
  }

  if ( ! empty( $dp_options['megamenu_a_id'] ) ) {
    if ( yeg_is_schedule_megamenu_item( $dp_options['megamenu_a_id'] ) ) {
      yeg_render_schedule_megamenu( $dp_options['megamenu_a_id'] );
    } elseif ( function_exists( 'render_megamenu_a' ) ) {
      render_megamenu_a( $dp_options['megamenu_a_id'] );
    }
  }

  if ( ! empty( $dp_options['megamenu_b_id'] ) && function_exists( 'render_megamenu_b' ) ) {
    render_megamenu_b( $dp_options['megamenu_b_id'] );
  }
}

function yeg_is_schedule_megamenu_item( $menu_item_id ) {
  $menu_item = get_post( $menu_item_id );

  if ( ! $menu_item ) {
    return false;
  }

  return false !== mb_strpos( $menu_item->post_title, 'スケジュール' );
}

function yeg_get_megamenu_events_by_day( $year, $month, $day, $limit = 8, $taxonomy_slug = '' ) {
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

    if ( $taxonomy_slug && ! has_term( $taxonomy_slug, 'event_category', $event_id ) ) {
      continue;
    }

    $event = get_post( $event_id );
    if ( $event ) {
      $events[] = $event;
    }
  }

  if ( function_exists( 'yeg_sort_events_by_opening_hours' ) ) {
    $events = yeg_sort_events_by_opening_hours( $events );
  }

  return array_slice( $events, 0, $limit );
}

function yeg_render_schedule_megamenu_column( $headline, $caption, $events, $archive_url = '' ) {
  ?>
  <div class="p-megamenu-schedule__column">
    <div class="p-megamenu-schedule__column-head">
      <p class="p-megamenu-schedule__label"><?php echo esc_html( $caption ); ?></p>
      <h3 class="p-megamenu-schedule__headline"><?php echo esc_html( $headline ); ?></h3>
    </div>

    <?php if ( $events ) : ?>
      <ul class="p-megamenu-schedule__list">
        <?php foreach ( $events as $event ) : ?>
          <?php $opening_hours = get_post_meta( $event->ID, 'opening_hours', true ); ?>
          <li class="p-megamenu-schedule__item">
            <?php if ( function_exists( 'tcd_events_single_links_enabled' ) && tcd_events_single_links_enabled() ) : ?>
            <a class="p-megamenu-schedule__link" href="<?php echo esc_url( get_permalink( $event ) ); ?>">
            <?php else : ?>
            <span class="p-megamenu-schedule__link" aria-disabled="true">
            <?php endif; ?>
              <?php if ( $opening_hours ) : ?>
                <span class="p-megamenu-schedule__time"><?php echo esc_html( $opening_hours ); ?></span>
              <?php endif; ?>
              <span class="p-megamenu-schedule__title"><?php echo esc_html( get_the_title( $event ) ); ?></span>
            <?php if ( function_exists( 'tcd_events_single_links_enabled' ) && tcd_events_single_links_enabled() ) : ?>
            </a>
            <?php else : ?>
            </span>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <?php if ( $archive_url ) : ?>
      <a class="p-megamenu-schedule__more" href="<?php echo esc_url( $archive_url ); ?>">
        一覧を見る
      </a>
    <?php endif; ?>
  </div>
  <?php
}

function yeg_render_schedule_megamenu( $id ) {
  $day13_events     = yeg_get_megamenu_events_by_day( 2026, 11, 13, 8 );
  $day14_events     = yeg_get_megamenu_events_by_day( 2026, 11, 14, 8 );
  $breakout_events  = yeg_get_megamenu_events_by_day( 2026, 11, 14, 7, 'bunkakai' );
  $events_base_link = get_post_type_archive_link( 'events' );
  ?>
  <div id="js-megamenu<?php echo esc_attr( $id ); ?>" class="p-megamenu p-megamenu-schedule">
    <div class="p-megamenu-schedule__inner l-inner">
      <?php
      yeg_render_schedule_megamenu_column(
        '11月13日（金）',
        'DAY 1',
        $day13_events,
        add_query_arg( array( 'calender' => '2026-11-13' ), $events_base_link )
      );

      yeg_render_schedule_megamenu_column(
        '11月14日（土）',
        'DAY 2',
        $day14_events,
        add_query_arg( array( 'calender' => '2026-11-14' ), $events_base_link )
      );

      yeg_render_schedule_megamenu_column(
        '分科会',
        'BREAKOUT',
        $breakout_events,
        home_url( '/events-category/bunkakai/' )
      );
      ?>
    </div>
  </div>
  <?php
}
