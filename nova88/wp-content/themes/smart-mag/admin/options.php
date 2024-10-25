<?php
/**
 * Theme Settings - All the relevant options!
 * 
 * @uses Bunyad_Admin_Options::render_options()
 */

/**
 * Commonly used configs.
 */
$_common = Bunyad::core()->get_common_data('options');
$options = [];

// Get the options
$files = [
	// 'intro',
	// 'privacy-gdpr',
	'colors-fonts',
	'layout-main',
	'logos',
	'header',
	'footer',
	'layout-pages',
	'archives',
	'posts-global',
	'posts-listings',
	'posts-single',
	'newsletter',
	'misc',
	'custom-codes',
];

foreach ($files as $file) {
	require get_theme_file_path('admin/options/' . $file . '.php');
}

return [
	[
		'sections' => [
			[
				'id'     => 'select-skin',
				'title'  => esc_html__('Skins & Demos', 'bunyad-admin'),
				'desc'   => '',
				'fields' => [

					[
						'name'  => 'import_info',
						'label' => esc_html__('Import Theme Demos', 'bunyad-admin'),
						'type'  => 'content',
						'text'  => '',
					],

					[
						'name'  => '_g_skins',
						'label' => esc_html__('Advanced: Skins', 'bunyad-admin'),
						'type'  => 'group',
						'style' => 'collapsible',
					],
					[
						'name'    => 'predefined_style',
						'label'   => esc_html__('Premade Skin', 'bunyad-admin'),
						'value'   => '',
						'desc'    => 'Note: This does NOT import the demo. It adds a few CSS styling needed for some demos. To import a theme demo, see info above.',
						'type'    => 'select',
						'options' => [
							''       => esc_html__('Default', 'bunyad-admin'),
							'trendy' => esc_html__('Trendy', 'bunyad-admin'),
							'thezine' => esc_html__('TheZine', 'bunyad-admin'),
							'classic' => esc_html__('Legacy/Classic', 'bunyad-admin'),
						],
						'group' => '_g_skins'
					],

					[
						'name'  => 'installed_demo',
						'value' => '',
						'type'  => 'ignore',
					]
				], // fields

			], // section
		], // sections
	],

	// Core Layouts
	[
		'id'          => 'h-core-layouts',
		'add_heading' => esc_html__('Core Layouts', 'bunyad-admin'),
		'sections'    => [],
	],
	$options['colors-fonts'],
	$options['layout-main'],
	$options['layout-main'],
	$options['logos'],
	$options['header'],
	$options['footer'],

	[
		'id'          => 'h-posts',
		'add_heading' => esc_html__('Posts & Listings', 'bunyad-admin'),
		'sections'    => [],
	],
	$options['posts-global'],
	'posts-listings' => $options['posts-listings'],
	$options['posts-single'],

	// Other Layouts
	[
		'id'          => 'h-other-layouts',
		'add_heading' => esc_html__('Other Layouts', 'bunyad-admin'),
		'sections'    => [],
	],

	$options['layout-pages'],
	$options['archives'],

	// Misc Features
	[
		'id'          => 'h-misc-features',
		'add_heading' => esc_html__('Misc. Features', 'bunyad-admin'),
		'sections'    => [],
		'start_priority' => 30
	],

	$options['misc-social'],
	$options['newsletter'],
	$options['misc-performance'],
	$options['misc-woocommerce'],
	$options['misc-other'],
	$options['custom-codes'],

	[
		// 'id' => 'reset-customizer',
		// 'add_heading' => esc_html__('WP Core & Others', 'bunyad-admin'),
		// 'add_heading_after' => true,
		'start_priority' => 41,
		'sections' => [
			[
				'id'       => 'reset-customizer',
				'title'    => esc_html__('Reset Settings', 'bunyad-admin'),
				'classes'  => 'spacing-below',
				'fields'   => [
					[
						'name'        => 'reset_customizer',
						'value'       => esc_html__('Reset All Settings', 'bunyad-admin'),
						'desc'        => esc_html__('Clicking the Reset button will revert all settings in the customizer except for menus, widgets and site identity.', 'bunyad-admin'),
						'type'        => 'button',
						'input_attrs' => [
							'class' => 'button reset-customizer',
						],
					],
				],
			],
		],
	],
];