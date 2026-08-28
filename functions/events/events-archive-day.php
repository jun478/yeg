<?php

add_action( 'after_setup_theme', 'yeg_replace_events_archive_day_output', 20 );
function yeg_replace_events_archive_day_output() {
  remove_action( 'tcd_events_archive_day', 'tcd_output_events_archive_day', 10 );
  add_action( 'tcd_events_archive_day', 'yeg_output_events_archive_day', 10, 3 );
}

function yeg_move_event_id_after_by_title( $event_ids, $target_title_part, $after_title_part ) {
  $event_ids    = array_values( $event_ids );
  $target_index = null;
  $after_index  = null;

  foreach ( $event_ids as $index => $event_id ) {
    $title = get_the_title( $event_id );

    if ( null === $target_index && false !== mb_strpos( $title, $target_title_part ) ) {
      $target_index = $index;
    }

    if ( null === $after_index && false !== mb_strpos( $title, $after_title_part ) ) {
      $after_index = $index;
    }
  }

  if ( null === $target_index || null === $after_index || $target_index === $after_index ) {
    return $event_ids;
  }

  $target_event_id = $event_ids[ $target_index ];
  array_splice( $event_ids, $target_index, 1 );

  if ( $target_index < $after_index ) {
    $after_index--;
  }

  array_splice( $event_ids, $after_index + 1, 0, array( $target_event_id ) );

  return $event_ids;
}

function yeg_reorder_events_archive_day_ids( $event_ids, $year, $month, $day ) {
  if ( 2026 === (int) $year && 11 === (int) $month && 14 === (int) $day ) {
    return yeg_move_event_id_after_by_title( $event_ids, '分科会I', '分科会H' );
  }

  return $event_ids;
}

function yeg_output_events_archive_day( $y, $m, $d ) {
  $unixmonth = mktime( 0, 0, 0, $m, $d, $y );
  ?>
  <div class="p-archive--events-day">

    <h2 class="p-archive--events-day__headline c-single-section-title">
      <?php echo date( __( 'F jS, Y', 'tcd-gaia' ), $unixmonth ); ?>
    </h2>
  <?php

  $tcd_calender_options = is_array( get_option( 'tcd_calender_options' ) ) ? get_option( 'tcd_calender_options' ) : array();
  $event_ids            = tcd_publish_events_ids( $tcd_calender_options[ $y ][ $m ][ $d ]['ids'] ?? array() );
  $event_ids            = yeg_reorder_events_archive_day_ids( $event_ids, $y, $m, $d );

  if ( ! empty( $event_ids ) ) {
    ?>
    <div class="p-archive--events-day__list p-events-loop">
    <?php

    foreach ( $event_ids as $event_id ) {
      global $post;
      $post = get_post( $event_id );
      setup_postdata( $post );
      tcd_events_loop( $y, $m, $d );
    }
    wp_reset_postdata();

    ?>
    </div>
    <div class="p-archive--events-day__nav u-flex-center">
      <a class="p-archive--events-day__nav-button c-button" href="<?php echo esc_url( get_post_type_archive_link( 'events' ) ); ?>">
        <?php printf( __( '%s Articles', 'tcd-gaia' ), tcd_get_post_type_label( 'events' ) ); ?>
      </a>
    </div>
    <?php
  }

  ?>
  </div>
  <?php
}
