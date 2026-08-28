<?php

get_header();

$term = get_queried_object();
$paged = max( 1, (int) get_query_var( 'paged' ) );
$posts_per_page = 12;

$event_query = new WP_Query( array(
  'post_type' => 'events',
  'post_status' => 'publish',
  'posts_per_page' => -1,
  'tax_query' => array(
    array(
      'taxonomy' => 'event_category',
      'field' => 'term_id',
      'terms' => array( $term->term_id ),
    ),
  ),
) );

$event_posts = yeg_sort_events_by_opening_hours( $event_query->posts );
$total_posts = count( $event_posts );
$paged_posts = array_slice( $event_posts, ( $paged - 1 ) * $posts_per_page, $posts_per_page );
$max_num_pages = (int) ceil( $total_posts / $posts_per_page );

?>
    <div class="p-archive p-archive--events p-archive--event-category">
<?php if ( ! empty( $paged_posts ) ) { ?>
      <div class="p-archive--events-day__list p-events-loop">
<?php
  global $post, $wp_query;
  $original_query = $wp_query;
  $pager_query = new WP_Query();
  $pager_query->max_num_pages = $max_num_pages;
  $wp_query = $pager_query;

  foreach ( $paged_posts as $event_post ) {
    $post = $event_post;
    setup_postdata( $post );
    tcd_events_loop( 0, 0, 0 );
  }

  wp_reset_postdata();
?>
      </div>
<?php
  if ( $max_num_pages > 1 ) {
    get_template_part( 'template-parts/pager' );
  }

  $wp_query = $original_query;
} else {
?>
      <p class="c-no-post"><?php echo __tcd( 'no_post' ); ?></p>
<?php } ?>
    </div>
<?php

get_footer();
