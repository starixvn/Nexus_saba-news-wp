<?php
/**
 * Template Name: Pagebuilder - No Paddings & Full Width
 */

Bunyad::core()->set_sidebar('none');
get_header();

if (Bunyad::options()->breadcrumbs_pagebuilder && !Bunyad::posts()->meta('hide_breadcrumbs')) {
	Bunyad::blocks()->load('Breadcrumbs')->render();
}

?>

<div class="main-full">
	<?php if (have_posts()): the_post(); endif; // load the page ?>

	<div id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>

		<?php Bunyad::posts()->the_content(); ?>

	</div>
</div>

<?php get_footer(); ?>
