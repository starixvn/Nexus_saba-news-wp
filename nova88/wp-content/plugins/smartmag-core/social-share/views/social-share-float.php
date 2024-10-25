<?php
/**
 * Plugin Template: for Alternate Social share buttons on single page
 * 
 * See: inc/social.php for filters and caller.
 * 
 * How to Modify:
 *  - Use the action hooks below. 
 *  - OR, place it in your-child-theme/partials/social-share/social-share-float.php
 */

if (!is_single()) {
	return;
}

$props = array_replace([
	// 'active' => ['facebook', 'twitter', 'pinterest', 'email'],
	'active' => Bunyad::options()->share_float_services,
	'style'  => Bunyad::options()->share_float_style ? Bunyad::options()->share_float_style : 'a',
	'text'   => Bunyad::options()->get_or(
		'share_float_text',
		esc_html__('Share', 'bunyad')
	),
	'label' => Bunyad::options()->share_float_label,
], $props);

// Post and media URL
$services = Bunyad::get('smartmag_social')->share_services();

if (strstr($services['pinterest']['icon'], 'tsi')) {
	$services['pinterest']['icon'] = 'tsi tsi-pinterest-p';
}

$classes = [
	'post-share-float',
	'share-float-' . $props['style'],
	'is-hidden',
	'spc-social-colors'
];

if (in_array($props['style'], ['c', 'd'])) {
	$classes[] = 'spc-social-bg';
}
else {
	$classes[] = 'spc-social-colored';
}

?>
<div class="<?php echo esc_attr(join(' ', $classes)); ?>">
	<div class="inner">
		<?php if ($props['label'] && $props['text']): ?>
			<span class="share-text"><?php echo esc_html($props['text']); ?></span>
		<?php endif; ?>

		<div class="services">
			<?php do_action('bunyad_social_share_float_services_start'); ?>
		
		<?php 
			foreach ((array) $props['active'] as $key): 

				if (!isset($services[$key])) {
					continue;
				}

				$service = $services[$key];
		?>
		
			<a href="<?php echo $service['url']; ?>" class="cf service s-<?php echo esc_attr($key); ?>" target="_blank" title="<?php echo esc_attr($service['label'])?>" rel="nofollow noopener">
				<i class="<?php echo esc_attr($service['icon']); ?>"></i>
				<span class="label"><?php echo esc_html($service['label']); ?></span>

				<?php 
					if ($key === 'link') {
						printf(
							'<span data-message="%s"></span>',
							esc_attr__('Link copied successfully!', 'bunyad')
						);
					}
				?>
			</a>
				
		<?php endforeach; ?>

			<?php do_action('bunyad_social_share_float_services_end'); ?>
		
		</div>
	</div>		
</div>
