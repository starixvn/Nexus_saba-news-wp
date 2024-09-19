<?php
/**
 * Modern Single Post Header.
 */

$props = array_replace(
	[
		'layout'              => 'modern',
		'classes'             => [],
		'header_below'        => false,
		'centered'            => false,
		'cat_style'           => 'labels',
		'featured'            => false,
		'social_top_location' => '',
		'social_top_style'    => '',
		'social_top_devices'  => [],
		'text_labels'         => Bunyad::options()->post_meta_single_labels,
		'author_img'          => Bunyad::options()->post_meta_single_author_img,
	],
	$props
);

// Alias for featured.
if (isset($props['featured_in_head'])) {
	$props['featured'] = $props['featured_in_head'];
}

// Top social share.
$render_social = false;

if (
	(is_single() || Bunyad::options()->social_icons_classic) 
	&& Bunyad::options()->single_share_top
	&& class_exists('SmartMag_Core')
) {
	$render_social = $props['social_top_location'] ?: 'below';
	$social_args = [
		'active'  => Bunyad::options()->single_share_top_services,
		'style'   => $props['social_top_style'],
		'devices' => $props['social_top_devices'],
	];

	// Not the same style as global style. Disable global customizations.
	if (Bunyad::options()->single_share_top_style !== $social_args['style']) {
		$social_args['classes'] = ['is-not-global'];
	}
}

// Subtitle - Devs: Use the filter to replace with a 3rd party plugin if needed.
$sub_title = apply_filters('bunyad_single_subtitle', Bunyad::posts()->meta('sub_title'));
if ($sub_title) {
	$sub_title = sprintf('<div class="sub-title">%s</div>', wp_kses_post($sub_title));
}

$classes = array_merge($props['classes'], [
	'the-post-header',
	's-head-modern',
	's-head-' . $props['layout'],
	$props['centered'] ? 's-head-center' : '',
	$props['social_top_location'] === 'meta-right' ? 'has-share-meta-right' : '',
	$props['header_below'] ? 's-head-modern-below' : '',
]);

?>
<div <?php Bunyad::markup()->attribs('s-head-' . $props['layout'], [
	'class' => $classes,
]); ?>>
	<?php

		$meta_args = [
			'type'        => 'single',
			'is_single'   => true,
			'text_labels' => $props['text_labels'],
			'cat_style'   => $props['cat_style'],
			
			// Items below should be from global settings.
			'show_title'  => true,
			'after_title' => $sub_title,
			'add_class'   => 'post-meta-single',
			'align'       => 'left',
			'author_img'  => $props['author_img'],
			'avatar_size' => 32,
		];

		// Social on meta right location.
		if ($render_social === 'meta-right') {

			$meta_args['below_right_html'] = function() use ($social_args) {
				Bunyad::get('smartmag_social')->render('social-share-b', $social_args);
			};
		}

		$meta = Bunyad::blocks()->load('PostMeta', $meta_args);
		$meta->render();

		if ($render_social === 'below') {
			if (Bunyad::options()->single_follow_top) {
				$social_args += [
					'follow_active'   => Bunyad::options()->single_follow_top_services,
					'follow_label'    => Bunyad::options()->get_or(
						'single_follow_top_label',
						esc_html__('Follow Us', 'bunyad')
					)
				];
			}

			// See plugins/smartmag-core/social-share/views/social-share-b.php
			Bunyad::get('smartmag_social')->render('social-share-b', $social_args);
		}

	?>
	
	<?php if ($props['featured']): ?>
		<div class="single-featured">
			<?php 
				Bunyad::core()->partial('partials/single/featured', $props); 
			?>
		</div>
	<?php endif; ?>

</div>