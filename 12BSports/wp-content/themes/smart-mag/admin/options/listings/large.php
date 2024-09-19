<?php
/**
 * Fields for Large Listings.
 */
$fields = [];
\Bunyad\Util\repeat_options(
	$_common['tpl_listing'],
	[
		'large' => [
			'overrides'    => [
				'loop_{key}_read_more' => [
					'value'  => 'btn'
				],
				'loop_{key}_excerpt_length' => [
					'value'  => 100,
				]
			],
			'replacements' => [
				'{selector}' => '.loop-grid-lg',
			],
			'skip' => [
				'loop_{key}_separators'
			]
		]
	],
	$fields,
	['replace_in' => ['css', 'group', 'context']]
);

$fields[] = [
	'name'    => 'loop_large_classic_body',
	'label'   => esc_html__('Legacy Style: Body', 'bunyad-admin'),
	'value'   => 'excerpt',
	'type'    => 'select',
	'style'   => 'inline-sm',
	'options' => [
		'full'    => esc_html__('Full Post', 'bunyad-admin'),
		'excerpt' => esc_html__('Excerpts', 'bunyad-admin'),
	],

];

return $fields;