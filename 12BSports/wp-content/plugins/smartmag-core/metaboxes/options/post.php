<?php
/**
 * Meta box for post options
 */

$_common = Bunyad::core()->get_common_data('options');

$options = [
	[
		'label'      => esc_html__('Sub Title', 'bunyad-admin'),
		'name'       => 'sub_title',
		'type'       => 'text',
		'input_size' => 90,
		'desc'       => esc_html__('Optional Sub-title/text thats displayed below main post title.', 'bunyad-admin')
	],

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
		'label'   => esc_html__('Post Style', 'bunyad-admin'),
		'name'    => 'layout_template', // will be _bunyad_layout_style
		'type'    => 'select',
		'options' => [
			''              => esc_html__('Default (from Customizer)', 'bunyad-admin'),
		] + (array) $_common['post_style_options'],
		'value' => '' // default
	],

	[
		'label'   => esc_html__('Spacious / Narrow Style?', 'bunyad-admin'),
		'name'    => 'layout_spacious',
		'desc'    => esc_html__('Enable to add extra left/right spacing to text to create a dynamic spacious feel. Especially great when used with Full Width.', 'bunyad-admin'),
		'type'    => 'select',
		'options' => [
			'_default' => esc_html__('Default', 'bunyad-admin'),
			'1'        => esc_html__('Yes', 'bunyad-admin'),
			'0'        => esc_html__('No', 'bunyad-admin')
		],
		'value' => '_default',
	],
	
	[
		'label' => esc_html__('Primary Category', 'bunyad-admin'),
		'name'  => 'cat_label', // _bunyad_cat_label
		'type'  => 'html',
		'html'  => wp_dropdown_categories([
			'show_option_all' => esc_html__('-- Auto Detect--', 'bunyad-admin'), 
			'hierarchical'    => 1, 'order_by' => 'name', 'class' => '', 
			'name'            => '_bunyad_cat_label', 'echo' => false,
			'selected'        => Bunyad::posts()->meta('cat_label')
		]),
		'desc' => esc_html__('When you have multiple categories for a post, auto detection chooses one in alphabetical order. These labels are shown above image in category listings.', 'bunyad-admin')
	],

	[
		'label_left' => esc_html__('Disable Featured?', 'bunyad-admin'),
		'label'      => esc_html__('Do not show featured Image, Video, or Gallery at the top for this post, on post page.', 'bunyad-admin'),
		'name'       => 'featured_disable', // _bunyad_featured_post
		'type'       => 'checkbox',
		'value'      => 0
	],
	
	[
		'label'        => esc_html__('Featured Video/Audio', 'bunyad-admin'),
		'name'         => 'featured_video', // will be _bunyad_layout_style
		'type'         => 'textarea',
		'options'      => ['rows' => 2, 'cols' => 90],
		'value'        => '',
		'desc'         => esc_html__('When using Video or Audio post format, enter a link of the video or audio from a service like YouTube, Vimeo, SoundCloud. Shortcodes also supported.', 'bunyad-admin'),
		'allowed_html' => ['iframe' => ['scrolling' => true, 'src' => true, 'width' => true, 'height' => true, 'frameborder' => true, 'allowfullscreen' => true]]
	],

	[
		'label'   => esc_html__('Multi-page Content Slideshow?', 'bunyad-admin'),
		'desc'    => esc_html__('You can use <!--nextpage--> to split a page into multi-page content slideshow.', 'bunyad-admin'),
		'name'    => 'content_slider', // _bunyad_featured_post
		'type'    => 'select',
		'value'   => '',
		'options' => [
			''         => esc_html__('Disabled', 'bunyad-admin'),
			'ajax'     => esc_html__('AJAX - No Refresh', 'bunyad-admin'),
			'refresh'  => esc_html__('Multi-page - Refresh for next page', 'bunyad-admin'), 
		],
	],

	[
		'label' => esc_html__('Legacy: Featured Post', 'bunyad-admin'),
		'desc'  => esc_html__('Used for legacy elements only like Classic Slider, or Category Legacy Mega Menu.', 'bunyad-admin'),
		'name'  => 'featured_post', // _bunyad_featured_post
		'type'  => 'checkbox',
		'value' => 0
	],
];

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