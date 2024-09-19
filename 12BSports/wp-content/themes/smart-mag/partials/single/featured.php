<?php 
/**
 * Partial: Featured part of single posts
 */

$props = isset($props) ? $props : [];
$props = array_replace([
	'context'        => is_single() ? 'single' : 'large',
	'caption_class'  => 'wp-caption-text',
	'relative_width' => Bunyad::blocks()->get_relative_width(),
	'featured_ratio' => null,
], $props);

/*
 * Don't output if featured image is disabled and it is a single post.
 * Featured images are output in loops/non-single regardless of being disabled.
 */
if (Bunyad::posts()->meta('featured_disable') && is_single()) {
	return;
}

/**
 * Setup featured image media data in advance.
 */
if (has_post_thumbnail()) {

	$image_options = [
		'bg_image' => false,
		'ratio'    => $props['featured_ratio'],
		'no_lazy'  => is_single(),
	];

	// Use same for classic listings too.
	$featured_crop = Bunyad::options()->single_featured_crop;
	$image_options['ratio'] = Bunyad::options()->get('single_featured_ratio_custom', 'single_featured_ratio');

	if (!$image_options['ratio']) {
		$image_options['ratio'] = $props['featured_ratio'];
	}
}

$classes = [
	'featured'
];

if (Bunyad::options()->single_featured_shadow && get_post_format() !== 'audio') {
	$classes[] = 'has-media-shadows';
}

?>
	<?php do_action('bunyad_post_featured_before', $props); ?>

	<div <?php Bunyad::markup()->attribs('featured', ['class' => $classes]); ?>>
		<?php if (get_post_format() == 'gallery' && !Bunyad::amp()->active()): ?>
		
			<?php get_template_part('partials/gallery-format'); ?>
			
		<?php elseif (Bunyad::posts()->meta('featured_video')): // featured video available? ?>
		
			<div class="featured-vid">
				<?php echo apply_filters('bunyad_featured_video', Bunyad::posts()->meta('featured_video')); ?>
			</div>
			
		<?php elseif (has_post_thumbnail()): ?>
		
			<?php 
				/**
				 * Normal featured image
				 */
		
				$thumb   = get_post(get_post_thumbnail_id());
				$caption = $thumb ? $thumb->post_excerpt : '';
				$url     = get_permalink();
				
				// on single page? link to image
				if (is_single()):
					$url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); 
					$url = $url[0];
				endif;

				// Whether to use an uncropped (original aspect ratio) image.
				if (!$featured_crop) {
					$image = $props['relative_width'] > 67 ? 'bunyad-full' : 'bunyad-main-uc';
				}
				else {
					$image = $props['relative_width'] > 67 ? 'bunyad-main-full' : 'bunyad-main';
				}

				Bunyad::media()->the_image(
					$image,
					$image_options + [
						'link' => !Bunyad::options()->single_featured_link ? false : $url,
					]
				);
			?>
		
			<?php if (!empty($caption)): ?>
					
				<div class="<?php echo esc_attr($props['caption_class']); ?>">
					<?php echo $caption; // phpcs:ignore WordPress.Security.EscapeOutput -- Sanitized caption from WordPress post_excerpt ?>
				</div>
					
			<?php endif;?>
			
		<?php endif; // end normal featured image ?>
	</div>

	<?php do_action('bunyad_post_featured_after', $props); ?>