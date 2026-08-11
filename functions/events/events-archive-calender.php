<?php



// カレンダーナビゲーション
function child_tcd_output_events_archive_calender_header( $y, $m, $d ){

  global $wp_locale;

  // ヘッダー
  $prev_month = tcd_get_prev_month( $y, $m );
  $next_month = tcd_get_next_month( $y, $m );
  
?>
  <div class="p-calender__nav u-flex">
    <a class="p-calender__nav-link p-calender__nav-link--prev u-flex-center" href="<?php echo add_query_arg( array( 'calender' => $prev_month->format('Y-m') ), get_post_type_archive_link('events') ); ?>">
      <!--<span class="p-calender__nav-icon p-calender__nav-icon--prev c-icon">&#xe5e0;</span>-->
      <?php //echo sprintf( _x( '%1$s', 'calendar caption' ), $wp_locale->get_month( $prev_month->format('m') ), ); ?>
    </a>
    <h2 class="p-calender__nav-headline u-flex-center">
      <?php echo sprintf( _x( '%1$s %2$s', 'calendar caption' ), $wp_locale->get_month( $m ), $y ); ?>
    </h2>
    <a class="p-calender__nav-link p-calender__nav-link--next u-flex-center" href="<?php echo add_query_arg( array( 'calender' => $next_month->format('Y-m') ), get_post_type_archive_link('events') ); ?>">
      <?php //echo sprintf( _x( '%1$s', 'calendar caption' ), $wp_locale->get_month( $next_month->format('m') ), ); ?>
      <!--<span class="p-calender__nav-icon p-calender__nav-icon--next c-icon">&#xe5e1;</span>-->
    </a>
  </div>
<?php

}
add_action( 'child_tcd_events_archive_calender', 'child_tcd_output_events_archive_calender_header', 20, 3 );



// カレンダーPC
function child_tcd_output_events_archive_calender_pc( $y, $m, $d ){

  $tcd_calender_basic_options = get_option( 'tcd_calender_basic_options' );
  $event_button_label = $tcd_calender_basic_options['events_button_label'] ?? __( 'Event Details', 'tcd-gaia' );
  $single_event_button_label = $tcd_calender_basic_options['single_events_button_label'] ?? __( 'Check event', 'tcd-gaia' );
  $default_holiday_label = $tcd_calender_basic_options['holiday_label'] ?? __( 'Regular Holiday', 'tcd-gaia' );

?>
<table class="p-calender p-calender--pc p-calender--<?php echo $y . sprintf('%02d', $m) ?>">
<?php

  // table header
  $weeks = tcd_get_weekday();

?>
  <thead class="p-calender__header">
    <tr class="p-calender__header-row">
    <?php foreach( $weeks as $wd_key => $week ) { ?>
    <th class="p-calender__header-item p-calender__header-item--<?php echo $wd_key; ?>" scope="col" title="<?php echo $wd_key; ?>"><?php echo $week; ?></th>
    <?php } ?>
    </tr>
  </thead>
<?php

  // table body
  $this_days = tcd_get_calender_days( $y, $m );

?>
  <tbody class="p-calender__body">
<?php

  foreach( $this_days as $days ){

    echo '<tr class="p-calender__body-row">';

    foreach( $days as $values ){

      if( $values == 'pad' ){
				continue;

        echo '<td class="p-calender__body-item p-calender__body-item--pad"></td>';

      }else{

        $day = $values['day'] ?? '';
				if($day < 9 || $day >=16){
					continue;
				}

        $week = $values['week'] ?? '';
        $state = $values['state'] ?? '';

        $event_type = $values['options']['type'] ?? null;
        $event_ids = tcd_publish_events_ids( $values['options']['ids'] ?? array() );

        $caption_title = $values['options']['caption_title'] ?? '';
        $caption_desc = $values['options']['caption_desc'] ?? '';
        $holiday_label = $values['options']['holiday_label'] ?? '';

        echo '<td class="p-calender__body-item p-calender__body-item--' . $day . ' is-' . $state . ' p-calender__body-item-type--' . $event_type . ' p-calender__body-item-week--' . $week . '">';

        // 日付
        echo '<div class="p-calender__body-item__day u-flex-align-center">' . $day;

        // アイコン
        if( $event_type == 'event' && ( !empty( $event_ids ) || ( $caption_title && $caption_desc ) ) ){
          echo '<button class="p-calender__body-item__modal c-icon--dp u-button-reset js-calender-modal-icon" type="buttton" data-y="' . $y . '" data-m="' . $m . '" data-d="' . $day . '">&#xe958;</button>';
        }

        echo '</div>';

        // イベント
        if( $event_type == 'event' ){

          // キャプション
          
            echo '<div class="p-calender__body-item__comment">';
            echo '<p class="p-calender__body-item__comment-text c-line2 c-link-color u-flex-align-center">';
            echo '<span>' . wp_kses_post( $caption_title ? $caption_title : $caption_desc ) . '</span>';
            echo '</p>';
            echo '</div>';
          
          // イベント
          if( !empty( $event_ids ) ){
            $day = sprintf('%02d', $day);
            if(count($event_ids) > 1){
            echo '<a class="p-calender__body-item__link" href="' . add_query_arg( array( 'calender' => $y . '-' . sprintf('%02d', $m) . '-' . sprintf('%02d', $day) ), get_post_type_archive_link('events') ) . '">';
            echo '<span class="p-calender__body-item__link-button u-flex-center">' . esc_html( $event_button_label ) . '</span>';
            echo '</a>';
          }elseif( count( $event_ids ) === 1 ){
              echo '<a class="p-calender__body-item__link" href="' . esc_url( get_permalink($event_ids[0]) )  . '">';
              echo '<span class="p-calender__body-item__link-button u-flex-center">' . esc_html( $single_event_button_label ) . '</span>';
              echo '</a>';
            }
          }

        }elseif( $event_type == 'holiday' ){

          echo '<div class="p-calender__body-item__holiday u-flex-center">';
          echo esc_html( $holiday_label ? $holiday_label : $default_holiday_label );
          echo '</div>';

        }

        echo '</td>';

      }

    }

    echo '</tr>';

  }

?>
  </tbody>
</table>
<?php

}
add_action( 'child_tcd_events_archive_calender', 'child_tcd_output_events_archive_calender_pc', 20, 3 );



