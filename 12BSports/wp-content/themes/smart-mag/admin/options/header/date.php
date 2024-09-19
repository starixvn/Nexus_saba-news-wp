<?php
/**
 * Element: Date
 */
$fields = [
	[
		'name'    => 'header_date_format',
		'label'   => esc_html__('Date Format', 'bunyad-admin'),
		'desc'    => '',
		'value'   => 'l, F j',
		'type'    => 'text',
	],

	// [
	// 	'name'    => 'css_header_date_typo',
	// 	'label'   => esc_html__('Titles Typography', 'bunyad-admin'),
	// 	'desc'    => '',
	// 	'value'   => '',
	// 	'type'    => 'group',
	// 	'group_type' => 'typography',
	// 	'style'   => 'edit',
	// 	'css'     => '.smart-head .h-date'
	// ],


];

return $fields;