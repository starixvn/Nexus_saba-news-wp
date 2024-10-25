<?php
/**
 * Archives Page!
 * 
 * This page is used for all kind of archives from custom post types to blog to 'by date' archives.
 * 
 * Bunyad framework recommends this template to be used as generic template wherever any sort of listing 
 * needs to be done.
 * 
 * Types of archives handled:
 * 
 *  - Categories
 *  - Tags
 *  - Taxonomies
 *  - Date Archives
 *  - Custom Post Types
 * 
 * @link http://codex.wordpress.org/images/1/18/Template_Hierarchy.png
 */

// Legacy: loop was $template
if (!empty($GLOBALS['bunyad_loop_template'])) {
	$props['loop'] = $GLOBALS['bunyad_loop_template'];
	//_deprecated_argument('archive.php', '4.0', 'Fix: $bunyad_loop_template is deprecated - update child theme files.');
}

/**
 * Setup heading, description and loop.
 */
$default_loop = Bunyad::archives()->get_default_loop();
$heading      = Bunyad::archives()->get_heading();

// For archives that support it
$description = is_author() ? '' : get_the_archive_description();

/**
 * Props setup.
 */
$props = isset($props) ? $props : [];
$props = array_replace([
	'loop'      => $default_loop,
	'columns'   => 1,
	'sidebar'   => Bunyad::options()->archive_sidebar,
	'loop_args' => [],
], $props);



// Set sidebar early to ensure it's accounted for in images dimensions.
if ($props['sidebar']) {
	Bunyad::core()->set_sidebar($props['sidebar']);
}

/**
 * Begin HTML output.
 */
get_header();
Bunyad::blocks()->load('Breadcrumbs')->render();

// Slider for categories
if (is_category()) {
	get_template_part('partials/featured-area');
}

// After everything else is done. Note: Must be after featured-area.
$props = Bunyad::archives()->process_props($props);

?>

<div <?php Bunyad::markup()->attribs('main'); ?>>
	<?php if (apply_filters('bunyad_do_partial_archive', true)): ?>
		<div class="ts-row">
			<div class="col-8 main-content">

			<?php if ($heading): ?>
				<h1 class="archive-heading">
					<?php echo $heading; // phpcs:ignore WordPress.Security.EscapeOutput -- Safe markup generated in Bunyad_Theme_Archives::get_heading() ?>
				</h1>
			<?php endif; ?>
			
			<?php if (Bunyad::options()->archive_descriptions && !empty($description)): ?>
				<div class="archive-description base-text">
					<?php echo do_shortcode(wp_kses_post($description)); ?>
				</div>
			<?php endif; ?>
		
			<?php if (is_author()): // Author box for author archives. ?>
				<div class="archive-author-box">
					<?php get_template_part('partials/author'); ?>
				</div>
			<?php endif; ?>
				
			<?php

				Bunyad::blocks()->load_loop(
					$props['loop'], 
					[
						// Stickies only for home/blog "archives" (not static frontpage).
						'sticky_posts' => is_home(),
						'columns'      => $props['columns'],
					] + $props['loop_args']
				)
				->render();
			?>

			</div>
			
			<?php Bunyad::core()->theme_sidebar(); ?>
			
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>