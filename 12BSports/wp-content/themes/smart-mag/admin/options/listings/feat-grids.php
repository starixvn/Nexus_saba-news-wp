<?php
/**
 * Fields for Grid Listings.
 */
$fields = [
	[
		'name'    => 'feat_grids_meta_above',
		'label'   => esc_html__('Meta: Items Above Title', 'bunyad-admin'),
		'desc'    => '',
		'value'   => ['cat'],
		'type'    => 'checkboxes',
		'options' => $_common['meta_options'],
		// Not a global style, specific to checkboxes.
		'style'   => 'sortable',
	],
	[
		'name'    => 'feat_grids_meta_below',
		'label'   => esc_html__('Meta: Items Below Title', 'bunyad-admin'),
		'desc'    => '',
		'value'   => ['author', 'date'],
		'type'    => 'checkboxes',
		'options' => $_common['meta_options'],
		// Not a global style, specific to checkboxes.
		'style'   => 'sortable',
	],
	[
		'name'  => '_n_feat_grids',
		'type'  => 'message',
		'label' => '',
		'text'  => 'Many more options are available when adding the featured grid in Elementor Page Builder for your homepage.',
		'style' => 'message-info',
	],
];

return $fields;