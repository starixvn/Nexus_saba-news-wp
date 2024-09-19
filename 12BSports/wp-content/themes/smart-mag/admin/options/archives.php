<?php
/**
 * Categories & Archives Options
 */
$custom_archives = [];
if (class_exists('\Sphere\Core\Elementor\Layouts\Module')) {
	$custom_options  = (array) \Sphere\Core\Elementor\Layouts\Module::instance()->get_options('ts-archive');
	$custom_archives = ['' => 'None'] + $custom_options;
}

$custom_loop_desc = sprintf(
	'Create a custom layout from %1$sSmartMag > Custom Layouts%2$s of Archive type.',
	'<a href="'. admin_url('edit.php?post_type=spc-el-layouts') .'" target="_blank">',
	'</a>'
);

$fields = [
	[
		'name'  => '_n_layout_archives',
		'type'  => 'message',
		'label' => '',
		'text'  => 'Most of the Posts Listings setting apply to archives. 
			Check <a href="#" class="focus-link is-with-nav" data-panel="bunyad-posts-listings">Blocks & Listings</a> section.
			Note: You can change styles in Blocks & Listings section. For example, changing Image ratio from Blocks & Listings > Grid will change image ratio in categories, if selected.',
		'style' => 'message-info',
	],
	[
		'name'    => 'archive_sidebar',
		'label'   => esc_html__('Listings Sidebar', 'bunyad-admin'),
		'value'   => '',
		'desc'    => esc_html__('Applies to all type of archives except home.', 'bunyad-admin'),
		'type'    => 'radio',
		'options' => $_common['sidebar_options'],
	],

	[
		'name'    => 'pagination_type',
		'label'   => esc_html__('Pagination Type', 'bunyad-admin'),
		'desc'    => esc_html__('For archives only. Does not apply to home built in Elementor.', 'bunyad-admin'),
		'value'   => 'numbers',
		'type'    => 'radio',
		'options' => $_common['pagination_options'],
	],

	[
		'name'    => 'category_loop',
		'label'   => esc_html__('Category Listing Style', 'bunyad-admin'),
		'value'   => 'grid-2',
		'desc'    => '',
		'type'    => 'select',
		'options' => $_common['archive_loop_options'] + [
			'custom' => esc_html__('- Custom Layout -', 'bunyad-admin')
		],
	],

	[
		'name'    => 'category_loop_custom',
		'label'   => esc_html__('Category Custom Layout', 'bunyad-admin'),
		'value'   => '',
		'desc'    => $custom_loop_desc,
		'type'    => 'select',
		'classes' => 'sep-bottom',
		'options' => $custom_archives,
		'context' => [['key' => 'category_loop', 'value' => 'custom']]
	],

	[
		'name'    => 'archive_loop',
		'label'   => esc_html__('Archive Listing Style', 'bunyad-admin'),
		'value'   => 'grid-2',
		'desc'    => '',
		'type'    => 'select',
		'options' => $_common['archive_loop_options'] + [
			'custom' => esc_html__('- Custom Layout -', 'bunyad-admin')
		],
	],
	[
		'name'    => 'archive_loop_custom',
		'label'   => esc_html__('Archive Custom Layout', 'bunyad-admin'),
		'value'   => '',
		'desc'    => $custom_loop_desc,
		'type'    => 'select',
		'classes' => 'sep-bottom',
		'options' => $custom_archives,
		'context' => [['key' => 'archive_loop', 'value' => 'custom']]
	],
	[
		'name'    => 'cpt_loop_custom',
		'label'   => esc_html__('Custom Post Types Layout', 'bunyad-admin'),
		'value'   => '',
		'desc'    => $custom_loop_desc,
		'type'    => 'select',
		'classes' => 'sep-bottom',
		'options' => $custom_archives,
		'context' => [['key' => 'archive_loop', 'value' => 'custom']]
	],

	[
		'name'    => 'author_loop',
		'label'   => esc_html__('Authors Listing Style', 'bunyad-admin'),
		'value'   => 'large',
		'desc'    => '',
		'type'    => 'select',
		'options' => $_common['archive_loop_options'] + [
			'custom' => esc_html__('- Custom Layout -', 'bunyad-admin')
		],
	],

	[
		'name'    => 'author_loop_custom',
		'label'   => esc_html__('Authors Custom Layout', 'bunyad-admin'),
		'value'   => '',
		'desc'    => $custom_loop_desc,
		'type'    => 'select',
		'classes' => 'sep-bottom',
		'options' => $custom_archives,
		'context' => [['key' => 'author_loop', 'value' => 'custom']]
	],
	
	[
		'name'    => 'search_loop',
		'label'   => esc_html__('Search Listing Style', 'bunyad-admin'),
		'value'   => 'grid-2',
		'desc'    => '',
		'type'    => 'select',
		'options' => $_common['archive_loop_options']
	],
	
	[
		'name'  => 'archive_descriptions',
		'value' => 1,
		'label' => esc_html__('Show Category Descriptions', 'bunyad-admin'),
		'desc'  => 'Show category / archive description text when available.',
		'type'  => 'checkbox'
	],

	[
		'name'  => 'archive_title',
		'value' => 1,
		'label' => esc_html__('Show Archive Title', 'bunyad-admin'),
		'desc'  => 'Applies to categories, tags and taxonomies.',
		'classes' => 'sep-top',
		'type'  => 'toggle',
		'style' => 'inline-sm',
	],

	[
		'name'  => 'archive_title_format',
		'label' => esc_html__('Archive Title Format', 'bunyad-admin'),
		'value' => esc_html__('Browsing: %s', 'bunyad'),
		'desc'  => '',
		'type'  => 'text'
	],
];

