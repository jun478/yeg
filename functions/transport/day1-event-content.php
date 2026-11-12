<?php
/**
 * Keep November 13 event itineraries aligned with the shared transport data.
 * These display filters leave the stored event content and November 14 untouched.
 */

/** Return the departure or arrival range for one shared route. */
function yeg_day1_event_route_time( $route, $key ) {
	$times = array_column( $route['times'], $key );
	$times = array_values( array_unique( $times ) );
	return count( $times ) > 1 ? reset( $times ) . '〜' . end( $times ) : reset( $times );
}

/** Return one transport row using the existing event schedule markup. */
function yeg_day1_event_schedule_row( $time, $label ) {
	return '<tr><th>' . esc_html( $time ) . '</th><td>' . esc_html( $label ) . '</td></tr>';
}

/** Update only transport rows in the designated events' itinerary tables. */
function yeg_day1_event_transport_content( $content ) {
	if ( ! is_singular( 'events' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$events = array(
		'day1-training-b-pr-dx-20261113'                => array( '06', '01', '02' ),
		'day1-training-c-member-growth-20261113'        => array( '05', '01', '02' ),
		'day1-training-d-vision-20261113'               => array( '08', '01', '04' ),
		'day1-training-e-policy-20261113'               => array( '07', '01', '02' ),
		'day1-training-f-economic-zone-20261113'        => array( '06', '01', '02' ),
		'day1-training-g-business-co-creation-20261113'  => array( '08', '03', '06' ),
		'day1-training-h-startup-20261113'              => array( '09', '01', '' ),
		'day1-training-i-international-business-20261113' => array( '08', '02', '05' ),
		'day1-training-j-international-exchange-20261113' => array( '03', '', '01' ),
		'ob-og-program-20261113'                       => array( '05', '01', '02' ),
	);
	$slug = get_post_field( 'post_name', get_the_ID() );
	if ( ! isset( $events[ $slug ] ) ) {
		return $content;
	}

	list( $page, $outbound_number, $return_number ) = $events[ $slug ];
	$registry = yeg_get_bus_guide_registry();
	$outbound = $outbound_number ? yeg_bus_overview_get_route( $registry, $page, $outbound_number ) : null;
	$return   = $return_number ? yeg_bus_overview_get_route( $registry, $page, $return_number ) : null;

	return preg_replace_callback(
		'/<table\b[^>]*class=["\'][^"\']*\byeg-event-detail__schedule\b[^"\']*["\'][^>]*>.*?<\/table>/su',
		function ( $table ) use ( $outbound, $return ) {
			return preg_replace_callback(
				'/<tr\b[^>]*>\s*<th\b[^>]*>(.*?)<\/th>\s*<td\b[^>]*>(.*?)<\/td>\s*<\/tr>/su',
				function ( $row ) use ( $outbound, $return ) {
					$time  = trim( html_entity_decode( wp_strip_all_tags( $row[1] ), ENT_QUOTES, 'UTF-8' ) );
					$label = trim( html_entity_decode( wp_strip_all_tags( $row[2] ), ENT_QUOTES, 'UTF-8' ) );
					if ( $outbound && 'JTAドーム 出発（シャトルバス）' === $label ) {
						return yeg_day1_event_schedule_row( yeg_day1_event_route_time( $outbound, 'departure' ), $label );
					}
					if ( '閉会・大懇親会場へバス移動' === $label ) {
						$closing = yeg_day1_event_schedule_row( $time, '閉会' );
						if ( ! $return ) {
							return $closing . yeg_day1_event_schedule_row( '研修終了後', '漲水シンボル緑地（大懇親会会場）へ徒歩移動' );
						}
						return $closing . yeg_day1_event_schedule_row( yeg_day1_event_route_time( $return, 'departure' ), '漲水シンボル緑地（大懇親会会場）へ出発（シャトルバス）' );
					}
					if ( '大懇親会場 到着' === $label ) {
						return $return ? yeg_day1_event_schedule_row( yeg_day1_event_route_time( $return, 'arrival' ), '漲水シンボル緑地（大懇親会会場）到着' ) : '';
					}
					return $row[0];
				},
				$table[0]
			);
		},
		$content
	);
}
add_filter( 'the_content', 'yeg_day1_event_transport_content', 9 );

/** Correct the OB/OG venue shown by the parent template without editing the DB. */
function yeg_day1_event_venue_metadata( $value, $object_id, $meta_key, $single ) {
	if ( null !== $value || 'table_list' !== $meta_key || is_admin() || ! is_singular( 'events' ) || (int) get_queried_object_id() !== (int) $object_id || 'ob-og-program-20261113' !== get_post_field( 'post_name', $object_id ) ) {
		return $value;
	}

	remove_filter( 'get_post_metadata', 'yeg_day1_event_venue_metadata', 10 );
	try {
		$table = get_post_meta( $object_id, $meta_key, true );
	} finally {
		add_filter( 'get_post_metadata', 'yeg_day1_event_venue_metadata', 10, 4 );
	}
	if ( ! is_array( $table ) ) {
		return $value;
	}
	foreach ( $table as &$item ) {
		if ( isset( $item['label'] ) && '開催場所' === trim( wp_strip_all_tags( $item['label'] ) ) ) {
			$item['cell'] = 'Yard miyakojima';
		}
	}
	unset( $item );
	return array( $table );
}
add_filter( 'get_post_metadata', 'yeg_day1_event_venue_metadata', 10, 4 );
