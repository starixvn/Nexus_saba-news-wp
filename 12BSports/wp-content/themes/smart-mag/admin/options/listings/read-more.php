<?php
/**
 * Read More options.
 */
$fields_read_more = [];
$tpl_read_more = [

	[
		'name'  => '_g_design_rmore_{key}',
		'type'  => 'group',
		'style' => 'collapsible',

		'template' => [
			'btn'   => [
				'label' => esc_html__('Style: Button', 'bunyad-admin'),
				'group' => '',
			],
			'btn_b'   => [
				'label' => esc_html__('Style: Colored Button', 'bunyad-admin'),
				'group' => '',
			],
			'basic' => [
				'label' => esc_html__('Style: Basic Text', 'bunyad-admin'),
				'group' => '',
			]
		],
	],

	[
		'name'       => 'css_rmore_typo_{key}',
		'label'      => esc_html__('Typography', 'bunyad-admin'),
		'value'      => '',
		'desc'       => '',
		'type'       => 'group',
		'group_type' => 'typography',
		'style'      => 'edit',
		'devices'    => true,
		'css'        => '{selector}',
	],

	[
		'name'    => 'css_rmore_text_color_{key}',
		'label'   => esc_html__('Text Color', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'css'     => [
			'{selector}' => ['props' => ['color' => '%s']]
		],
	],
	[
		'name'    => 'css_rmore_text_color_sd_{key}',
		'label'   => esc_html__('Dark: Text Color', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'css'     => [
			'.s-dark {selector}' => ['props' => ['color' => '%s']]
		],
	],

	[
		'name'    => 'css_rmore_bg_{key}',
		'label'   => esc_html__('Button Background', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'css'     => [
			'{selector}' => ['props' => ['background-color' => '%s']]
		],
	],
	[
		'name'    => 'css_rmore_bg_sd_{key}',
		'label'   => esc_html__('Dark: Button Background', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'css'     => [
			'.s-dark {selector}' => ['props' => ['background-color' => '%s']]
		],
	],

	[
		'name'    => 'css_rmore_border_{key}',
		'label'   => esc_html__('Border Color', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color-alpha',
		'css'     => [
			'{selector}' => ['props' => ['border-color' => '%s']]
		],
	],

	[
		'name'    => 'css_rmore_padding_{key}',
		'label'   => esc_html__('Button Padding', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'dimensions',
		'devices' => true,
		'css'     => [
			'{selector}' => ['dimensions' => 'padding']
		],
	]
];

\Bunyad\Util\repeat_options(
	$tpl_read_more,
	[
		'btn' => [
			'group' => '_g_design_rmore_{key}',
			'replacements' => [
				'{selector}' => '.read-more-btn'
			]
		],
		'btn_b' => [
			'group' => '_g_design_rmore_{key}',
			'replacements' => [
				'{selector}' => '.read-more-btn-b'
			]
		],
		'basic' => [
			'group' => '_g_design_rmore_{key}',
			'replacements' => [
				'{selector}' => '.read-more-basic'
			]
		],
	],
	$fields_read_more,
	['replace_in' => ['css', 'group']]
);

return $fields_read_more;