$fields_featured = [
	[
		'name'  => '_g_category_featured',
		'label' => esc_html__('Categories Featured Area', 'bunyad-admin'),
		'desc'  => esc_html__('Can be overriden per-category by editing a category.', 'bunyad-admin'),
		'type'  => 'group',
		'style' => 'collapsible',
	],

	[
		'name'    => 'category_featured_skip',
		'label'   => esc_html__('Skip Featured Posts In Feed', 'bunyad-admin'),
		'desc'    => esc_html__('In the category feed, exclude posts already displayed in the featured area.', 'bunyad-admin'),
		'value'   => 1,
		'type'    => 'checkbox',
		'group'   => '_g_category_featured',
	],

	[
		'name'    => 'category_slider',
		'label'   => esc_html__('Featured Area', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'select',
		'options' => [
			''         => esc_html__('Disabled', 'bunyad-admin'),
			'latest'   => esc_html__('Show Latest Posts/Use a tag filter', 'bunyad-admin'),
			'default'  => esc_html__('Show Posts Marked for Featured Slider', 'bunyad-admin'),
		],
		'group' => '_g_category_featured',
	],

	[
		'name'    => 'category_slider_type',
		'label'   => esc_html__('Featured Style', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'select',
		'options' => $_common['featured_type_options'],
		'group'   => '_g_category_featured',
	],

	[
		'name'    => 'category_slider_slides',
		'label'   => esc_html__('Enable Slider', 'bunyad-admin'),
		'desc'    => esc_html__('Uses a static grid by default. Enabling a slider changes mobile view as well.', 'bunyad-admin'),
		'value'   => 0,
		'type'    => 'toggle',
		'style'   => 'inline-sm',
		'group'   => '_g_category_featured',
	],

	[
		'name'    => 'category_slider_width',
		'label'   => esc_html__('Featured Width', 'bunyad-admin'),
		'desc'    => '',
		'value'   => 'container',
		'type'    => 'select',
		'options' => [
			'container' => esc_html__('Container', 'bunyad-admin'),
			'viewport'  => esc_html__('Full Browser Width', 'bunyad-admin'),
		],
		'group'   => '_g_category_featured',
	],

	[
		'name'    => 'category_slider_tags',
		'label'   => esc_html__('Featured Filter by Tag(s)', 'bunyad-admin'),
		'desc'    => esc_html__('Enter a tag slug, or multiple tag slugs separated by comma, to limit posts from.', 'bunyad-admin'),
		'value'   => '',
		'type'    => 'text',
		'group' => '_g_category_featured',
	],

	[
		'name'    => 'category_slider_number',
		'label'   => esc_html__('Featured Posts', 'bunyad-admin'),
		'desc'    => esc_html__('Number of posts for featured area.', 'bunyad-admin'),
		'value'   => 5,
		'type'    => 'number',
		'group' => '_g_category_featured',
	],

	[
		'name'  => 'css_category_slider_gap',
		'value' => '',
		'label' => esc_html__('Grid Gap', 'bunyad-admin'),
		'desc'  => '',
		'type'  => 'number',
		'style' => 'inline-sm',
		'css'   => [
			'.category .feat-grid' => ['props' => ['--grid-gap' => '%dpx']],
		],
		'group' => '_g_category_featured',
	],

	[
		'name'        => 'css_category_slider_custom_ratio',
		'label'       => esc_html__('Main Image Ratio', 'bunyad-admin'),
		'value'       => '',
		'desc'        => 'Calculated using width/height.',
		'type'        => 'number',
		'style'       => 'inline-sm',
		'input_attrs' => ['min' => 0.25, 'max' => 3.5, 'step' => .1],
		'css'         => [
			'.category .feat-grid' => ['props' => ['--main-ratio' => '%s']]
		],
		'group' => '_g_category_featured',
	],


];

$fields = array_merge($fields, $fields_featured);

$options['archives'] = [
	'sections' => [[
		'id'     => 'archives',
		'title'  => esc_html__('Categories & Archives', 'bunyad-admin'),
		'fields' => $fields
	]]
];