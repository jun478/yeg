<?php

function yeg_facility_event_place_aliases( $facility_id ) {
  $facility_slug = get_post_field( 'post_name', $facility_id );
  $facility_name = get_the_title( $facility_id );

  $aliases = array(
    $facility_name,
  );

  $slug_aliases = array(
    'jta-dome-miyakojima' => array(
      'JTAドーム',
      'JTAドーム 特設会場',
      'JTAドーム宮古島',
      'JTAドーム宮古島 大会議室',
    ),
    'turiba-seaside-park' => array(
      'トゥリバー',
      'トゥリバー海浜公園',
      'トゥリバー海浜公園 特設会場',
    ),
    'miyakojima-future-creation-center' => array(
      '宮古島市未来創造センター',
      '宮古島未来創造センター',
      '宮古島市未来創造センター 研修室',
      '宮古島未来創造センター 研修室',
      '宮古島市未来創造センター 多目的ホール',
      '宮古島未来創造センター 多目的ホール',
    ),
    'hirara-port-marine-terminal' => array(
      '平良港マリンターミナルビル',
      '平良港マリンターミナルビル 大会議室',
      'マリンターミナル 大会議室',
    ),
    'hotel-atorremerald-miyakojima' => array(
      'ホテルアトールエメラルド宮古島',
      'ホテルアトールエメラルド宮古島 漲水の間',
      'ホテルアトールエメラルド宮古島 漲水の間A・B',
    ),
    'matida-civic-theater' => array(
      'マティダ市民劇場',
    ),
    'hilton-okinawa-miyako-island-resort' => array(
      'ヒルトン沖縄宮古島リゾート',
      'ヒルトン沖縄宮古島リゾート ボールルームPANA',
    ),
    'hotel-shigira-mirage' => array(
      'ホテルシギラミラージュ',
      'ホテルシギラミラージュ コンベンションホール1F',
      'ホテルシギラミラージュ コンベンションホール2F',
    ),
    'shigira-seven-miles-resort-shimauta' => array(
      'シギラセブンマイルズリゾート 島唄',
    ),
    'hotel-breezebay-marina' => array(
      'ホテルブリーズベイマリーナ',
      'ホテルブリーズベイマリーナ コンベンションホール',
    ),
    'yard-miyakojima' => array(
      'Yard Miyakojima',
    ),
    'alternative-farm-miyakojima' => array(
      'オルタナティブファーム宮古島',
    ),
    'yukishio-museum-karimata' => array(
      '雪塩ミュージアム',
      '狩俣地区',
      '雪塩ミュージアム・狩俣地区',
    ),
    'hirara-city-area' => array(
      '平良市街地周辺',
    ),
    'rosewood-miyakojima' => array(
      'ローズウッド宮古島',
      'シーウッドホテル宮古島',
      '宮古島来間リゾート シーウッドホテル',
    ),
    'underground-dam-museum-fukuzato-dam' => array(
      '地下ダム資料館',
      '福里ダム',
      '地下ダム資料館・福里ダム',
    ),
  );

  if ( isset( $slug_aliases[ $facility_slug ] ) ) {
    $aliases = array_merge( $aliases, $slug_aliases[ $facility_slug ] );
  }

  $aliases = array_filter( array_map( 'trim', $aliases ) );

  return array_values( array_unique( $aliases ) );
}

function yeg_get_event_place( $event_id ) {
  $table_list = get_post_meta( $event_id, 'table_list', true );

  if ( ! is_array( $table_list ) ) {
    return '';
  }

  foreach ( $table_list as $table_item ) {
    if ( isset( $table_item['label'], $table_item['cell'] ) && '開催場所' === $table_item['label'] ) {
      return (string) $table_item['cell'];
    }
  }

  return '';
}

function yeg_event_place_matches_facility( $place, $aliases ) {
  if ( '' === $place ) {
    return false;
  }

  foreach ( $aliases as $alias ) {
    if ( '' !== $alias && false !== mb_strpos( $place, $alias ) ) {
      return true;
    }
  }

  return false;
}

function yeg_facility_event_sort_key( $event_id ) {
  $date_key = (int) get_post_meta( $event_id, 'calender_date_start', true );
  $time_key = function_exists( 'yeg_event_opening_hours_sort_value' )
    ? yeg_event_opening_hours_sort_value( $event_id )
    : PHP_INT_MAX;

  return array( $date_key, $time_key, (int) $event_id );
}

function yeg_get_facility_events( $facility_id ) {
  $aliases = yeg_facility_event_place_aliases( $facility_id );
  $events  = get_posts(
    array(
      'post_type'      => 'events',
      'post_status'    => 'publish',
      'posts_per_page' => -1,
      'meta_key'       => 'calender_date_start',
      'orderby'        => 'meta_value_num',
      'order'          => 'ASC',
    )
  );

  $matched_events = array();

  foreach ( $events as $event ) {
    $place = yeg_get_event_place( $event->ID );

    if ( yeg_event_place_matches_facility( $place, $aliases ) ) {
      $matched_events[] = $event;
    }
  }

  usort(
    $matched_events,
    function ( $event_a, $event_b ) {
      $sort_a = yeg_facility_event_sort_key( $event_a->ID );
      $sort_b = yeg_facility_event_sort_key( $event_b->ID );

      return $sort_a <=> $sort_b;
    }
  );

  return $matched_events;
}

function yeg_output_facility_events() {
  if ( ! is_singular( 'facility' ) ) {
    return;
  }

  $facility_id = get_the_ID();
  $events      = yeg_get_facility_events( $facility_id );

  if ( empty( $events ) ) {
    return;
  }

?>
  <section class="p-single--facility-events" style="margin-top: 60px;">
    <h2 class="p-single--facility-events__headline c-single-section-title" style="color: white;">この会場で開かれるイベント</h2>
    <div class="p-single--facility-events__list p-events-loop">
<?php

  foreach ( $events as $event ) {
    global $post;
    $post = $event;
    setup_postdata( $post );
    tcd_events_loop( 0, 0, 0 );
  }

  wp_reset_postdata();

?>
    </div>
  </section>
<?php

}
add_action( 'yeg_single_facility_after_pager', 'yeg_output_facility_events', 10 );
