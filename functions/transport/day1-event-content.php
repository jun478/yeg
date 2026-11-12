<?php
/** Venue display correction; event content and times use the saved admin values. */

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
