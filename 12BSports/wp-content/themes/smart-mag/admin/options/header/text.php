<?php
/**
 * Elements: Text/HTML
 */
$fields   = [];
$template = [
	/**
	 * Group: Text N
	 */
	[
		'name'  => '_g_header_{key}',
		'template' => [
			'text' => [
				'label' => esc_html__('Text/HTML 1', 'bunyad-admin'),
				'group' => '',
			],
			'text2' => [
				'label' => esc_html__('Text/HTML 2', 'bunyad-admin'),
				'group' => '',
			],
			'text3' => [
				'label' => esc_html__('Text/HTML 3', 'bunyad-admin'),
				'group' => '',
			],
			'text4' => [
				'label' => esc_html__('Text/HTML 4', 'bunyad-admin'),
				'group' => '',
			]
		],
		'type'  => 'group',
		'style' => 'collapsible',
	],

	// [
	// 	'name'  => 'css_nav_bg_{key}',
	// 	'value' => '',
	// 	'label' => esc_html__('Nav Background Color', 'bunyad-admin'),
	// 	'desc'  => '',
	// 	'type'  => 'color',
	// 	'css'   => [
	// 		'.s-{key} .navigation' => ['props' => [
	// 			'background-color' => '%s',
	// 		]],
	// 	],
	// ],
	[
		'name'    => 'header_{key}',
		'label'   => esc_html__('Text/HTML', 'bunyad-admin'),
		'desc'    => 'Shortcodes are supported as well.',
		'value'   => '',
		'type'    => 'textarea',
		'sanitize_callback' => '',
	],

	[
		'name'    => 'css_header_{key}_width',
		'label'   => esc_html__('Custom Width', 'bunyad-admin'),
		'value'   => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head .h-{key}' => ['props' => ['width' => '%spx']]
		]
	],

	[
		'name'          => 'css_header_{key}_typo',
		'label'         => esc_html__('Typography', 'bunyad-admin'),
		'desc'          => '',
		'type'          => 'group',
		'group_type'    => 'typography',
		'style'         => 'edit',
		'css'           => '.smart-head .h-{key}',
		'devices'       => true,
	],

	[
		'name'    => 'css_header_{key}_color',
		'value'   => '',
		'label'   => esc_html__('Text Color', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head .h-{key}' => ['props' => ['color' => '%s']]
		]
	],

	[
		'name'    => 'css_header_{key}_color_sd',
		'value'   => '',
		'label'   => esc_html__('Dark: Text Color', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.s-dark .smart-head .h-{key},
			.smart-head .s-dark .h-{key}' => ['props' => ['color' => '%s']]
		]
	],
];

\Bunyad\Util\repeat_options(
	$template,
	[
		'text' => [
			'group'   => '_g_header_text',
		],
		'text2'  => [
			'group'   => '_g_header_text2',
		],
		'text3'  => [
			'group'   => '_g_header_text3',
		],
		'text4'  => [
			'group'   => '_g_header_text4',
		],
	],
	$fields
);

return $fields;