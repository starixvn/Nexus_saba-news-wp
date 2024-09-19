<?php
/**
 * Fields for Grid Listings.
 */
$fields = [];
\Bunyad\Util\repeat_options(
	$_common['tpl_listing'],
	[
		'grid' => [
			'overrides'    => [
				'css_loop_{key}_title_typo' => [
					'css'   => '.loop-grid-base .post-title'
				],
				'css_loop_{key}_media_margins' => [
					'css'   => [
						'.loop-grid-base .media' => ['dimensions' => 'margin'],
					]
				]
			],
			'replacements' => [
				'{selector}' => '.loop-grid',
			],
			'skip' => [
				'loop_{key}_separators',
			]
		]
	],
	$fields,
	['replace_in' => ['css', 'group', 'context']]
);

$fields[] = [
	'name'         => 'loop_grid_style',
	'label'        => esc_html__('Style Variation', 'bunyad-admin'),
	'value'        => '',
	'desc'         => 'Different styles like cards, normal etc.',
	'type'         => 'select',
	'options'      => [
		''        => esc_html__('Normal', 'bunyad-admin'),
		'stylish' => esc_html__('Stylish', 'bunyad-admin'),
		'card'    => esc_html__('Cards', 'bunyad-admin'),
	],
];

$fields[] = [
	'name'       => 'css_loop_grid_column_gap',
	'label'      => esc_html__('Custom Column Gap', 'bunyad-admin'),
	'value'      => '',
	'desc'       => '',
	'type'       => 'number',
	'devices'    => ['main', 'medium'],
	'style'      => 'inline-sm',
	'css'        => [
		'.loop-grid' => ['props' => ['--grid-gutter' => '%spx']]
	]
];

$fields[] = [
	'name'       => 'css_loop_grid_box_radius',
	'label'      => esc_html__('Post Box Roundness', 'bunyad-admin'),
	'value'      => '',
	'desc'       => '',
	'type'       => 'number',
	'style'      => 'inline-sm',
	'css'        => [
		'.loop-grid .l-post' => [
			'props' => ['border-radius' => '%spx', 'overflow' => 'hidden']
		]
	]
];

$fields[] = [
	'name'       => 'css_loop_grid_title_sm_typo',
	'label'      => esc_html__('Small Style: Titles', 'bunyad-admin'),
	'value'      => '',
	'desc'       => 'Post titles typography for the small variation of grid posts.',
	'type'       => 'group',
	'devices'    => true,
	'group_type' => 'typography',
	'style'      => 'edit',
	'css'        => '.loop-grid-sm .post-title',
	// 'controls'   => ['spacing', 'transform', 'weight', 'style'],
];

$numbers_fields = [
	[
		'name'       => 'css_loop_nums_font',
		'label'      => esc_html__('Numbers Font', 'bunyad-admin'),
		'value'      => '',
		'desc'       => '',
		'type'       => 'font-family',
		'classes'    => 'sep-top',
		'css'        => [
			'.has-nums .l-post' => ['props' => ['--num-font' => '%s']],
		]
		// 'controls'   => ['spacing', 'transform', 'weight', 'style'],
	],

	[
		'name'       => 'css_loop_nums_simple_typo',
		'label'      => esc_html__('Numbers Typography: Simple', 'bunyad-admin'),
		'value'      => '',
		'desc'       => '',
		'type'       => 'group',
		'devices'    => true,
		'group_type' => 'typography',
		'style'      => 'edit',
		'css'        => 
			'.has-nums-a .l-post .post-title:before,
			.has-nums-b .l-post .content:before',
		'controls'   => ['size', 'spacing', 'weight', 'line_height', 'style'],
	],

	[
		'name'       => 'css_loop_nums_c_typo',
		'label'      => esc_html__('Numbers Typography: Circled', 'bunyad-admin'),
		'value'      => '',
		'desc'       => '',
		'type'       => 'group',
		'devices'    => true,
		'group_type' => 'typography',
		'style'      => 'edit',
		'css'        => 
			'.has-nums-c .l-post .post-title:before,
			.has-nums-c .l-post .content:before',
		'controls'   => ['size', 'spacing', 'weight', 'line_height', 'style'],
	],

	[
		'name'    => 'css_loop_nums_simple_color',
		'label'   => esc_html__('Color', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.has-nums:not(.has-nums-c)' => [
				'props' => ['--num-color' => '%s']
			]
		],
	],

	[
		'name'    => 'css_loop_nums_simple_color_sd',
		'label'   => esc_html__('Dark: Color', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.s-dark .has-nums:not(.has-nums-c)' => [
				'props' => ['--num-color' => '%s']
			]
		],
	],

	[
		'name'    => 'css_loop_nums_simple_after',
		'label'   => esc_html__('After Number', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'text',
		'style'   => 'inline-sm',
		'css'     => [
			'.has-nums-a .l-post .post-title:before,
			.has-nums-b .l-post .content:before' => [
				'props' => ['content' => 'counter(ts-loop) "%s"']
			]
		],
	],
];

array_push($fields, ...$numbers_fields);

return $fields;