<?php
/**
 * Options for category / term meta.
 */
$_common = Bunyad::core()->get_common_data('options');
$options = [];

$custom_archives = [];
if (class_exists('\Sphere\Core\Elementor\Layouts\Module')) {
	$custom_archives = \Sphere\Core\Elementor\Layouts\Module::instance()->get_options('ts-archive');
}

if ($custom_archives) {

	$custom_loop_desc = sprintf(
		'Create a custom layout from %1$sSmartMag > Custom Layouts%2$s of Archive type.',
		'<a href="'. admin_url('edit.php?post_type=spc-el-layouts') .'" target="_blank">',
		'</a>'
	);

	$options[] = [
		'name'    => 'custom_template', // will be _bunyad_template
		'label'   => esc_html__('Custom Listing Layout', 'bunyad-admin'),
		'desc'    => 'When using a custom layout, all settings below will be IGNORED. ' . $custom_loop_desc,
		'type'    => 'select',
		'options' => [
			'' => esc_html__('Default / Customizer Setting', 'bunyad-admin'),
			'none' => esc_html__('None / Disabled', 'bunyad-admin'),
		] + $custom_archives,
		'value' => '' // default
	];
}

array_push($options, ...[
	[
		'name'    => 'template', // will be _bunyad_template
		'label'   => esc_html__('Category Listing Style', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'select',
		'options' => [
			'' => esc_html__('Default / Global Style', 'bunyad-admin'),
		] + $_common['archive_loop_options'],
		'value' => '' // default
	],
	
	[
		'name'    => 'sidebar',
		'label'   => esc_html__('Show Sidebar', 'bunyad-admin'),
		'desc'    => esc_html__('Select layout sidebar preference for this category listing.', 'bunyad-admin'),
		'type'    => 'select',
		'options' => $_common['sidebar_options'],
		'value' => '' // default
	],

	[
		'name'    => 'slider',
		'label'   => esc_html__('Featured Area', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'select',
		'options' => [
			''         => esc_html__('Global Settings', 'bunyad-admin'),
			'latest'   => esc_html__('Show Latest Posts/Use a tag filter', 'bunyad-admin'),
			'default'  => esc_html__('Show Posts Marked for Featured Slider', 'bunyad-admin'),
			'none'     => esc_html__('Disabled', 'bunyad-admin'),
		],
		'value' => '' // default
	],

	[
		'name'    => 'slider_tags',
		'label'   => esc_html__('Featured Filter by Tag(s)', 'bunyad-admin'),
		'desc'    => esc_html__('Enter a tag slug, or multiple tag slugs separated by comma, to limit posts from.', 'bunyad-admin'),
		'type'    => 'text',
		'value' => '' // default
	],

	[
		'name'    => 'slider_type',
		'label'   => esc_html__('Featured Style', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'select',
		'options' => $_common['featured_type_options'] + [
			'classic' => 'Legacy: Classic Slider',
		],
		'value' => '' // default
	],

	[
		'name'    => 'slider_number',
		'label'   => esc_html__('Featured Posts', 'bunyad-admin'),
		'desc'    => esc_html__('Number of posts for featured area.', 'bunyad-admin'),
		'type'    => 'number',
		'value'   => 5 // default
	],

	[
		'name'    => 'pagination_type',
		'label'   => esc_html__('Pagination Type', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'select',
		'options' => [
			'' => esc_html__('Default / Global', 'bunyad-admin')
		] + $_common['pagination_options'],
		'value' => '' // default
	],

	[
		'name'    => 'per_page',
		'label'   => esc_html__('Custom Posts Per Page', 'bunyad-admin'),
		'desc'    => esc_html__('Override default posts per page setting for this category. Leave empty for default from Settings.', 'bunyad-admin'),
		'type'    => 'number',
		'value'   => '' // default
	],

	[
		'name'    => 'color',
		'label'   => esc_html__('Category Color', 'bunyad-admin'),
		'desc'    => esc_html__('SmartMag uses this for category overlay labels, heading color in home blocks, and in some styles of main navigation.', 'bunyad-admin'),
		'type'    => 'color',
		'value' => '' // default
	],

	[
		'name'    => 'main_color',
		'label'   => esc_html__('Main Site Color', 'bunyad-admin'),
		'desc'    => esc_html__('Not Recommended! Setting this color will change the entire site main color when viewing this category or posts that belong to this category.', 'bunyad-admin'),
		'type'    => 'color',
		'value' => '' // default
	],
]);

if (Bunyad::options()->layout_type === 'boxed') {
	$options[] = [
		'label'   => esc_html__('Boxed: Background Image', 'bunyad-admin'),
		'name'    => 'bg_image', // will be _bunyad_image
		'desc'    => esc_html__('SmartMag can use an image as body background in boxed layout. Note: It is not a repeating pattern. A large photo is to be used as background.', 'bunyad-admin'),
		'type'    => 'upload',
		'options' => [
			'type'         => 'image',
			'title'        => esc_html__('Upload This Picture', 'bunyad-admin'), 
			'button_label' => esc_html__('Upload', 'bunyad-admin'),
			'insert_label' => esc_html__('Use as Background', 'bunyad-admin'),
			// 'media_type'   => 'id',
		],
		'value' => '' // default
	];
}

return $options;