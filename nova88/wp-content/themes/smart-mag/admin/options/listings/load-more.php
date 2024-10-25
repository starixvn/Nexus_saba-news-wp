<?php
/**
 * Load More options.
 */
$fields_load_more = [
	[
		'name'       => 'load_more_style',
		'label'      => esc_html__('Load More Style', 'bunyad-admin'),
		'desc'       => 'Global style. May be overridden per block in Elementor.',
		'value'      => 'a',
		'type'       => 'select',
		'style'      => 'inline-sm',
		'options'    => $_common['load_more_options']
	],
];

$tpl_load_more = [

	[
		'name'       => 'css_load_more_typo_{key}',
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
		'name'    => 'css_load_more_color_{key}',
		'label'   => esc_html__('Text Color', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'{selector}' => ['props' => ['color' => '%s']]
		],
	],
	[
		'name'    => 'css_load_more_color_sd_{key}',
		'label'   => esc_html__('Dark: Text Color', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.s-dark {selector}' => ['props' => ['color' => '%s']]
		],
	],

	[
		'name'    => 'css_load_more_bg_{key}',
		'label'   => esc_html__('Button Background', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'{selector}' => ['props' => ['background-color' => '%s']]
		],
	],
	[
		'name'    => 'css_load_more_bg_sd_{key}',
		'label'   => esc_html__('Dark: Button Background', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.s-dark {selector}' => ['props' => ['background-color' => '%s']]
		],
	],

	[
		'name'    => 'css_load_more_border_{key}',
		'label'   => esc_html__('Border Color', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'{selector}' => ['props' => ['border-color' => '%s']]
		],
	],

	[
		'name'    => 'css_load_more_pad_{key}',
		'label'   => esc_html__('Button Padding', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'dimensions',
		'devices' => true,
		'css'     => [
			'{selector}' => ['dimensions' => 'padding']
		],
	],

	[
		'name'    => 'css_load_more_width_{key}',
		'label'   => esc_html__('Button Width (px)', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'{selector}' => ['props' => [
				'width'     => '%spx', 
				'min-width' => '0'
			]]
		],
	],
	[
		'name'    => 'css_load_more_bradius_{key}',
		'label'   => esc_html__('Border Radius', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'{selector}' => ['props' => ['border-radius' => '%spx']]
		],
	]
];

\Bunyad\Util\repeat_options(
	$tpl_load_more,
	[
		'btn' => [
			'replacements' => [
				'{selector}' => '.load-button'
			]
		],
	],
	$fields_load_more,
	['replace_in' => ['css', 'group']]
);

return $fields_load_more;