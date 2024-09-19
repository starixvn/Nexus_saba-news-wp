<?php
/**
 * Category Labels and Overlays options.
 */
$fields = [
	[
		'name'    => 'cat_labels',
		'label'   => esc_html__('Enable Overlay Labels', 'bunyad-admin'),
		'value'   => 1,
		'desc'    => 'Can also be enabled/disabled per block in pagebuilder.',
		'type'    => 'toggle',
		'style'   => 'inline-sm',
	],

	[
		'name'    => 'cat_labels_pos',
		'label'   => esc_html__('Overlay Position', 'bunyad-admin'),
		'value'   => 'bot-left',
		'desc'    => 'Can also be enabled/disabled per block in pagebuilder.',
		'type'    => 'select',
		'style'   => 'inline-sm',
		'options' => $_common['cat_labels_pos_options'],
	],

	[
		'name'       => 'css_cat_labels_typo',
		'label'      => esc_html__('Typography', 'bunyad-admin'),
		'desc'       => 'Applies when enabled above or in a specific listing.',
		'value'      => '',
		'type'       => 'group',
		'group_type' => 'typography',
		'style'      => 'edit',
		'css'        => '.cat-labels .category',
	],
	[
		'name'       => 'css_cat_labels_bg',
		'label'      => esc_html__('Background Color', 'bunyad-admin'),
		'desc'       => '',
		'value'      => '',
		'type'       => 'color',
		'style'      => 'inline-sm',
		'css'        => [
			'.cat-labels .category' => [
				'props' => ['background-color' => '%s']
			]
		],
	],
	[
		'name'    => 'cat_labels_use_colors',
		'label'   => esc_html__('Category Color', 'bunyad-admin'),
		'value'   => 1,
		'desc'    => 'When a category color is set, use it for category in post meta or overlays.',
		'type'    => 'toggle',
		'style'   => 'inline-sm',
	],
	[
		'name'       => 'css_cat_labels_color',
		'label'      => esc_html__('Text Color', 'bunyad-admin'),
		'desc'       => '',
		'value'      => '',
		'type'       => 'color',
		'style'      => 'inline-sm',
		'css'        => [
			'.cat-labels .category' => [
				'props' => ['color' => '%s']
			]
		],
	],
	[
		'name'       => 'css_cat_labels_border_radius',
		'label'      => esc_html__('Border Radius', 'bunyad-admin'),
		'desc'       => '',
		'value'      => '',
		'type'       => 'number',
		'style'      => 'inline-sm',
		'css'        => [
			'.cat-labels .category' => [
				'props' => ['border-radius' => '%dpx']
			]
		],
	],
	[
		'name'    => 'css_cat_labels_pad',
		'label'   => esc_html__('Padding', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'dimensions',
		'devices' => true,
		'css'     => [
			'.cat-labels .category' => ['dimensions' => 'padding'],
		],
	],
	[
		'name'    => 'css_cat_labels_margins',
		'label'   => esc_html__('Overlay Margins', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'dimensions',
		'devices' => true,
		'css'     => [
			'.cat-labels-overlay' => ['dimensions' => 'margin'],
		],
	],

];

return $fields;