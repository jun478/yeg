<?php
/**
 * Facility archive without the optional bottom catchphrase section.
 */

get_header();

if ( have_posts() ) :
	?>
	<div class="p-archive p-archive--facility">
		<div class="p-archive--facility-loop u-flex-wrap">
			<?php
			while ( have_posts() ) :
				the_post();
				tcd_facility_loop();
			endwhile;

			get_template_part( 'template-parts/pager' );
			?>
		</div>
	</div>
	<?php
endif;

get_footer();
