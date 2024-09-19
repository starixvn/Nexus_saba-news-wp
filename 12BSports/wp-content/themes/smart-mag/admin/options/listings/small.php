<?php
/**
 * Fields for Small Post loops.
 */
$fields = [];
\Bunyad\Util\repeat_options(
	$_common['tpl_listing'],
	[
		'small' => [
			'replacements' => [
				'{selector}' => '.loop-small',
			],
			'skip' => [
				'loop_{key}_excerpts',
				'loop_{key}_excerpt_length',
				'loop_{key}_read_more',
				'loop_{key}_content_center',
				'loop_{key}_cat_labels',
				'loop_{key}_cat_labels_pos',
			]
		]
	],
	$fields,
	['replace_in' => ['css', 'group', 'context']]
);

// Extra fields for small block.
$fields = array_merge($fields, [
	[
		'name'    => 'loop_small_reviews',
		'label'   => esc_html__('Reviews Style', 'bunyad-admin'),
		'desc'    => '',
		'value'   => 'bars',
		'type'    => 'select',
		// 'style'   => 'inline-sm',
		'options' => [
			'' => esc_html__('Auto/Global', 'bunyad-admin')
		] + $_common['reviews_options'],
	],

	[
		'name'    => 'loop_small_post_formats',
		'label'   => esc_html__('Post Format Icons', 'bunyad-admin'),
		'desc'    => '',
		'value'   => 0,
		'type'    => 'toggle',
		'style'   => 'inline-sm',
	],
	[
		'name'        => 'loop_small_media_width',
		'label'       => esc_html__('Image Width %', 'bunyad-admin'),
		'value'       => '',
		'desc'        => '',
		'type'        => 'number',
		'style'       => 'inline-sm',
		'input_attrs' => ['min' => 0, 'max' => 100, 'step' => 1],
		'transport'   => 'refresh',
		'css'         => [
			'.loop-small .media' => [
				'props'     => ['width' => '%s%', 'max-width' => '50%'],
			]
		],
	],
	[
		'name'        => 'css_loop_small_media_max_width',
		'label'       => esc_html__('Max Width (px)', 'bunyad-admin'),
		'value'       => '',
		'desc'        => '',
		'type'        => 'slider',
		'devices'     => true,
		'classes'     => 'sep-bottom',
		'input_attrs' => ['min' => 1, 'max' => 500, 'step' => 1],
		'css'         => [
			'.loop-small .media:not(i)' => [
				'props'     => ['max-width' => '%spx'],
			]
		],
		'condition' => [['key' => 'loop_small_media_width', 'value' => '', 'compare' => '!=']]
	],

	[
		'name'    => 'loop_small_meta_above',
		'label'   => esc_html__('Meta: Above Title', 'bunyad-admin'),
		'desc'    => '',
		'value'   => [],
		'type'    => 'checkboxes',
		'options' => $_common['meta_options'],
		'style'   => 'sortable',
	],
	[
		'name'    => 'loop_small_meta_below',
		'label'   => esc_html__('Meta: Below Title', 'bunyad-admin'),
		'desc'    => '',
		'value'   => ['date'],
		'type'    => 'checkboxes',
		'options' => $_common['meta_options'],
		'style'   => 'sortable',
	],
]);


return $fields;