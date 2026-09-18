<?php

add_filter( 'get_the_excerpt', 'yeg_add_event_online_stream_button', 20, 2 );
function yeg_add_event_online_stream_button( $excerpt, $post ) {
  if (
    ! is_singular( 'events' ) ||
    ! in_the_loop() ||
    ! $post instanceof WP_Post ||
    get_queried_object_id() !== (int) $post->ID
  ) {
    return $excerpt;
  }

  $button_states = array(
    'breakout-j-20261114'                 => true,
    'current-president-training-20261113' => false,
  );

  if ( ! array_key_exists( $post->post_name, $button_states ) ) {
    return $excerpt;
  }

  $is_disabled = $button_states[ $post->post_name ];

  $button = '<span class="yeg-event-online-stream">';
  $button .= '<button class="yeg-event-online-stream__button c-button" type="button"';
  if ( $is_disabled ) {
    $button .= ' disabled aria-label="オンライン配信（現在は利用できません）"';
  } else {
    $button .= ' aria-label="オンライン配信"';
  }
  $button .= '>オンライン配信</button>';
  $button .= '</span>';

  return $excerpt . $button;
}
