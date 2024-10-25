<?php
/**
 * Legacy / compat for older SmartMag.
 * 
 * Note: This is always loaded as some features have to be shimmed regardless of 
 * presence of legacy_mode flag in options.
 */
class Bunyad_Theme_Legacy
{
	public function __construct()
	{
		add_action('bunyad_footer_before', function() {
			do_action('bunyad_pre_footer');
		});

		// Using bunyad_register_assets to enqueue at right place and avoid Elementor issues.
		add_action('bunyad_register_assets', [$this, 'register_assets']);

		// Add slider options to options array.
		add_action('bunyad_theme_options', [$this, 'add_slider_options'], 10, 2);

		// Legacy Class Alias.
		class_alias('\Bunyad\Blocks\Helpers', 'Bunyad_Theme_Blocks_Helpers', false);

		// Legacy: Old page templates compatiblity.
		add_filter('page_template_hierarchy', function($templates) {
			if (!is_array($templates) || !$templates) {
				return $templates;
			}
			
			$legacy = [
				'page-blocks.php',
				'authors.php',
				'sitemap.php'
			];

			if (in_array($templates[0], $legacy)) {
				$templates[0] = 'page-templates/' . str_replace('page-', '', $templates[0]);
			}

			return $templates;
		});

		// Set revslider as packaged.
		if (function_exists('set_revslider_as_theme')) {
			set_revslider_as_theme();
		}
	}

	public function register_assets()
	{
		if (is_admin()) {
			return;
		}

		$version = Bunyad::options()->get_config('theme_version');

		// Legacy CSS, if needed.
		// if (Bunyad::options()->legacy_mode) {
		// 	wp_enqueue_style(
		// 		'smartmag-legacy', 
		// 		get_theme_file_uri('css/' . (is_rtl() ? 'rtl/' : '') . 'legacy.css'), 
		// 		['smartmag-core'],
		// 		$version
		// 	);
		// }

		// Styling for legacy shortcodes.
		if (class_exists('Bunyad_ShortCodes')) {
			wp_enqueue_style(
				'smartmag-legacy-sc', 
				get_theme_file_uri('css/' . (is_rtl() ? 'rtl/' : '') . 'legacy-shortcodes.css'), 
				['smartmag-core'],
				$version
			);

			wp_enqueue_script(
				'smartmag-legacy-sc',
				get_theme_file_uri('js/legacy-shortcodes.js'),
				['smartmag-theme'],
				$version
			);
		}
	}

	/**
	 * Add classic slider options.
	 * Note: legacy_mode can't be tested as it's not initialized yet.
	 */
	public function add_slider_options($options, $type = 'tree')
	{
		$fields = [
					
			[
				'name'    => 'classic_slider_animation',
				'label'   => esc_html__('Animation Type', 'bunyad-admin'),
				'value'   => 'fade',
				'desc'    => 'Set the type of animation to use for the slider. Does not apply to default slider.',
				'type'    => 'select',
				'options' => [
					'fade' => esc_html__('Fade Animation', 'bunyad-admin'), 
					'slide' => esc_html__('Slide Animation', 'bunyad-admin')
				],
			],
			
			[
				'name'    => 'classic_slider_slide_delay',
				'label'   => esc_html__('Slide Delay/Speed', 'bunyad-admin'),
				'value'   => 5000,
				'desc'    => 'Set the time a slide will be displayed for (in ms) before animating to the next one.',
				'type'    => 'text',
			],
			
			[
				'name'    => 'classic_slider_animation_speed',
				'label'   => esc_html__('Animation Speed', 'bunyad-admin'),
				'value'   => 600,
				'desc'    => 'Set the speed of animations in miliseconds. A valid number is required.',
				'type'    => 'text',
			],

			[
				'name'    => 'classic_slider_right_cat',
				'label'   => esc_html__('Right Side: Limit to Category', 'bunyad-admin'),
				'value'   => '',
				'desc'    => 'Enter a category ID if you want to show posts on the right side of the slider from a specific category. When not using a category, posts marked as "Featured Slider Post?" will be used instead.',
				'type'    => 'text',
			],
			
			[
				'name'    => 'classic_slider_right_tag',
				'label'   => esc_html__('Right Side: Limit to Tag', 'bunyad-admin'),
				'value'   => '',
				'desc'    => 'Enter tag slug if you want to show posts on the right side of the slider from a specific tag.',
				'type'    => 'text',
			],
		];

		if ($type !== 'short') {
			$fields = array_merge($fields, [
				[
					'name'  => 'css_classic_slider_bg_color',
					'value' => '',
					'label' => 'Background Color',
					'desc'  => '',
					'type'  => 'color',
					'css'   => [
						'.main-featured.has-classic-slider' => ['props' => ['background' =>  '%s']]
					],
				],
				
				[
					'name'    => 'css_classic_slider_bg_image',
					'value'   => '',
					'label'   => 'Featured Slider Background',
					'desc'    => '',
					'type'    => 'upload',
					'options' => [
						'type' => 'image'
					],
					'bg_type' => ['value' => 'cover-nonfixed'],
					'css'     => [
						'.main-featured.has-classic-slider' => ['props' => ['background-image' =>  'url(%s)']]
					],
				]
			]);
		}

		$section = [
			'id'     => 'legacy-classic-slider',
			'title'  => 'Slider: Classic/Legacy',
			'desc'   => '',
			'fields' => $fields
		];

		// Options tree, insert at right place.
		if ($type === 'tree') {
			$options['posts-listings']['sections'][] = $section;
		}
		else {
			$options[] = $section;
		}

		return $options;
	}
}

// init and make available in Bunyad::get('legacy')
Bunyad::register('legacy', [
	'class' => 'Bunyad_Theme_Legacy',
	'init'  => true
]);