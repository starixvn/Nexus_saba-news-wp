<?php
/**
 * Element: Trending Ticker
 */
$fields = [
	[
		'name'    => 'header_ticker_posts',
		'label'   => esc_html__('Number of Posts', 'bunyad-admin'),
		'desc'    => '',
		'value'   => 8,
		'type'    => 'number',
		'style'   => 'inline-sm',
	],

	[
		'name'     => 'header_ticker_tags',
		'label'    => esc_html__('Limit to Tags', 'bunyad-admin'),
		'desc'     => 'Tag slugs separated by comma. Leave empty for no limit.',
		'value'    => '',
		'type'     => 'text',
	],

	[
		'name'     => 'header_ticker_delay',
		'label'    => esc_html__('Autoplay Delay (Seconds)', 'bunyad-admin'),
		'desc'     => '',
		'value'    => 8,
		'type'     => 'number',
		'style'    => 'inline-sm',
	],

	[
		'name'     => 'header_ticker_heading',
		'label'    => esc_html__('Ticker Label', 'bunyad-admin'),
		'desc'     => 'Tag slugs separated by comma. Leave empty for no limit.',
		'value'    => '',
		'placeholder' => esc_html__('Trending', 'bunyad'),
		'type'     => 'text',
	],
	// @todo: Dark
	[
		'name'    => 'css_header_ticker_label_color',
		'label'   => esc_html__('Label Color', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.trending-ticker .heading' => ['props' => ['color' => '%s']],
		],
	],

	[
		'name'    => 'css_header_ticker_label_typo',
		'label'   => esc_html__('Label Typography', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'group',
		'group_type' => 'typography',
		'style'   => 'edit',
		'css'     => '.trending-ticker .heading'
	],

	[
		'name'    => 'css_header_ticker_label_margins',
		'label'   => esc_html__('Label Margins', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'dimensions',
		'devices' => ['main', 'medium'],
		'css'     => [
			'.trending-ticker .heading' => ['dimensions' => 'margin'],
		]
	],

	[
		'name'    => 'css_header_ticker_typo',
		'label'   => esc_html__('Titles Typography', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'group',
		'group_type' => 'typography',
		'style'   => 'edit',
		'css'     => '.trending-ticker .post-link'
	],


	[
		'name'    => 'css_header_ticker_max_width',
		'label'   => esc_html__('Max Width of Titles', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'number',
		'devices' => ['main', 'large'],
		'style'   => 'inline-sm',
		'css'     => [
			'.trending-ticker' => ['props' => ['--max-width' => '%spx']],
		],
	],
];

return $fields;