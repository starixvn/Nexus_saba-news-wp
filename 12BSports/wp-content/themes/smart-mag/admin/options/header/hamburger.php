<?php
/**
 * Element: Hamburger Icon
 */
$fields   = [];
$template = [
	[
		'name'  => '_g_{key}_hamburger',
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

	// Only for main header, skipped for header_mob so no prefix.
	[
		'name'    => 'header_hamburger_style',
		'label'   => esc_html__('Hamburger Style', 'bunyad-admin'),
		'desc'    => '',
		'value'   => 'a',
		'type'    => 'select',
		'options' => [
			'a' => esc_html__('Default: Varied Lines', 'bunyad-admin'),
			'b' => esc_html__('Style 2: Simple', 'bunyad-admin'),
		]
	],
	[
		'name'    => 'css_{key}_hamburger_color',
		'label'   => esc_html__('Icon Color', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix}' => ['props' => ['--c-hamburger' => '%s']]
		]
	],

	[
		'name'    => 'css_{key}_hamburger_color_sd',
		'label'   => esc_html__('Dark: Icon Color', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.s-dark .smart-head-{suffix} .offcanvas-toggle,
			.smart-head-{suffix} .s-dark .offcanvas-toggle' => [
				'props' => ['--c-hamburger' => '%s']
			]
		]
	],

	[
		'name'    => 'css_{key}_hamburger_hov_color',
		'label'   => esc_html__('Icon Hover', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix} .offcanvas-toggle:hover' => [
				'props' => ['--c-hamburger' => '%s']
			]
		]
	],

	[
		'name'    => 'css_{key}_hamburger_hov_color_sd',
		'label'   => esc_html__('Dark: Icon Hover', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.s-dark .smart-head-{suffix} .offcanvas-toggle:hover,
			.smart-head-{suffix} .s-dark .offcanvas-toggle:hover' => [
				'props' => ['--c-hamburger' => '%s']
			]
		]
	],

	[
		'name'    => 'css_{key}_hamburger_scale',
		'label'   => esc_html__('Scale Size', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'slider',
		'input_attrs' => ['min' => .1, 'max' => 2.5, 'step' => .05],
		'css'     => [
			'.smart-head-{suffix} .offcanvas-toggle' => [
				'props' => ['transform' => 'scale(%s)']
			]
		]
	],

	[
		'name'    => 'css_{key}_hamburger_line',
		'label'   => esc_html__('Line Weight', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'number',
		'input_attrs' => ['min' => 1, 'max' => 5],
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix} .hamburger-icon' => [
				'props' => ['--line-weight' => '%spx']
			]
		]
	],

	[
		'name'    => 'css_{key}_hamburger_width',
		'label'   => esc_html__('Width', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'number',
		'input_attrs' => ['min' => 1, 'max' => 100],
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix} .hamburger-icon' => [
				'props' => ['width' => '%spx']
			]
		]
	],

	[
		'name'    => 'css_{key}_hamburger_height',
		'label'   => esc_html__('Height', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'number',
		'input_attrs' => ['min' => 1, 'max' => 75],
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix} .hamburger-icon' => [
				'props' => ['--height' => '%spx']
			]
		]
	],

	[
		'name'    => 'css_{key}_hamburger_mr',
		'label'   => esc_html__('Space Right', 'bunyad-admin'),
		'desc'    => 'Margin on the right side of the item.',
		'value'   => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix} .offcanvas-toggle' => [
				'props' => ['--item-mr' => '%spx']
			]
		]
	],
];

\Bunyad\Util\repeat_options(
	$template,
	[
		'header'     => [
			'replacements' => ['{suffix}' => 'main'],
			'group'        => '_g_{key}_hamburger'
		],
		'header_mob' => [
			'replacements' => ['{suffix}' => 'mobile'],
			'skip'         => ['header_hamburger_style'],
			'group'        => '_g_{key}_hamburger'
		],
	],
	$fields,
	[
		'replace_in' => ['css', 'group'],
	]
);

return $fields;