// カレンダーSP
function child_tcd_output_events_archive_calender_sp( $y, $m, $d ){

  $this_days = tcd_get_calender_days( $y, $m );
  $week_labels = tcd_get_weekday();

  $tcd_calender_basic_options = get_option( 'tcd_calender_basic_options' );
  $default_holiday_label = $tcd_calender_basic_options['holiday_label'] ?? __( 'Regular Holiday', 'tcd-gaia' );

?>
<table class="p-calender--sp p-calender--<?php echo $y . sprintf('%02d', $m) ?>">
  <tbody class="p-calender--sp__body">
<?php

  foreach( $this_days as $day_rows ){
    foreach( $day_rows as $day_values ){

      if( $day_values == 'pad' ) continue;

      $day = $day_values['day'] ?? '';
      $week = $day_values['week'] ?? '';
      $event_type = $day_values['options']['type'] ?? '';
      $event_ids = tcd_publish_events_ids( $day_values['options']['ids'] ?? array() );
      $holiday_label = $day_values['options']['holiday_label'] ?? null;

?>
    <tr class="p-calender--sp__body-row p-calender--sp__body-item--day-<?php echo $day; ?>">
<?php

      // 日付

?>
      <td class="p-calender--sp__body-item p-calender--sp__body-item--day p-calender--sp__body-item--<?php echo $event_type; ?> p-calender--sp__body-item--week-<?php echo $week; ?>">
        <div class="p-calender--sp__body-item--day-wrapper u-flex-center">
          <span class="p-calender--sp__body-item--day-number"><?php echo $day; ?></span>
          <span class="p-calender--sp__body-item--day-week">(<?php echo $week_labels[$week] ?? ''; ?>)</span>
        </div>
      </td>
<?php

      // 定休日
      if( $event_type == 'holiday' ){

?>
      <td class="p-calender--sp__body-item p-calender--sp__body-item--holiday" colspan="3">
        <p class="p-calender--sp__body-item--holiday-label">
          <?php echo esc_html( $holiday_label ? $holiday_label : $default_holiday_label ); ?>
        </p>
      </td>
<?php

      }else{

        // キャプション
        $caption_title = $day_values['options']['caption_title'] ?? '';
        $caption_desc = $day_values['options']['caption_desc'] ?? '';

?>
      <td class="p-calender--sp__body-item p-calender--sp__body-item--caption">
<?php

        if( $caption_title || $caption_desc ){

?>
        <div class="p-calender--sp__body-item--caption-wrapper u-flex-align-center">
          <div class="p-calender--sp__body-item--caption-text c-link-color c-line2">
            <span><?php echo wp_kses_post( $caption_title ? $caption_title : $caption_desc ); ?></span>
          </div>
        </div>
<?php

        }

?>
      </td>
<?php

      // モーダル

?>
      <td class="p-calender--sp__body-item p-calender--sp__body-item--modal">
<?php
        if( !empty( $event_ids ) || ( $caption_title && $caption_desc ) ){
          echo '<button class="p-calender--sp__body-item--modal-icon p-calender__body-item__modal c-icon--dp u-button-reset u-flex-center js-calender-modal-icon" type="buttton" data-y="' . $y . '" data-m="' . $m . '" data-d="' . $day . '">&#xe958;</button>';
        }
?>
      </td>
<?php

      // イベント一覧

?>
      <td class="p-calender--sp__body-item p-calender--sp__body-item--events">
      <?php

        if( !empty( $event_ids ) ){
          if(count($event_ids) > 1){
            echo '<a class="p-calender--sp__body-item--events-link u-flex-center" href="' . add_query_arg( array( 'calender' => $y . '-' . sprintf('%02d', $m) . '-' . sprintf('%02d', $day) ), get_post_type_archive_link('events') ) . '">';
            echo __( 'More', 'tcd-gaia' );
            echo '</a>';
          }elseif( count( $event_ids ) === 1 ){
            echo '<a class="p-calender--sp__body-item--events-link u-flex-center" href="' . esc_url( get_permalink($event_ids[0]) )  . '">';
            echo __( 'Check', 'tcd-gaia' );
            echo '</a>';
          }
        }

      ?>
      </td>
<?php

      }

?>
    </tr>
<?php

    }
  }

?>
  </tbody>
</table>
<?php

}
add_action( 'child_tcd_events_archive_calender', 'child_tcd_output_events_archive_calender_sp', 20, 3 );



