<?php
/**
 * Element: Search
 */
$fields   = [
	[
		'name'     => 'header_search_overlay_scheme',
		'label'    => esc_html__('Search Modal Scheme', 'bunyad-admin'),
		'desc'     => '',
		'value'    => 'dark',
		'type'     => 'select',
		'options'  => [
			'light' => esc_html__('Light', 'bunyad-admin'),
			'dark'  => esc_html__('Dark', 'bunyad-admin'),
		],
		'style'    => 'inline-sm',
		'context'  => [['key' => 'header_search_type', 'value' => 'icon']],
	],
	[
		'name'     => 'css_header_search_overlay_bg',
		'label'    => esc_html__('Modal BG Color', 'bunyad-admin'),
		'desc'     => '',
		'value'    => '',
		'type'     => 'color',
		'css'      => [
			'.search-modal.mfp-bg' => ['props' => ['background-color' => '%s']],
		],
		'style'    => 'inline-sm',
		'context'  => [['key' => 'header_search_type', 'value' => 'icon']],
	],
	[
		'name'     => 'css_header_search_overlay_bg_sd',
		'label'    => esc_html__('Dark: Modal BG Color', 'bunyad-admin'),
		'desc'     => '',
		'value'    => '',
		'type'     => 'color',
		'css'      => [
			'.s-dark .search-modal.mfp-bg, 
			.s-dark.search-modal.mfp-bg' => ['props' => ['background-color' => '%s']],
		],
		'style'    => 'inline-sm',
		'context'  => [['key' => 'header_search_type', 'value' => 'icon']],
	],
	[
		'name'  => 'header_search_live',
		'label' => esc_html__('Enable Live Search', 'bunyad-admin'),
		'desc'  => esc_html__('Live search shows results using AJAX as you type, in the top bar search.', 'bunyad-admin'),
		'value' => 1,
		'type'  => 'toggle',
		'style' => 'inline-sm',
	],

	[
		'name'  => 'header_search_live_posts',
		'label'  => esc_html__('Live Search Results', 'bunyad-admin'),
		'desc'   => esc_html__('Set the number of results to show when using the live search.', 'bunyad-admin'),
		'value' => 4,
		'type'  => 'number',
		'style' => 'inline-sm',
		'context' => [['key' => 'header_search_live', 'value' => 1]]
	],
];

$template = [
	[
		'name'  => '_g_{key}_search',
		'type'  => 'group',
		'style' => 'collapsible',
		'template' => [
			'header' => [
				'label'  => esc_html__('Main Header', 'bunyad-admin'),
				'collapsed' => false,
				'group'     => '',
			],
			'header_mob' => [
				'label' => esc_html__('Mobile Header', 'bunyad-admin'),
				'group'     => '',
			],
		]
	],
	[
		'name'     => 'header_search_type',
		'label'    => esc_html__('Search Type', 'bunyad-admin'),
		'desc'     => '',
		'value'    => 'icon',
		'type'     => 'select',
		'options'  => [
			'icon' => esc_html__('Icon', 'bunyad-admin'),
			'form'  => esc_html__('Search Form', 'bunyad-admin'),
		],
	],

	[
		'name'    => 'css_{key}_search_color',
		'label'   => esc_html__('Icon Color', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			// '.s-light .smart-head-row:not(.s-dark)' => ['props' => ['--c-search-icon' => '%s']]
			'.smart-head-{suffix}' => ['props' => ['--c-search-icon' => '%s']]
		],
		'context' => [['key' => 'header_search_type', 'value' => 'icon']],
	],
	[
		'name'    => 'css_{key}_search_color_sd',
		'label'   => esc_html__('Dark: Icon Color', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.s-dark .smart-head-{suffix} .search-icon,
			.smart-head-{suffix} .s-dark .search-icon' => ['props' => ['--c-search-icon' => '%s']]
		],
		'context' => [['key' => 'header_search_type', 'value' => 'icon']],
	],

	[
		'name'    => 'css_{key}_search_hov_color',
		'label'   => esc_html__('Icon Hover', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix} .search-icon:hover' => ['props' => ['color' => '%s']]
		],
		'context' => [['key' => 'header_search_type', 'value' => 'icon']],
	],
	[
		'name'    => 'css_{key}_search_hov_color_sd',
		'label'   => esc_html__('Dark: Icon Hover', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.s-dark .smart-head-{suffix} .search-icon:hover,
			.smart-head-{suffix} .s-dark .search-icon:hover' => ['props' => ['color' => '%s']]
		],
		'context' => [['key' => 'header_search_type', 'value' => 'icon']],
	],

	[
		'name'    => 'css_{key}_search_size',
		'label'   => esc_html__('Icon Size', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'number',
		'input_attrs' => ['min' => 8],
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix}' => [
				'props' => ['--search-icon-size' => '%spx']
			]
		],
		'context' => [['key' => 'header_search_type', 'value' => 'icon']],
	],

	[
		'name'    => 'css_{key}_search_size_top',
		'label'   => esc_html__('Icon Size (Top Row)', 'bunyad-admin'),
		'desc'    => 'When used in top bar row.',
		'value'   => '',
		'type'    => 'number',
		'input_attrs' => ['min' => 8],
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix} .smart-head-top' => [
				'props' => ['--search-icon-size' => '%spx']
			]
		],
		'context' => [['key' => 'header_search_type', 'value' => 'icon']],
	],

	[
		'name'    => 'css_{key}_search_mr',
		'label'   => esc_html__('Space Right', 'bunyad-admin'),
		'desc'    => 'Margin on the right side of the item.',
		'value'   => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix} .search-icon' => [
				'props' => ['--item-mr' => '%spx']
			]
		],
		'context' => [['key' => 'header_search_type', 'value' => 'icon']],
	],
];

\Bunyad\Util\repeat_options(
	$template,
	[
		'header'     => [
			'replacements' => ['{suffix}' => 'main'],
			'group'        => '_g_{key}_search'
		],
		'header_mob' => [
			'replacements' => ['{suffix}' => 'mobile'],
			'skip'         => ['header_search_type'],
			'group'        => '_g_{key}_search',
			'context'      => null,
		],
	],
	$fields,
	[
		'replace_in' => ['css', 'group'],
	]
);

return $fields;