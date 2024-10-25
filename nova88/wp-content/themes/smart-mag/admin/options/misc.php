<?php
/**
 * Misc features options
 */

$social_profiles = [];
foreach ($_common['social_services_ext'] as $key => $label) {
	$social_profiles[] = [
		'name'   => 'social_profiles['. $key .']',
		'value'  => $key === 'rss' ? get_bloginfo('rss2_url') : '',
		'label'  => $label,
		'desc'   => '',
		'type'   => 'text'
	];
}

$social = [[
	'id'     => 'misc-social',
	'title'  => esc_html__('Social Media Links', 'bunyad-admin'),
	'desc'   => esc_html__('Enter full URLs to your social media profiles. They will be used anywhere you use social profiles such as header, sidebar etc.', 'bunyad-admin'),
	'fields' => $social_profiles,
]];

// Other settings
$other = [[
	'id'     => 'other-settings',
	'title'  => esc_html__('Miscellaneous Settings', 'bunyad-admin'),
	'fields' => [
		[
			'name'  => 'theme_updates',
			'value' => 1,
			'label' => esc_html__('Enable One-Click Updates', 'bunyad-admin'),
			'desc'  => esc_html__('Enable to be notified of new updates in your WordPress admin panel with one-click update. REQUIRED: Activated theme license.', 'bunyad-admin'),
			'type'  => 'checkbox'
		],

		[
			'name'   => 'search_posts_only',
			'value'  => 1,
			'label'  => esc_html__('Limit Search To Posts', 'bunyad-admin'),
			'desc'   => esc_html__('Enabling this feature will exclude pages from WordPress search.', 'bunyad-admin'),
			'type'   => 'checkbox'
		],

		[
			'name'   => 'image_effects',
			'value'  => 0,
			'label'  => esc_html__('Images Fade In (On Scroll)', 'bunyad-admin'),
			'desc'   => esc_html__('Creates a fade-in effect for images as the user scrolls down.', 'bunyad-admin'),
			'type'   => 'checkbox'
		],

		[
			'name'   => 'yoast_primary_cat',
			'value'  => 0,
			'label'  => esc_html__('Yoast SEO Primary Category', 'bunyad-admin'),
			'desc'   => esc_html__('When Yoast SEO plugin is enabled, use primary categories from Yoast SEO unless overridden by theme post options.', 'bunyad-admin'),
			'type'   => 'checkbox'
		],
		
		[
			'name'    => 'enable_lightbox',
			'label'   => esc_html__('Enable Lightbox for Images', 'bunyad-admin'),
			'value'   => 1,
			'desc'    => esc_html__('Show images such as featured images, WordPress galleries etc. in a lightbox on click.', 'bunyad-admin'),
			'type'    => 'checkbox',
		],

		[
			'name'    => 'enable_lightbox_mobile',
			'label'   => esc_html__('Lightbox on Small Screens', 'bunyad-admin'),
			'value'   => 1,
			'desc'    => '',
			'type'    => 'checkbox',
			'context' => [['key' => 'enable_lightbox', 'value' => 1]],
		],

		[
			'name'    => 'amp_enabled',
			'label'   => esc_html__('AMP: Enable Theme Styles', 'bunyad-admin'),
			'value'   => 1,
			'desc'    => esc_html__('Enable our special changes for the AMP plugin. Note: Only works when the "Bunyad AMP" plugin is active.', 'bunyad-admin'),
			'type'    => 'checkbox',
		],

		[
			'name'    => 'guten_styles',
			'label'   => esc_html__('Gutenberg: Theme Styles in Editor', 'bunyad-admin'),
			'value'   => 1,
			'desc'    => esc_html__('By default Gutenberg has its own styling. Ticking this will enable our custom styles so that the backend is similar looking to frontend.', 'bunyad-admin'),
			'type'    => 'checkbox',
		],

		[
			'name'    => 'widgets_block_editor',
			'label'   => esc_html__('Enable Widgets Block Editor', 'bunyad-admin'),
			'value'   => 0,
			'desc'    => esc_html__('NOT RECOMMENDED: This will convert your widgets to legacy and many widgets and plugins may not work with new WordPress widgets block editor.', 'bunyad-admin'),
			'type'    => 'checkbox',
		],

		[
			'name'  => 'legacy_mode',
			'label' => esc_html__('Enable Legacy Mode', 'bunyad-admin'),
			'value' => 0,
			'desc'  => 'Enables old SmartMag features such as shortcodes plugin for in Classic Editor, some legacy layouts etc.',
			'type'  => 'checkbox',
		],

		[
			'name'  => 'fontawesome4',
			'label' => esc_html__('Legacy: Load FontAwesome 4', 'bunyad-admin'),
			'value' => 0,
			'desc'  => esc_html__('Legacy: If you used custom FA4 icons before SmartMag v5.0, enable this.', 'bunyad-admin'),
			'type'  => 'checkbox',
			// legacy_mode enables it auto.
			// 'context' => [['key' => 'legacy_mode', 'value' => 0]]
		],

	] // fields
]];

