<?php
/**
 * Legacy: Content Template is used for every post format and used on single posts
 * 
 * Note: This is only used on classic post layout. Check partials/single/ folder for 
 * other post layouts (layout-{name}.php). This template is called by single.php
 */

$classes = get_post_class();

// Using the title above featured image variant.
$layout = Bunyad::posts()->meta('layout_template');
if (is_single() && $layout == 'classic-above') {
	$classes[] = 'title-above'; 
}
else {
	$layout = 'classic';
}

?>

<article id="post-<?php the_ID(); ?>" class="<?php echo esc_attr(join(' ', $classes)); ?>">
	
	<header class="the-post-header post-header cf">
	
		<?php if ($layout === 'classic-above'): ?>
		
			<?php get_template_part('partials/single/classic-title-meta'); ?>
		
		<?php endif; ?>

		<?php get_template_part('partials/single/featured'); ?>
		
		<?php if ($layout !== 'classic-above'): ?>
		
			<?php get_template_part('partials/single/classic-title-meta'); ?>
		
		<?php endif; ?>
		
	</header>

	<?php 
		$args = [];

		// Disable spacious in classic listings.
		if (!is_single()) {
			$args['spacious_style'] = false;
		}

		// Get post body content
		Bunyad::core()->partial('partials/single/post-content', $args); 
	?>
		
</article>

<?php Bunyad::core()->partial('partials/single/post-footer'); ?>

