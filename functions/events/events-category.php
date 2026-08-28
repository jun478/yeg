<?php

add_action( 'init', 'yeg_register_event_category_taxonomy', 20 );
function yeg_register_event_category_taxonomy() {
  register_taxonomy(
    'event_category',
    array( 'events' ),
    array(
      'label' => 'イベントカテゴリ',
      'labels' => array(
        'name' => 'イベントカテゴリ',
        'singular_name' => 'イベントカテゴリ',
        'search_items' => 'イベントカテゴリを検索',
        'all_items' => 'すべてのイベントカテゴリ',
        'edit_item' => 'イベントカテゴリを編集',
        'update_item' => 'イベントカテゴリを更新',
        'add_new_item' => '新規イベントカテゴリを追加',
        'new_item_name' => '新規イベントカテゴリ名',
        'menu_name' => 'イベントカテゴリ',
      ),
      'public' => true,
      'hierarchical' => true,
      'show_ui' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
      'query_var' => true,
      'rewrite' => array(
        'slug' => 'events-category',
        'with_front' => false,
      ),
    )
  );
}

function yeg_event_opening_hours_sort_value( $post_id ) {
  $opening_hours = (string) get_post_meta( $post_id, 'opening_hours', true );

  if ( preg_match( '/(\d{1,2})[:：](\d{2})/', $opening_hours, $matches ) ) {
    return ( (int) $matches[1] * 60 ) + (int) $matches[2];
  }

  return PHP_INT_MAX;
}

function yeg_sort_events_by_opening_hours( $posts ) {
  usort( $posts, function( $post_a, $post_b ) {
    $time_a = yeg_event_opening_hours_sort_value( $post_a->ID );
    $time_b = yeg_event_opening_hours_sort_value( $post_b->ID );

    if ( $time_a === $time_b ) {
      return $post_a->ID <=> $post_b->ID;
    }

    return $time_a <=> $time_b;
  } );

  return $posts;
}