// 今月のイベント一覧
function child_tcd_output_events_archive_this_month( $y, $m, $d ){
  if ( is_front_page() ) { return; } // トップページでは今月のイベント一覧を出力しない
  $tcd_calender_options = is_array( get_option( 'tcd_calender_options' ) ) ? get_option( 'tcd_calender_options' ) : array();
  $tcd_calender_basic_options = get_option( 'tcd_calender_basic_options' );

  $events_list_headline = $tcd_calender_basic_options['events_list_headline'] ?? __( 'Recommended Events this month', 'tcd-gaia' );
	$events_list_headline = "スケジュール一覧";
  $events_list_article_type = $tcd_calender_basic_options['events_list_article_type'] ?? 'type1';

?>
<section class="p-archive--events-bottom">

  <h2 class="p-archive--events-bottom__headline c-single-section-title">
    <?php echo esc_html( $events_list_headline ); ?>
  </h2>

<?php

  // 今月のイベント
  if( $events_list_article_type == 'type1' ){

    // イベントids
    $this_month_event_ids = array();
    $this_month_events = $tcd_calender_options[$y][$m] ?? array();
    ksort( $this_month_events ); // キーで並び替え
    $this_month_events = array_column( $this_month_events, 'ids' );
    foreach( $this_month_events as $event_ids ){
      $event_keys = array_keys( $event_ids );
      foreach( $event_keys as $event_id ){
        $event_id = (int) $event_id;
        $this_month_event_ids[$event_id] = $event_id;
      }
    }

    // 記事一覧
    if( !empty( $this_month_event_ids ) ){
      foreach( $this_month_event_ids as $event_id ){
        global $post;
        $post = get_post( $event_id );
        setup_postdata( $post );
        tcd_events_loop( $y, $m, $d );
      }
    }else{
      echo '<p style="text-align:center;margin-top:40px;">' . __( 'There is no registered post.', 'tcd-gaia' ) . '</p>';
    }

  // おすすめイベント
  }elseif( $events_list_article_type == 'type2' || $events_list_article_type == 'type3' || $events_list_article_type == 'type4' ){

    $args = array(
      'post_type' => 'events',
      'posts_per_page' => -1,
      'meta_query' => array(
        'relation' => 'AND',
        'recommend' => array(
          'key' => TCD_EVENTS_ARCHIVE_LIST_TYPE[$events_list_article_type]['name'],
          'value' => 'on',
          'type' => 'CHAR',
          'compare' => 'IN',
        ),
        'calender_date' => array(
          'key' => 'calender_date_start',
          'value' => 0,
          'type' => 'NUMERIC',
          'compare' => '>'
        ),
      ),
      'orderby' => array( 
        'recommend' => 'DESC',
        'calender_date' => 'ASC'
      )
    );

    $the_query = new WP_Query( $args );
    if( $the_query->have_posts() ) {

      while ($the_query->have_posts()) : $the_query->the_post();
        tcd_events_loop( $y, $m, $d );
      endwhile; wp_reset_postdata();

    }else{
      echo '<p style="text-align:center;margin-top:40px;">' . __( 'There is no registered post.', 'tcd-gaia' ) . '</p>';
    }

  }


?>
</section>
<?php

}
add_action( 'child_tcd_events_archive_calender', 'child_tcd_output_events_archive_this_month', 40, 3 );