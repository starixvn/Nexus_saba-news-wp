<?php
/**
 * Fields for Overlay loops.
 */
$fields = [];
\Bunyad\Util\repeat_options(
	$_common['tpl_listing'],
	[
		'overlay' => [
			'replacements' => [
				'{selector}' => '.loop-overlay',
			],
			'skip' => [
				'loop_{key}_excerpts',
				'loop_{key}_excerpt_length',
				'loop_{key}_read_more',
				'loop_{key}_separators',
				'loop_{key}_content_center',
				'css_loop_{key}_media_margins',
				'css_loop_{key}_content_pad'
			]
		]
	],
	$fields,
	['replace_in' => ['css', 'group', 'context']]
);

$fields[] = [
	'css_loop_overlay_padding' => [
		'label'       => esc_html__('Overlay Paddings', 'bunyad-admin'),
		'type'        => 'dimensions',
		'value'       => [],
		'devices'     => true,
		'css'         => [
			'.loop-overlay .content' => ['dimensions' => 'padding']
		]
	],
];

$fields[] = [
	'loop_overlay_title_style' => [
		'label'       => esc_html__('Post Titles Style', 'bunyad-admin'),
		'type'        => 'select',
		'value'       => 'normal',
		'options'     => $_common['post_title_styles'],
		'css'         => [
			'.loop-overlay .content' => ['dimensions' => 'padding']
		]
	],
];

return $fields;