<?php
/** Link event venue fields to existing published facility pages at display time. */

/** Normalize spacing and Latin capitalization for the existing venue aliases. */
function yeg_event_venue_match_text( $text ) {
	$text = html_entity_decode( wp_strip_all_tags( $text ), ENT_QUOTES, 'UTF-8' );
	return mb_strtolower( preg_replace( '/[\s\x{3000}]+/u', '', $text ), 'UTF-8' );
}

/** Resolve an unambiguous venue using the same aliases as the facility event list. */
function yeg_event_venue_facility_url( $place ) {
	static $facilities = null;
	if ( null === $facilities ) {
		$facilities = array();
		$posts = get_posts(
			array(
				'post_type' => 'facility',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'no_found_rows' => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);
		foreach ( $posts as $facility ) {
			$facilities[] = array(
				'url' => get_permalink( $facility ),
				'aliases' => array_map( 'yeg_event_venue_match_text', yeg_facility_event_place_aliases( $facility->ID ) ),
			);
		}
	}
	$place = yeg_event_venue_match_text( $place );
	$matches = array();
	foreach ( $facilities as $facility ) {
		foreach ( $facility['aliases'] as $alias ) {
			if ( '' !== $alias && false !== mb_strpos( $place, $alias ) ) {
				$matches[ $facility['url'] ] = true;
				break;
			}
		}
	}
	return 1 === count( $matches ) ? key( $matches ) : '';
}

/** Decorate only the queried event's venue, after the day-specific corrections. */
function yeg_event_venue_link_metadata( $value, $object_id, $meta_key, $single ) {
	if ( 'table_list' !== $meta_key || is_admin() || ! is_singular( 'events' ) || (int) get_queried_object_id() !== (int) $object_id ) {
		return $value;
	}
	if ( null === $value ) {
		remove_filter( 'get_post_metadata', 'yeg_event_venue_link_metadata', 20 );
		try {
			$table = get_post_meta( $object_id, $meta_key, true );
		} finally {
			add_filter( 'get_post_metadata', 'yeg_event_venue_link_metadata', 20, 4 );
		}
	} else {
		$table = is_array( $value ) ? reset( $value ) : $value;
	}
	if ( ! is_array( $table ) ) {
		return $value;
	}
	foreach ( $table as &$item ) {
		if ( ! isset( $item['label'], $item['cell'] ) || '開催場所' !== trim( wp_strip_all_tags( $item['label'] ) ) || preg_match( '/<a\b/i', $item['cell'] ) ) {
			continue;
		}
		$url = yeg_event_venue_facility_url( $item['cell'] );
		if ( '' !== $url ) {
			$item['cell'] = '<a class="yeg-event-venue-link" href="' . esc_url( $url ) . '">' . wp_kses_post( $item['cell'] ) . '</a>';
		}
	}
	unset( $item );
	return array( $table );
}
add_filter( 'get_post_metadata', 'yeg_event_venue_link_metadata', 20, 4 );