// Performance
$performance = [[
	'id'     => 'misc-performance',
	'title'  => esc_html__('Performance', 'bunyad-admin'),
	'fields' => [
		[
			'name'  => '_n_performance',
			'type'  => 'message',
			'label' => 'Read Performance Guide!',
			'text'  => 'There is a lot more to performance. <a href="https://theme-sphere.com/docs/smartmag/#performance" target="_blank">Read the guide</a>.',
			'style' => 'message-info',
		],
		[
			'name'  => 'lazyload_enabled',
			'label' => esc_html__('LazyLoad Images', 'bunyad-admin'),
			'value' => 1,
			'desc'  => '',
			'type'  => 'checkbox',
		],	
		[
			'name'    => 'lazyload_type',
			'label'   => esc_html__('Lazy Loader Type', 'bunyad-admin'),
			'value'   => 'normal',
			'desc'    => '',
			'type'    => 'radio',
			'options' => [
				'normal' => esc_html__('Normal - Load Images on scroll', 'bunyad-admin'),
				'smart'  => esc_html__('Smart - Preload Images on Desktops', 'bunyad-admin')
			]
		],
		[
			'name'  => 'lazyload_aggressive',
			'label' => esc_html__('Aggressive Lazy Load', 'bunyad-admin'),
			'value' => 0,
			'desc'  => esc_html__('By default, only posts and single images are lazyloaded. Aggressive enables lazyloading on all sidebar widgets and footer as well.', 'bunyad-admin'),
			'type'  => 'checkbox',
		],
		[
			'name'  => 'lazyload_skip_number',
			'label' => esc_html__('Skip Lazyload', 'bunyad-admin'),
			'value' => 1,
			'desc'  => 'Skip lazyload for first N images on home and archives.',
			'style' => 'inline-sm',
			'classes' => 'sep-bottom',
			'type'  => 'number',
		],

		[
			'name'  => 'perf_preload_featured',
			'label' => esc_html__('Preload Featured Image', 'bunyad-admin'),
			'value' => 1,
			'desc'  => esc_html__('Preload the main image on single pages and the first featured grid image on home.', 'bunyad-admin'),
			'type'  => 'checkbox',
		],
		[
			'name'  => 'perf_first_img_class',
			'label' => esc_html__('Add First Image Class', 'bunyad-admin'),
			'value' => 0,
			'desc'  => esc_html__('Add class .ts-first-image to the first featured image. You may use this class in lazyload plugin excludes.', 'bunyad-admin'),
			'type'  => 'checkbox',
			'context' => [['key' => 'perf_preload_featured', 'value' => 1]]
		],

		[
			'name'  => 'perf_lazy_oembed',
			'label' => esc_html__('Lazyload Embeds', 'bunyad-admin'),
			'value' => 1,
			'desc'  => esc_html__('Currently for embedded tweets as they load very large amount of JS.', 'bunyad-admin'),
			'type'  => 'checkbox',
		],

		[
			'name'  => 'perf_disable_bg_images',
			'label' => esc_html__('Disable BG Image Method', 'bunyad-admin'),
			'value' => 0,
			'desc'  => esc_html__('Advanced: Plugins like WebP Express have a setting to inject <picture> tag when using a CDN. This will requires our bg image method to be disabled.', 'bunyad-admin'),
			'type'  => 'checkbox',
		],

		[
			'name'    => '_n_elementor_assets',
			'type'    => 'message',
			'label'   => '',
			'text'    => '
			<p>The Elementor assets are <strong>not needed</strong> for any of SmartMag demo homepages. You can safely disable these unless you use the specific feature in Elementor.</p>
			<p>Notes:</p>
			<ul>
				<li>Frontend JS will be needed for some of non-SmartMag Elementor blocks that use video APIs, lightbox, or custom sliders. Some like Swiper may also need the frontend modules.</li>
				<li>Share Links is only necessary for the non-recommended Elementor social block.</li>
			</ul>			
			',
			'style'   => 'message-info',
		],
		[
			'name'  => 'perf_disable_elementor_assets',
			'label' => esc_html__('Disable Elementor Assets', 'bunyad-admin'),
			'value' => [],
			'desc'  => '',
			'type'  => 'checkboxes',
			'options' => [
				'animations'     => 'Animations',
				'icons'          => 'Icons',
				'share-links-js' => 'Share Links',
				'dialog-js'      => 'Dialog/Lightbox',
				'frontend-js'    => 'Frontend JS',
				'frontend-modules-js' => 'Frontend Modules',
			]
		],
	]
]];

// WooCommerce
$woocommerce = [[
	'id'     => 'misc-woocommerce',
	'title'  => esc_html__('WooCommerce/Shop', 'bunyad-admin'),
	'desc'   => esc_html__('Settings here only apply if you have WooCommerce installed.', 'bunyad-admin'),
	'fields' => [

		[
			'name'    => 'woocommerce_per_page',
			'label'   => esc_html__('Shop Products / Page', 'bunyad-admin'),
			'value'   => 9,
			'desc'    => '',
			'type'    => 'number'
		],

		[
			'name'    => 'woocommerce_image_zoom',
			'label'   => esc_html__('Product Page - Image Zoom', 'bunyad-admin'),
			'value'   => 1,
			'desc'    => '',
			'type'    => 'checkbox'
		],

		[
			'name'    => 'woocommerce_page_sidebar',
			'label'   => esc_html__('Pages Sidebar', 'bunyad-admin'),
			'desc'    => sprintf(
				esc_html__('Applies to cart, checkout, account page and single product, unless overridden. For shop page/archives, %1$sedit your shop page%2$s.', 'bunyad-admin'),
				'<a href="https://theme-sphere.com/docs/smartmag/#woocommerce-sidebar" target="_blank">', '</a>'
			),
			'value'   => 'none',
			'type'    => 'select',
			'options' => $_common['sidebar_options'],
		],
	]
]];

$options['misc-social'] = [
	'sections' => $social
];

$options['misc-other'] = [
	'sections' => $other
];

$options['misc-performance'] = [
	'sections' => $performance
];

// Woocommerce only if exists.
$options['misc-woocommerce'] = [];

// Always make it available in WP-CLI for options-short generation.
// @todo move to integration option hooks instead.
if (function_exists('is_woocommerce') || defined('WP_CLI')) {
	$options['misc-woocommerce'] = ['sections' => $woocommerce];
}

return $options;