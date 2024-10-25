<?php
/**
 * Element: Cart Icon
 */
$fields = [];

$template = [
	[
		'name'  => '_g_{key}_cart',
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
		'name'    => 'css_{key}_cart_no_counters',
		'label'   => esc_html__('Hide Counters', 'bunyad-admin'),
		'desc'    => 'By defaut, number of items in cart is shown when more than 0.',
		'value'   => 0,
		'type'    => 'toggle',
		'style'   => 'inline-sm',
		'css'   => [
			'.smart-head-{suffix} .cart-counter:not(._)' => ['props' => ['display' => 'none']],
			'.smart-head-{suffix} .cart-link.has-count'  => ['props' => ['padding-right' => '0']],
		],
	],

	[
		'name'    => 'css_{key}_cart_color',
		'label'   => esc_html__('Icon Color', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color',
		'css'     => [
			'.smart-head-{suffix} .cart-icon' => ['props' => ['--c-icons' => '%s']]
		],
	],
	[
		'name'    => 'css_{key}_cart_color_sd',
		'label'   => esc_html__('Dark: Icon Color', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color',
		'css'     => [
			'.s-dark .smart-head-{suffix} .cart-icon,
			.smart-head-{suffix} .s-dark .cart-icon' => ['props' => ['--c-icons' => '%s']]
		],
	],

	[
		'name'    => 'css_{key}_cart_size',
		'label'   => esc_html__('Icon Size', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'number',
		'input_attrs' => ['min' => 8],
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix}' => [
				'props' => ['--cart-icon-size' => '%spx']
			]
		],
	],
];

\Bunyad\Util\repeat_options(
	$template,
	[
		'header'     => [
			'replacements' => ['{suffix}' => 'main'],
			'group'        => '_g_{key}_cart'
		],
		'header_mob' => [
			'replacements' => ['{suffix}' => 'mobile'],
			'group'        => '_g_{key}_cart',
		],
	],
	$fields,
	[
		'replace_in' => ['css', 'group'],
	]
);

return $fields;