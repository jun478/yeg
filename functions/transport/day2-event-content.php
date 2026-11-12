<?php
/** November 14 display corrections; stored content and disputed bus times stay intact. */

/** Return a time range from the shared route rows. */
function yeg_day2_event_route_time( $route, $key ) {
	$times = array_values( array_unique( array_column( $route['times'], $key ) ) );
	return count( $times ) > 1 ? reset( $times ) . '〜' . end( $times ) : reset( $times );
}

/** Render a row in the existing itinerary table. */
function yeg_day2_event_schedule_row( $time, $label ) {
	return '<tr><th>' . esc_html( $time ) . '</th><td>' . esc_html( $label ) . '</td></tr>';
}

/** Align confirmed venue transfers without replacing the event's other agenda. */
function yeg_day2_event_transport_content( $content ) {
	if ( ! is_singular( 'events' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	$events = array(
		'breakout-d-20261114' => array( '15', '' ), // Return times are disputed: retain the current rows.
		'breakout-e-20261114' => array( '15', '04' ),
		'breakout-f-20261114' => array( '16', '05' ),
		'breakout-g-20261114' => array( '16', '06' ),
		'breakout-h-20261114' => array( '16', '07' ),
		'breakout-i-20261114' => array( '15', '05' ),
		'after-party-20261114' => array( '', '' ),
	);
	$slug = get_post_field( 'post_name', get_the_ID() );
	if ( ! isset( $events[ $slug ] ) ) {
		return $content;
	}
	$is_f = 'breakout-f-20261114' === $slug;
	if ( $is_f ) {
		$content = str_replace( array( 'A班：雪塩ミュージアム', 'B班：狩俣地区', 'B班：狩俣マッチャーズ', 'A班：', 'B班：' ), array( '島の駅みやこ', '狩俣マッチャーズ', '狩俣マッチャーズ', '', '' ), $content );
	} elseif ( 'breakout-h-20261114' === $slug ) {
		$content = str_replace( 'Rosewood Miyakojima', 'シーウッドホテル宮古島', $content );
	}
	$registry = yeg_get_bus_guide_registry();
	list( $page, $number ) = $events[ $slug ];
	$return = $number ? yeg_bus_overview_get_route( $registry, $page, $number ) : null;
	$middle = $is_f ? yeg_bus_overview_get_route( $registry, '16', '04' ) : null;
	$outbound = $is_f ? yeg_bus_overview_get_route( $registry, '16', '01' ) : null;

	return preg_replace_callback(
		'/<table\b[^>]*class=["\'][^"\']*\byeg-event-detail__schedule\b[^"\']*["\'][^>]*>.*?<\/table>/su',
		function ( $table ) use ( $slug, $return, $middle, $outbound, $is_f ) {
			return preg_replace_callback(
				'/<tr\b[^>]*>\s*<th\b[^>]*>(.*?)<\/th>\s*<td\b[^>]*>(.*?)<\/td>\s*<\/tr>/su',
				function ( $row ) use ( $slug, $return, $middle, $outbound, $is_f ) {
					$time = trim( html_entity_decode( wp_strip_all_tags( $row[1] ), ENT_QUOTES, 'UTF-8' ) );
					$label = trim( html_entity_decode( wp_strip_all_tags( $row[2] ), ENT_QUOTES, 'UTF-8' ) );
					if ( 'after-party-20261114' === $slug ) {
						return '時間' === $time && '閉会式後' === $label ? yeg_day2_event_schedule_row( '時間', '14:30〜16:00' ) : $row[0];
					}
					if ( $is_f && $outbound && '宮古島公設市場 出発（シャトルバス）' === $label ) {
						return $row[0] . yeg_day2_event_schedule_row( yeg_day2_event_route_time( $outbound, 'arrival' ), '狩俣マッチャーズ到着' );
					}
					if ( '研修終了' === $label ) {
						$transfer = '';
						if ( $middle ) {
							$transfer = yeg_day2_event_schedule_row( yeg_day2_event_route_time( $middle, 'departure' ), '狩俣マッチャーズ 出発（シャトルバス）' );
							$transfer .= yeg_day2_event_schedule_row( yeg_day2_event_route_time( $middle, 'arrival' ), '島の駅みやこ到着' );
						}
						return $transfer . yeg_day2_event_schedule_row( '11:30', $label );
					}
					if ( $return && false !== strpos( $label, '出発（シャトルバス）' ) && false === strpos( $label, '公設市場' ) ) {
						return yeg_day2_event_schedule_row( yeg_day2_event_route_time( $return, 'departure' ), $return['departure_name'] . ' 出発（シャトルバス）' );
					}
					if ( $return && 'JTAドーム到着' === $label ) {
						return yeg_day2_event_schedule_row( yeg_day2_event_route_time( $return, 'arrival' ), $label );
					}
					return $row[0];
				},
				$table[0]
			);
		},
		$content
	);
}
add_filter( 'the_content', 'yeg_day2_event_transport_content', 9 );

/** Keep confirmed start times and venues consistent across event summaries and lists. */
function yeg_day2_event_metadata( $value, $object_id, $meta_key, $single ) {
	if ( null !== $value || is_admin() || ! in_array( $meta_key, array( 'opening_hours', 'table_list' ), true ) || 'events' !== get_post_type( $object_id ) ) {
		return $value;
	}
	$slug = get_post_field( 'post_name', $object_id );
	if ( 'opening_hours' === $meta_key ) {
		$times = array(
			'breakout-j-20261114' => '10:00〜11:30', // 9:30 is the meeting time; both manual schedules start at 10:00.
			'after-party-20261114' => '14:30〜16:00',
		);
		return isset( $times[ $slug ] ) ? array( $times[ $slug ] ) : $value;
	}
	if ( 'breakout-f-20261114' !== $slug ) {
		return $value;
	}
	remove_filter( 'get_post_metadata', 'yeg_day2_event_metadata', 10 );
	try {
		$table = get_post_meta( $object_id, $meta_key, true );
	} finally {
		add_filter( 'get_post_metadata', 'yeg_day2_event_metadata', 10, 4 );
	}
	if ( ! is_array( $table ) ) {
		return $value;
	}
	unset( $item );
	return array( $table );
}
add_filter( 'get_post_metadata', 'yeg_day2_event_metadata', 10, 4 );
