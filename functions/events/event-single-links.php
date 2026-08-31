<?php

// イベント詳細ページが整うまで、イベント詳細へのリンクを子テーマ側で一時停止する。
// 詳細ページへの導線を戻す場合は false に変更する。
if ( ! defined( 'YEG_DISABLE_EVENTS_SINGLE_LINKS' ) ) {
  define( 'YEG_DISABLE_EVENTS_SINGLE_LINKS', true );
}

function tcd_events_single_links_enabled() {
  return ! YEG_DISABLE_EVENTS_SINGLE_LINKS;
}

function yeg_is_events_single_links_disabled() {
  return ! tcd_events_single_links_enabled();
}

add_filter( 'post_type_link', 'yeg_disable_events_single_permalink', 20, 2 );
function yeg_disable_events_single_permalink( $post_link, $post ) {
  if ( ! yeg_is_events_single_links_disabled() ) {
    return $post_link;
  }

  if ( ! $post || $post->post_type !== 'events' ) {
    return $post_link;
  }

  if ( is_admin() && ! wp_doing_ajax() ) {
    return $post_link;
  }

  return '#';
}

add_filter( 'nav_menu_link_attributes', 'yeg_disable_events_single_nav_menu_link', 30, 4 );
function yeg_disable_events_single_nav_menu_link( $atts, $menu_item, $args, $depth ) {
  if ( ! yeg_is_events_single_links_disabled() ) {
    return $atts;
  }

  if ( $menu_item->type !== 'post_type' || $menu_item->object !== 'events' ) {
    return $atts;
  }

  unset( $atts['href'] );
  $atts['aria-disabled'] = 'true';
  $atts['tabindex']      = '-1';

  return $atts;
}

add_action( 'wp_footer', 'yeg_disable_events_single_link_clicks', 99 );
function yeg_disable_events_single_link_clicks() {
  if ( ! yeg_is_events_single_links_disabled() ) {
    return;
  }
  ?>
  <script>
    document.addEventListener('click', function(event) {
      var link = event.target.closest('a[href="#"]');
      if (!link) {
        return;
      }

      if (link.closest('.p-events-loop, .p-fp-events, .p-widget-events, .p-megamenu01, .p-calender, .p-calender--sp, .p-calender-modal')) {
        event.preventDefault();
      }
    });
  </script>
  <?php
}
