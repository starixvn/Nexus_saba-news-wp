<?php
/**
 * Block Headings
 */
$heading_fields_tpl = [
	/**
	 * Group: Block Heading Styles
	 */
	[
		'name'  => '_g_block_headings_{key}',
		'desc'  => 'These settings apply to Page Builder blocks headings, Homepage Posts Carousel Title, Sidebar Titles (can be overridden from Main Layout & Sidebar > Sidebar Styling) and so on - depending on chosen heading style.',
		'type'  => 'group',
		'style' => 'collapsible',
	],

	[
		'name'        => 'bhead_align_{key}',
		'label'       => esc_html__('Heading Align', 'bunyad-admin'),
		'type'        => 'select',
		'value'        => 'left',
		'options'     => [
			'left'   => esc_html__('Default / Left', 'bunyad-admin'),
			'center' => esc_html__('Centered (Filters Not Supported)', 'bunyad-admin'),
		],
	],

	[
		'name'       => 'css_bhead_typo_{key}',
		'label'      => esc_html__('Heading Typography', 'bunyad-admin'),
		'desc'       => '',
		'value'      => '',
		'type'       => 'group',
		'group_type' => 'typography',
		'style'      => 'edit',
		'css'        => '.block-head-{key} .heading',
	],

	[
		'name'  => 'css_bhead_space_below_{key}',
		'label' => esc_html__('Space / Margin Below', 'bunyad-admin'),
		'desc'  => '',
		'value' => '',
		'type'  => 'slider',
		'css'   => [
			'.block-head-{key}' => ['props' => ['--space-below' => '%dpx']],
		],
	],

	[
		'name'  => 'css_bhead_line_weight_{key}',
		'label' => esc_html__('Accent Line Weight', 'bunyad-admin'),
		'desc'  => '',
		'value' => '',
		'type'  => 'number',
		'style' => 'inline-sm',
		'css'   => [
			'.block-head-{key}' => ['props' => ['--line-weight' => '%dpx']],
		],
	],

	// Only for c/c2.
	[
		'name'  => 'css_bhead_line_width_{key}',
		'label' => esc_html__('Accent Line Width', 'bunyad-admin'),
		'desc'  => '',
		'value' => '',
		'type'  => 'number',
		'style' => 'inline-sm',
		'css'   => [
			'.block-head-{key}' => ['props' => ['--c-line' => '%dpx']],
		],
	],

	[
		'name'  => 'css_bhead_line_color_{key}',
		'label' => esc_html__('Accent Line Color', 'bunyad-admin'),
		'desc'  => '',
		'value' => '',
		'type'  => 'color',
		'style' => 'inline-sm',
		'css'   => [
			'.block-head-{key}' => ['props' => ['--c-line' => '%s']],
		],
	],
	[
		'name'  => 'css_bhead_line_color_sd_{key}',
		'label' => esc_html__('Dark: Accent Line Color', 'bunyad-admin'),
		'desc'  => '',
		'value' => '',
		'type'  => 'color',
		'style' => 'inline-sm',
		'css'   => [
			'.s-dark .block-head-{key}' => ['props' => ['--c-line' => '%s']],
		],
	],

	// Only for 'c'.
	[
		'name'  => 'css_bhead_border_weight_{key}',
		'label' => esc_html__('Border Line Weight', 'bunyad-admin'),
		'desc'  => '',
		'value' => '',
		'type'  => 'number',
		'style' => 'inline-sm',
		'css'   => [
			'.block-head-{key}' => ['props' => ['--border-weight' => '%dpx']],
		],
	],

	[
		'name'  => 'css_bhead_border_color_{key}',
		'label' => esc_html__('Border Color', 'bunyad-admin'),
		'desc'  => '',
		'value' => '',
		'type'  => 'color',
		'style' => 'inline-sm',
		'css'   => [
			'.block-head-{key}' => ['props' => ['--c-border' => '%s']],
		],
	],
	[
		'name'  => 'css_bhead_border_color_sd_{key}',
		'label' => esc_html__('Dark: Border Color', 'bunyad-admin'),
		'desc'  => '',
		'value' => '',
		'type'  => 'color',
		'style' => 'inline-sm',
		'css'   => [
			'.s-dark .block-head-{key}' => ['props' => ['--c-border' => '%s']],
		],
	],

	[
		'name'  => 'css_bhead_bg_{key}',
		'value' => '',
		'label' => esc_html__('Heading Background', 'bunyad-admin'),
		'desc'  => '',
		'type'  => 'color',
		'style' => 'inline-sm',
		'css'   => [
			'.block-head-{key}' => ['props' => ['background-color' => '%s']],
		],
	],
	[
		'name'  => 'css_bhead_bg_sd_{key}',
		'value' => '',
		'label' => esc_html__('Dark: Heading BG', 'bunyad-admin'),
		'desc'  => '',
		'type'  => 'color',
		'style' => 'inline-sm',
		'css'   => [
			'.block-head-{key}' => ['props' => ['background-color' => '%s']],
		],
	],
		
	[
		'name'  => 'css_bhead_color_{key}',
		'label' => esc_html__('Heading Color', 'bunyad-admin'),
		'desc'  => esc_html__('Category color or theme main color will be used by default.', 'bunyad-admin'),
		'value' => '',
		'type'  => 'color',
		'style' => 'inline-sm',
		'css'   => [
			'.block-head-{key} .heading' => ['props' => ['color' => '%s']],
		],
	],
	[
		'name'  => 'css_bhead_color_sd_{key}',
		'label' => esc_html__('Dark: Heading Color', 'bunyad-admin'),
		'desc'  => '',
		'value' => '',
		'type'  => 'color',
		'style' => 'inline-sm',
		'css'   => [
			'.s-dark .block-head-{key} .heading' => ['props' => ['color' => '%s']],
		],
	],

	[
		'name'    => 'css_bhead_pad_{key}',
		'label'   => esc_html__('Heading Padding', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'dimensions',
		'devices' => true,
		'css'     => [
			'.block-head-{key}' => ['dimensions' => 'padding'],
		],
	],

	// Inner Padding: For style E.
	[
		'name'    => 'css_bhead_inner_pad_{key}',
		'label'   => esc_html__('Inner Padding', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'number',
		'devices' => true,
		'css'     => [
			'.block-head-{key}' => ['--inner-pad' => '%dpx'],
		],
	],

	// Box roundness: For style D.
	[
		'name'    => 'css_bhead_roundness_{key}',
		'label'   => esc_html__('Roundness', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'number',
		'css'     => [
			'.block-head-{key}' => ['--box-roundness' => '%dpx'],
		],
	],
];

$fields_headings = [
	[
		'name'        => 'block_head_style',
		'label'       => esc_html__('Default Heading Style', 'bunyad-admin'),
		'desc'        => esc_html__('
			This is only applied if not overriden in block settings, i.e. only if the Heading Style 
			in block heading is set to "Global Default Style".', 
			'bunyad-admin'
		),
		'value'       => 'a',
		'type'        => 'select',
		'options'     => $_common['block_headings'],
	],
	[
		'name'        => 'css_block_head_font',
		'label'       => esc_html__('Heading Font', 'bunyad-admin'),
		'desc'        => '',
		'value'       => '',
		'type'        => 'font-family',
		'css'         => [
			'.block-head .heading' => ['props' => ['font-family' => '%s']]
		]
	],
	[
		'name'        => 'css_block_head_filters_typo',
		'label'       => esc_html__('Filters Typography', 'bunyad-admin'),
		'desc'       => '',
		'value'      => '',
		'type'       => 'group',
		'group_type' => 'typography',
		'style'      => 'edit',
		'css'        => '.block-head .filters',
	]
];

foreach ($_common['block_headings'] as $style => $label) {

	// Make it a1 and e1.
	if (in_array($style, ['a', 'e'])) {
		$style .= '1';
	}

	$skips       = [];
	$skip_checks = [
		'bhead_border_weight',
		'bhead_border_color',
		'bhead_line_color',
		'bhead_line_width',
		'bhead_line_weight',
	];

	foreach ($skip_checks as $option) {
		if (!in_array($style, $_common['supports_' . $option])) {
			// Unsupported option. Add this to skips.
			array_push(
				$skips, 
				'css_' . $option . '_{key}',
				'css_' . $option . '_sd_{key}'
			);
		}
	}

	// Style 'e' uses padding instead of inner padding.
	if ($style === 'e') {
		$skips[] = 'css_bhead_pad_{key}';
	}
	else {
		$skips[] = 'css_bhead_inner_pad_{key}';
	}

	\Bunyad\util\repeat_options(
		$heading_fields_tpl,
		[
			$style => [
				'group'     => '_g_block_headings_{key}',
				'overrides' => [
					'_g_block_headings_{key}' => [
						'label'  => $label,
						'group'  => ''
					],
				],
				'skip' => $skips,
			],
		],
		$fields_headings,
		['replace_in' => ['css', 'group']]
	);
}

return $fields_headings;