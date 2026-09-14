<?php

  get_header();

  global $dp_options;
  if ( ! $dp_options ) $dp_options = get_design_plus_option();

?>
<article class="p-single p-single--facility">
<?php

  if ( have_posts() ) : while ( have_posts() ) : the_post();

?>
  <h1 class="p-single--facility__title c-single-section-title">
    <?php the_title(); ?>
  </h1>
  <div class="p-single--facility__wrapper">
<?php

    if( $page == '1' ) {

      if ( has_post_thumbnail() ) :

        echo '<div class="p-single--facility__image">' . "\n";
        the_post_thumbnail( 'post-thumbnail' );
        echo "</div>\n";

        // キャプション
        $thumb_caption = get_post( get_post_thumbnail_id() )->post_excerpt;
        if( $thumb_caption ){
          echo '<p class="p-single--facility__caption">';
          echo $thumb_caption;
          echo '</p>';
        }

      endif;

      /**
       * Hook: tcd_single_facility_before_content.
       *
       * @hooked tcd_share_button - 10
       * @hooked tcd_render_copy_title_url_button - 20
       * @hooked tcd_single_banner_top - 30
       */
      do_action( 'tcd_single_facility_before_content', $dp_options );

    }

?>
    <div class="p-single__margin p-single--facility__content">
      <div class="post_content u-clearfix">
<?php

    // post content
    the_content();
    if ( ! post_password_required() ) custom_wp_link_pages();

?>
      </div>
    </div>
<?php

    $facility_address = get_post_meta( get_the_ID(), 'map_address', true );
    if ( $facility_address ) :
      $facility_map_url = add_query_arg(
        array(
          'q'      => $facility_address,
          'output' => 'embed',
        ),
        'https://www.google.com/maps'
      );

?>
    <section class="p-single__margin yeg-facility-access">
      <h2 class="p-single--facility-events__headline yeg-facility-access__title">アクセス</h2>
      <dl class="yeg-facility-access__details">
        <div class="yeg-facility-access__row">
          <dt class="yeg-facility-access__label">住所</dt>
          <dd class="yeg-facility-access__value">
            <address><?php echo esc_html( $facility_address ); ?></address>
          </dd>
        </div>
      </dl>
      <div class="yeg-facility-access__map">
        <iframe src="<?php echo esc_url( $facility_map_url ); ?>" title="<?php echo esc_attr( get_the_title() . 'のGoogle Map' ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
      </div>
    </section>
<?php

    endif;

?>
<?php

    /**
     * Hook: tcd_single_facility_after_content.
     *
     * @hooked tcd_share_button - 10
     * @hooked tcd_render_copy_title_url_button - 20
     * @hooked tcd_single_banner_bottom - 30
     */
    do_action( 'tcd_single_facility_after_content', $dp_options );

?>
  </div>
<?php

    // pager
    $prev_post = get_previous_post();
    $next_post = get_next_post();

?>
  <div class="p-single--facility__pager u-flex-center">

    <p class="p-single--facility__pager-link p-single--facility__pager-prev">
      <?php if ( $prev_post ) { ?>
      <a class="p-single--facility__pager-arrow u-flex-align-center" href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>">
        <span class="p-single--facility__pager-icon c-icon">&#xe5cb;</span>
        <span class="p-single--facility__pager-label"><?php _e( 'Previous page', 'tcd-gaia' ); ?></span>
      </a>
      <?php } ?>
    </p>

    <a class="p-single--facility__pager-button c-button" href="<?php echo esc_url( get_post_type_archive_link( 'facility' ) ); ?>">
      <?php echo tcd_get_post_type_label( 'facility' ); ?>
    </a>

    <p class="p-single--facility__pager-link p-single--facility__pager-next">
      <?php if ( $next_post ) { ?>
      <a class="p-single--facility__pager-arrow u-flex-align-center" href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>">
        <span class="p-single--facility__pager-label"><?php _e( 'Next page', 'tcd-gaia' ); ?></span>
        <span class="p-single--facility__pager-icon c-icon">&#xe5cc;</span>
      </a>
      <?php } ?>
    </p>

  </div>
<?php

    do_action( 'yeg_single_facility_after_pager' );

  endwhile; endif;

?>
</article>
<?php

  get_footer();
