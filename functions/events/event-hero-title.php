<?php

function yeg_add_event_hero_title( $html ) {
  if ( ! is_singular( 'events' ) || false !== strpos( $html, 'yeg-event-hero__title' ) ) {
    return $html;
  }

  $needle = '<div class="p-page-header__content">';
  $title  = sprintf(
    '<h1 class="p-page-header__headline yeg-event-hero__title">%s</h1>',
    esc_html( get_the_title( get_queried_object_id() ) )
  );

  return str_replace( $needle, $needle . $title, $html );
}
add_filter( 'tcd_archive_header', 'yeg_add_event_hero_title' );
