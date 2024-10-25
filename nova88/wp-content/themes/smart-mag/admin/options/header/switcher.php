<?php
/**
 * Element: Dark Switcher
 */
$fields   = [];
$template = [
	[
		'name'  => '_g_{key}_switcher',
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
		'name'    => 'css_{key}_switcher_color',
		'label'   => esc_html__('Icon Color', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix} .scheme-switcher a' => [
				'props' => ['color' => '%s']
			]
		]
	],

	[
		'name'    => 'css_{key}_switcher_color_sd',
		'label'   => esc_html__('Dark: Icon Color', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.s-dark .smart-head-{suffix} .scheme-switcher a,
			.smart-head-{suffix} .s-dark .scheme-switcher a' => [
				'props' => ['color' => '%s']
			]
		]
	],

	[
		'name'    => 'css_{key}_switcher_hov_color',
		'label'   => esc_html__('Icon Hover', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix} .scheme-switcher a:hover' => [
				'props' => ['color' => '%s']
			]
		]
	],

	[
		'name'    => 'css_{key}_switcher_hov_color_sd',
		'label'   => esc_html__('Dark: Icon Hover', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.s-dark .smart-head-{suffix} .scheme-switcher a:hover,
			.smart-head-{suffix} .s-dark .scheme-switcher a:hover' => [
				'props' => ['color' => '%s']
			]
		]
	],

	[
		'name'    => 'css_{key}_switcher_size',
		'label'   => esc_html__('Icon Size', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'number',
		'input_attrs' => ['min' => 8],
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix} .scheme-switcher' => [
				'props' => ['--icons-size' => '%spx']
			]
		]
	],

	[
		'name'    => 'css_{key}_switcher_mr',
		'label'   => esc_html__('Space Right', 'bunyad-admin'),
		'desc'    => 'Margin on the right side of the item.',
		'value'   => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix} .scheme-switcher' => [
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
			'group'        => '_g_{key}_switcher'
		],
		'header_mob' => [
			'replacements' => ['{suffix}' => 'mobile'],
			'group'        => '_g_{key}_switcher'
		],
	],
	$fields,
	[
		'replace_in' => ['css', 'group'],
	]
);

return $fields;