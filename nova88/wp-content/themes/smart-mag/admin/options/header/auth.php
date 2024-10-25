<?php
/**
 * Element: Auth
 */
$fields = [];

$template = [
	[
		'name'  => '_g_{key}_auth',
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
		'name'    => '{key}_auth_text',
		'label'   => esc_html__('Auth Link Text', 'bunyad-admin'),
		'desc'    => '',
		'value'   => 'Login',
		'type'    => 'text',
		'style'   => 'inline-sm',
	],

	[
		'name'    => '{key}_auth_icon',
		'label'   => esc_html__('Show Icon', 'bunyad-admin'),
		'desc'    => '',
		'value'   => 1,
		'type'    => 'toggle',
		'style'   => 'inline-sm',
	],

	[
		'name'    => '{key}_auth_logout',
		'label'   => esc_html__('Show Logout', 'bunyad-admin'),
		'desc'    => 'Show logout link if logged in.',
		'value'   => 1,
		'type'    => 'toggle',
		'style'   => 'inline-sm',
	],
	// [
	// 	'name'    => 'css_{key}_cart_no_counters',
	// 	'label'   => esc_html__('Hide Counters', 'bunyad-admin'),
	// 	'desc'    => 'By defaut, number of items in cart is shown when more than 0.',
	// 	'value'   => 0,
	// 	'type'    => 'toggle',
	// 	'style'   => 'inline-sm',
	// 	'css'   => [
	// 		'.smart-head-{suffix} .cart-counter:not(._)' => ['props' => ['display' => 'none']]
	// 	],
	// ],

	[
		'name'    => 'css_{key}_auth_color',
		'label'   => esc_html__('Link Color', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color',
		'css'     => [
			'.smart-head-{suffix} .auth-link' => ['props' => ['--c-icons' => '%s']]
		],
	],
	[
		'name'    => 'css_{key}_auth_color_sd',
		'label'   => esc_html__('Dark: Link Color', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color',
		'css'     => [
			'.s-dark .smart-head-{suffix} .auth-link,
			.smart-head-{suffix} .s-dark .auth-link' => ['props' => ['--c-icons' => '%s']]
		],
	],

	[
		'name'    => 'css_{key}_auth_icon_size',
		'label'   => esc_html__('Icon Size', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'number',
		'input_attrs' => ['min' => 8],
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix} .auth-link' => [
				'props' => ['--icons-size' => '%spx']
			]
		],
	],

	[
		'name'       => 'css_{key}_auth_typo',
		'label'      => esc_html__('Typography', 'bunyad-admin'),
		'desc'       => '',
		'value'      => '',
		'type'       => 'group',
		'group_type' => 'typography',
		'style'      => 'edit',
		'css'        => '.smart-head-{suffix} .auth-link',
		'controls'   => ['size', 'spacing', 'transform'],
	],
];

\Bunyad\Util\repeat_options(
	$template,
	[
		'header'     => [
			'replacements' => ['{suffix}' => 'main'],
			'group'        => '_g_{key}_auth'
		],
		'header_mob' => [
			'replacements' => ['{suffix}' => 'mobile'],
			'group'        => '_g_{key}_auth',
		],
	],
	$fields,
	[
		'replace_in' => ['css', 'group'],
	]
);

return $fields;