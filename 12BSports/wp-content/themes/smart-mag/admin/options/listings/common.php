<?php
/**
 * Common fields for blocks.
 */
return [
	[
		'name'    => 'loop_{key}_excerpts',
		'label'   => esc_html__('Show Excerpt', 'bunyad-admin'),
		'desc'    => esc_html__('For archives only. For home, set per block in pagebuilder.', 'bunyad-admin'),
		'value'   => 1,
		'type'    => 'toggle',
		'style'   => 'inline-sm',
	],

	[
		'name'    => 'loop_{key}_excerpt_length',
		'label'   => esc_html__('Excerpt Words', 'bunyad-admin'),
		'desc'    => '',
		'value'   => 20,
		'type'    => 'number',
		'style'   => 'inline-sm',
	],

	[
		'name'      => 'loop_{key}_read_more',
		'label'     => esc_html__('Read More', 'bunyad-admin'),
		'type'      => 'select',
		'value'     => 'none',
		'style'   => 'inline-sm',
		'options'   => $_common['read_more_options'],
	],
	
	[
		'name'    => 'loop_{key}_media_ratio',
		'label'   => esc_html__('Image Aspect Ratio', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'select',
		'style'   => 'inline-sm',
		'options' => $_common['ratio_options'],
	],
	[
		'name'        => 'loop_{key}_media_ratio_custom',
		'label'       => esc_html__('Custom Ratio', 'bunyad-admin'),
		'value'       => '',
		'desc'        => 'Calculated using width/height.',
		'type'        => 'number',
		'style'       => 'inline-sm',
		'classes'     => 'sep-bottom',
		'input_attrs' => ['min' => 0.25, 'max' => 3.5, 'step' => .1],
		'css'         => [
			'{selector} .ratio-is-custom' => ['props' => ['padding-bottom' => 'calc(100% / %s)']]
		],
		'transport' => 'refresh',
		'context'   => [['key' => 'loop_{key}_media_ratio', 'value' => 'custom']],
	],

	[
		'name'    => 'css_loop_{key}_media_margins',
		'label'   => esc_html__('Image Margins', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'dimensions',
		'devices' => true,
		'css'     => [
			'{selector} .media' => ['dimensions' => 'margin'],
		],
	],

	// Only for grid and overlay. Skipped in others.
	[
		'name'    => 'loop_{key}_content_center',
		'label'   => esc_html__('Centered Content', 'bunyad-admin'),
		'desc'    => '',
		'value'   => 0,
		'type'    => 'toggle',
		'style'   => 'inline-sm',
	],

	[
		'name'       => 'css_loop_{key}_title_typo',
		'label'      => esc_html__('Titles Typography', 'bunyad-admin'),
		'value'      => '',
		'desc'       => '',
		'type'       => 'group',
		'group_type' => 'typography',
		'devices'    => true,
		'style'      => 'edit',
		'css'        => '{selector} .post-title',
		// 'controls'   => ['spacing', 'transform', 'weight', 'style'],
	],

	[
		'name'    => 'css_loop_{key}_content_pad',
		'label'   => esc_html__('Content Paddings', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'dimensions',
		'devices' => true,
		'css'     => [
			'{selector} .content' => ['dimensions' => 'padding'],
		],
	],

	[
		'name'    => 'loop_{key}_separators',
		'label'   => esc_html__('Add Separators', 'bunyad-admin'),
		'value'   => 1,
		'type'    => 'toggle',
		'style'   => 'inline-sm',
		'classes' => 'sep-bottom',
	],

	[
		'name'    => 'loop_{key}_cat_labels',
		'label'   => esc_html__('Category Label Overlay', 'bunyad-admin'),
		'value'   => '',
		'type'    => 'select',
		'style'   => 'inline-sm',
		'options' => [
			''  => esc_html__('Auto/Global', 'bunyad-admin'),
			'1' => esc_html__('Enabled', 'bunyad-admin'),
			'0' => esc_html__('Disabled', 'bunyad-admin'),
		],
		'style'   => 'inline-sm',
	],

	'cat_labels_pos' => [
		'name'        => 'loop_{key}_cat_labels_pos',
		'label'       => esc_html__('Label Position', 'bunyad-admin'),
		'value'       => '',
		'type'        => 'select',
		'style'       => 'inline-sm',
		'classes'     => 'sep-bottom',
		'options'     => [
			'' => esc_html__('Auto/Global', 'bunyad-admin')
		] + $_common['cat_labels_pos_options'],
	],

];