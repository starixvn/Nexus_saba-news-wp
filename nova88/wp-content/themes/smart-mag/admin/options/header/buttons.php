<?php
/**
 * Element: Buttons
 */
$fields_buttons = [];

$buttons = [
	/**
	 * Group: Button N
	 */
	[
		'name'  => '_g_header_buttons_{key}',
		'template' => [
			'button' => [
				'label' => esc_html__('Button 1', 'bunyad-admin'),
				'group' => '',
			],
			'button2' => [
				'label' => esc_html__('Button 2', 'bunyad-admin'),
				'group' => '',
			],
			'button3' => [
				'label' => esc_html__('Button 3', 'bunyad-admin'),
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
		'name'    => 'header_{key}_text',
		'label'   => esc_html__('Button Text', 'bunyad-admin'),
		'desc'    => '',
		'value'   => 'Button',
		'type'    => 'text',
	],
	[
		'name'    => 'header_{key}_link',
		'label'   => esc_html__('Button Link', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '#',
		'type'    => 'text',
	],
	[
		'name'    => 'header_{key}_target',
		'label'   => esc_html__('Open in New Tab', 'bunyad-admin'),
		'desc'    => '',
		'value'   => 0,
		'type'    => 'toggle',
		'style'   => 'inline-sm'
	],

	[
		'name'    => 'header_{key}_style',
		'label'   => esc_html__('Button Style', 'bunyad-admin'),
		'desc'    => '',
		'value'   => 'alt',
		'type'    => 'select',
		'options' => [
			'alt' => esc_html__('Transparent', 'bunyad-admin'),
			'a'   => esc_html__('Solid Color', 'bunyad-admin'),
		]
	],

	[
		'name'          => 'css_header_{key}_typo',
		'label'         => esc_html__('Typography', 'bunyad-admin'),
		'desc'          => '',
		'type'          => 'group',
		'group_type'    => 'typography',
		'style'         => 'edit',
		'css'           => '.smart-head .{class}',
		'devices'       => false,
	],

	[
		'name'    => 'css_header_{key}_color',
		'value'   => '',
		'label'   => esc_html__('Text Color', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head .{class}:not(._)' => ['props' => ['color' => '%s']]
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
			'.s-dark .smart-head .{class},
			.smart-head .s-dark .{class}' => ['props' => ['color' => '%s']]
		]
	],

	[
		'name'    => 'css_header_{key}_bg',
		'value'   => '',
		'label'   => esc_html__('Background Color', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head .{class}' => ['props' => ['background-color' => '%s']]
		]
	],

	[
		'name'    => 'css_header_{key}_bg_sd',
		'value'   => '',
		'label'   => esc_html__('Dark: Background Color', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.s-dark .smart-head .{class},
			.smart-head .s-dark .{class}' => ['props' => ['background-color' => '%s']]
		]
	],

	[
		'name'    => 'css_header_{key}_bc',
		'value'   => '',
		'label'   => esc_html__('Border Color', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head .{class}' => ['props' => ['border-color' => '%s']]
		]
	],

	[
		'name'    => 'css_header_{key}_bc_sd',
		'value'   => '',
		'label'   => esc_html__('Dark: Border Color', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.s-dark .smart-head .{class},
			.smart-head .s-dark .{class}' => ['props' => ['border-color' => '%s']]
		]
	],

	[
		'name'    => 'css_header_{key}_bradius',
		'value'   => '',
		'label'   => esc_html__('Border Roundness', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head .{class}' => ['props' => ['border-radius' => '%spx']]
		]
	],

	[
		'name'    => 'css_header_{key}_height',
		'value'   => '',
		'label'   => esc_html__('Height', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'devices' => true,
		'css'     => [
			'.smart-head .{class}' => [
				'props' => [
					'height' => '%spx',
					'line-height' => '%spx',
				]
			]
		]
	],

	[
		'name'    => 'css_header_{key}_pad',
		'value'   => '',
		'label'   => esc_html__('Padding', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'dimensions',
		'fields'  => ['left', 'right'],
		'devices' => true,
		'css'     => [
			'.smart-head .{class}' => ['dimensions' => 'padding']
		]
	],

	[
		'name'  => 'css_header_{key}_lg_hide',
		'label' => esc_html__('Hide on Large Tablets', 'bunyad-admin'),
		'value' => '',
		'type'  => 'toggle',
		'style' => 'inline-sm',
		'css'   => [
			'.smart-head-main .{class}' => [
				'large' => [
					'props' => ['display' => 'none'],
					'value_key' => 'global'
				]
			]
		]
	]
];

\Bunyad\Util\repeat_options(
	$buttons,
	[
		'button' => [
			'group'   => '_g_header_buttons_button',
			'replacements' => ['{class}' => 'ts-button1']
		],
		'button2'  => [
			'group'   => '_g_header_buttons_button2',
			'replacements' => ['{class}' => 'ts-button2']
		],
		'button3'  => [
			'group'   => '_g_header_buttons_button3',
			'replacements' => ['{class}' => 'ts-button3']
		],
	],
	$fields_buttons
);

return $fields_buttons;