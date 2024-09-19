<?php 
/**
 * Partial Template for Single Post "Modern Layout" - called from single.php
 */
$props = array_replace(
	[
		'layout'              => 'modern-a',
		'header_outer'        => false,
		'header_below'        => false,
		'centered'            => false,
		'cat_style'           => Bunyad::options()->post_meta_single_cat_labels,
		'has_large_bot'       => false,
		'social_top_style'    => Bunyad::options()->single_share_top_style,
		'social_top_location' => Bunyad::options()->single_share_top_location,
		'social_top_devices'  => Bunyad::options()->single_share_top_devices,
		'featured_in_head'    => false,
		
		// Post Header classes.
		'classes'             => [],

		// Post wrapper classes
		'post_classes'        => [],
	],
	isset($props) ? $props : []
);

$row_classes = array_filter([
	'ts-row',
	$props['has_large_bot'] ? 'has-s-large-bot' : '',
	$props['header_below']  ? 'has-head-below' : '',
]);

?>

<?php if ($props['header_outer']): ?>
	<?php Bunyad::core()->partial('partials/single/modern-header', $props); ?>
<?php endif; ?>

<div class="<?php echo esc_attr(join(' ', $row_classes)); ?>">
	<div class="col-8 main-content s-post-contain">

		<?php if (!$props['header_outer']): ?>
			<?php Bunyad::core()->partial('partials/single/modern-header', $props); ?>
		<?php endif; ?>

		<?php if (!$props['featured_in_head']): ?>
			<div class="single-featured"><?php 
				// Note: No spacing around div and php tags to allow for :empty() in css.
				Bunyad::core()->partial('partials/single/featured', $props); 
			?></div>
		<?php endif; ?>

		<div <?php Bunyad::markup()->attribs('the-post-wrap', [
			'class' => $props['post_classes']
		]); ?>>

			<article id="post-<?php the_ID(); ?>" class="<?php echo esc_attr(join(' ', get_post_class())); ?>">
				<?php 
					// Get post body content.
					get_template_part('partials/single/post-content'); 
				?>
			</article>

			<?php Bunyad::core()->partial('partials/single/post-footer'); ?>
			
			<div class="comments">
				<?php comments_template('', true); ?>
			</div>

		</div>
	</div>
	
	<?php Bunyad::core()->theme_sidebar(); ?>
</div>