<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Telegram
 */

get_header();
?>
	<section class="twp-404 error-404 not-found twp-min-height">
		<div class="twp-wrapper">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'telegram' ); ?></h1>
			</header><!-- .page-header -->
			<?php get_search_form(); ?>
		</div>
	</section><!-- .error-404 -->
	

<?php
get_footer();
