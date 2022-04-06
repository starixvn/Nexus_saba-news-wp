<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Telegram
 */

get_header();
?>
	<div class="site-content">
		<?php
		while ( have_posts() ) :
			the_post();
	
			get_template_part( 'template-parts/content','single' );

		endwhile; // End of the loop.
		?>
	</div>
	<?php 
		/**
		 * Hook telegram_related_posts
		 *
		 * @hooked telegram_get_related_posts
		 */
		do_action('telegram_related_posts'); ?>


<?php
get_footer();
