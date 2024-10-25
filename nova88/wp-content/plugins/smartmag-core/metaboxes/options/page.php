<?php
/**
 * Options metabox for pages
 */
$options = [
	[
		'label'   => esc_html__('Layout Type', 'bunyad-admin'),
		'name'    => 'layout_style', // will be _bunyad_layout_style
		'desc'    => esc_html__('Default uses the site-wide general layout setting set in Appearance > Customize.', 'bunyad-admin'),
		'type'    => 'radio',
		'options' => [
			''      => esc_html__('Default', 'bunyad-admin'),
			'right' => esc_html__('Right Sidebar', 'bunyad-admin'),
			'full'  => esc_html__('Full Width', 'bunyad-admin')],
		'value' => '' // default
	],

	[
		'label'   => esc_html__('Show Page Title?', 'bunyad-admin'),
		'name'    => 'page_title', 
		'type'    => 'select',
		'options' => [
			''    => esc_html__('Default', 'bunyad-admin'),
			'yes' => esc_html__('Yes', 'bunyad-admin'),
			'no'  => esc_html__('No', 'bunyad-admin')
		],
		'value' => '' // default
	],

	[
		'label'   => esc_html__('Spacious / Narrow Style?', 'bunyad-admin'),
		'name'    => 'page_spacious',
		'desc'    => esc_html__('Enable to add extra left/right spacing to text to create a dynamic spacious feel. Especially great when used with Full Width. Only works with default and some other page templates.', 'bunyad-admin'),
		'type'    => 'select',
		'options' => [
			'_default' => esc_html__('Default', 'bunyad-admin'),
			'1'        => esc_html__('Yes', 'bunyad-admin'),
			'0'        => esc_html__('No', 'bunyad-admin')
		],
		'value' => '_default',
	],

	[
		'label'   => esc_html__('Hide Breadcrumbs', 'bunyad-admin'),
		'name'    => 'hide_breadcrumbs',
		'desc'    => esc_html__('Ignore global settings and disable breadcrumbs on this page.', 'bunyad-admin'),
		'type'    => 'checkbox',
		'value'   => 0,
	],
];

/**
 * Only for legacy.
 */
$is_legacy = Bunyad::options()->legacy_mode;

if ($is_legacy) {

	$rev_slider = (class_exists('RevSlider') 
		? 
		['rev-slider' => esc_html__('Revolution Slider Plugin', 'bunyad-admin')] 
		: []
	); 

	$options = array_merge($options, [
		[
			'label'   => esc_html__('(Legacy) Show Featured Area?', 'bunyad-admin'),
			'name'    => 'featured_slider',
			'desc'    => 'WARNING: This is an old feature for backward compatibility and not recommended. It is better to use a full-width template in Elementor and add it there instead.',
			'type'    => 'select',
			'options' => array_merge([
				''	              => esc_html__('None', 'bunyad-admin'),
				'default'        => esc_html__('Use Posts Marked as "Featured Slider Post?"', 'bunyad-admin'),
				'default-latest' => esc_html__('Use Latest Posts from Whole Site', 'bunyad-admin'),
			], $rev_slider),
			'value' => '' // default
		],
		
		[
			'label'   => esc_html__('Featured Style', 'bunyad-admin'),
			'name'    => 'slider_type',
			'type'    => 'select',
			'options' => [
				'grid-a'   => esc_html__('Featured Grid: 1 + 4', 'bunyad-admin'),
				'grid-d'   => esc_html__('Featured Grid: 1 + 3', 'bunyad-admin'),
				'classic'  => esc_html__('Legacy / Classic Slider', 'bunyad-admin'),
			],
			'value' => 'grid-a' // default
		],

		[
			'label' => esc_html__('Number of Slides', 'bunyad-admin'),
			'name'  => 'slider_number',
			'type'  => 'text',
			'desc'  => esc_html__('Number of posts to show on the left side of the slider. 3 are displayed on the right as a post grid.', 'bunyad-admin'),
			'value' => 5, // default
		],
		
		[
			'label' => esc_html__('Slider Limit by Tag', 'bunyad-admin'),
			'name'  => 'slider_tags',
			'desc'  => esc_html__('Optional: To limit slider to certain tag or tags. If multiple, separate tag slugs by comma.', 'bunyad-admin'),
			'type'  => 'text',
			'value' => '' // default
		],
		
		[
			'label' => esc_html__('Slider Manual Post Ids', 'bunyad-admin'),
			'name'  => 'slider_posts',
			'desc'  => esc_html__('Optional: ADVANCED! If you only want to show a set of selected pre-selected posts. Enter post ids separated by comma.', 'bunyad-admin'),
			'type'  => 'text',
			'value' => '' // default
		],
		
		[
			'label' => esc_html__('Revolution Slider Alias', 'bunyad-admin'),
			'name'  => 'slider_rev',
			'desc'  => esc_html__('Enter alias of a slider you created in revolution slider plugin.', 'bunyad-admin'),
			'type'  => 'text',
			'value' => '' // default
		],
	]);
}

if (Bunyad::options()->layout_type == 'boxed') {
	
	$options[] = [
		'label'   => esc_html__('Boxed: Background Image', 'bunyad-admin'),
		'name'    => 'bg_image',
		'type'    => 'upload',
		'options' => [
				'type'         => 'image',
				'title'        => esc_html__('Upload This Picture', 'bunyad-admin'), 
				'button_label' => esc_html__('Upload', 'bunyad-admin'),
				'insert_label' => esc_html__('Use as Background', 'bunyad-admin')
		],	
		'value'   => '', // default
		// 'bg_type' => ['value' => 'cover'],
	];
}
