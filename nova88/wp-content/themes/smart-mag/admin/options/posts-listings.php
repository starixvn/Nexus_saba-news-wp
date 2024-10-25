<?php
/**
 * Post & Listings Option
 */

$options = is_array($options) ? $options : [];

/**
 * General / Shared Settings.
 */
$fields_common = [
	[
		'name'    => 'post_format_icons',
		'label'   => esc_html__('Post Format Icons', 'bunyad-admin'),
		'desc'    => esc_html__('For archives only. Post format icons (video, gallery etc.) are displayed over image. For home, set per block in pagebuilder. Small posts have separate setting.', 'bunyad-admin'),
		'value'   => 1,
		'type'    => 'toggle',
		'style'   => 'inline-sm',
	],

	[
		'name'    => 'css_loops_media_radius',
		'label'   => esc_html__('Featured Image Roundness', 'bunyad-admin'),
		'desc'    => esc_html__('Set custom border radius for featured images.', 'bunyad-admin'),
		'value'   => 0,
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'   => [
			'.l-post' => ['props' => ['--media-radius' => '%spx']],
		],
	],

	[
		'name'    => 'loops_media_style_shadow',
		'label'   => esc_html__('Featured Image Shadows', 'bunyad-admin'),
		'desc'    => esc_html__('Add a shadow on featured images.', 'bunyad-admin'),
		'value'   => 0,
		'type'    => 'toggle',
		'style'   => 'inline-sm',
	],

	[
		'name'    => 'post_formats_pos',
		'label'   => esc_html__('Formats Default Position', 'bunyad-admin'),
		'desc'    => '',
		'value'   => 'center',
		'type'    => 'select',
		'style'   => 'inline-sm',
		'options' => $_common['post_format_pos_options']
	],
	

	[
		'name'    => 'post_read_more_text',
		'label'   => esc_html__('Read More Text', 'bunyad-admin'),
		'value'   => '',
		'placeholder' => esc_html__('Read More', 'bunyad'),
		'desc'    => esc_html__('Default text used on read more buttons and links.', 'bunyad-admin'),
		'type'    => 'text',
		// 'style'   => 'inline-sm',
	],

	[
		'name'    => 'loops_reviews',
		'label'   => esc_html__('Reviews Style', 'bunyad-admin'),
		'desc'    => esc_html__('Style for showing review numbers in posts blocks and listings.', 'bunyad-admin'),
		'value'   => 'stars',
		'type'    => 'select',
		// 'style'   => 'inline-sm',
		'options' => $_common['reviews_options'],
	],
// 	[
// 		'name'  => '_n_listings_meta',
// 		'type'  => 'message',
// 		'label' => 'Post Meta Settings',
// 		'text'  => 'While some post meta settings are available locally, most are in the global settings. You can find them in 
// 			<a href="#" class="focus-link is-with-nav" data-section="bunyad-posts-global">Global Posts Settings</a>',
// 		'style' => 'message-info',
// 	],

	/**
	 * Group: Design General
	 */
	[
		'name'      => '_g_listings_design',
		'label'     => esc_html__('Design: General', 'bunyad-admin'),
		'desc'      => 'Most override settings from global <a href="#" class="focus-link is-with-nav" data-section="sphere-style">Colors & Fonts</a>.',
		'type'      => 'group',
		'style'     => 'collapsible',
		'collapsed' => false,

	],

		[
			'name'  => 'css_titles_spacing',
			'value' => '',
			'label' => esc_html__('Post Titles Space', 'bunyad-admin'),
			'desc'  => esc_html__('Titles top/bottom spacing. Does not apply to overlay or featured grid blocks.', 'bunyad-admin'),
			'type'  => 'number',
			'style' => 'inline-sm',
			'css'   => [
				'vars' => ['props' => ['--p-title-space' => '%spx']],
			],
			'group' => '_g_listings_design',
		],
		[
			'name'  => 'css_excerpts_margin_top',
			'value' => '',
			'label' => esc_html__('Excerpts Top Space', 'bunyad-admin'),
			'type'  => 'number',
			'style' => 'inline-sm',
			'css'   => [
				'vars' => ['props' => ['--excerpt-mt' => '%spx']],
			],
			'group' => '_g_listings_design',
		],
		[
			'name'  => 'css_excerpts_color',
			'value' => '',
			'label' => esc_html__('Excerpts Color', 'bunyad-admin'),
			'type'  => 'color',
			'style' => 'inline-sm',
			'css'   => [
				'vars' => ['props' => ['--c-excerpts' => '%s']],
			],
			'group' => '_g_listings_design',
		],
		[
			'name'  => 'css_excerpts_color_sd',
			'value' => '',
			'label' => esc_html__('Dark: Excerpts Color', 'bunyad-admin'),
			'type'  => 'color',
			'style' => 'inline-sm',
			'css'   => [
				'.s-dark' => ['props' => ['--c-excerpts' => '%s']],
			],
			'group' => '_g_listings_design',
		],
		[
			'name'       => 'css_excerpts_typo',
			'value'      => '',
			'label'      => esc_html__('Excerpts Typography', 'bunyad-admin'),
			'desc'       => '',
			'type'       => 'group',
			'group_type' => 'typography',
			'style'      => 'edit',
			'css'        => '.l-post .excerpt',
			'controls_options' => [
				'size' => [
					'css' => ['vars' => ['props' => ['--excerpt-size' => '%spx']]],
				]
			],
			'group'      => '_g_listings_design',
		],
		[
			'name'  => 'css_post_titles_color',
			'value' => '',
			'label' => esc_html__('Post Titles Hover', 'bunyad-admin'),
			'type'  => 'color',
			'style' => 'inline-sm',
			'css'   => [
				'.post-title a' => ['props' => ['--c-a-hover' => '%s']],
			],
			'group' => '_g_listings_design',
		],
		[
			'name'  => 'css_post_titles_color_sd',
			'value' => '',
			'label' => esc_html__('Dark: Post Titles Hover', 'bunyad-admin'),
			'type'  => 'color',
			'style' => 'inline-sm',
			'css'   => [
				'.s-dark .post-title a' => ['props' => ['--c-a-hover' => '%s']],
			],
			'group' => '_g_listings_design',
		],

];

// Common fields for listings.
$_common['tpl_listing'] = include get_theme_file_path('admin/options/listings/common.php');

// Get all the fields from files.
$_fields     = [];
$field_files = [
	'read-more',
	'load-more',
	'block-headings',
	'cat-labels',
	'grid',
	'overlay',
	'list',
	'small',
	'large',
	'feat-grids',
];

foreach ($field_files as $key) {
    $_fields[$key] = include get_theme_file_path('admin/options/listings/' . $key . '.php');
}

/**
 * Sections setup.
 */
$sections = [
	[
		'id'     => 'posts-listings-message',
		'title'  => '',
		'desc'   => 'The settings in this section apply to any of the home listings, posts widgets, and listings in categories & archives. <strong>Note:</strong> Most of these settings can be overriden in Elementor when creating homepage.',
		'type'   => 'message',
		'fields' => [],
	],
	[
		'id'     => 'posts-listings-common',
		'title'  => esc_html__('Common Shared Settings', 'bunyad-admin'),
		'desc'   => 'The settings in this section apply to any of the home listings, posts blocks, and listings in categories & archives.',
		'fields' => $fields_common,
	],
	[
		'id'     => 'posts-listings-cat-labels',
		'title'  => esc_html__('Category Labels / Overlays', 'bunyad-admin'),
		'desc'   => '',
		'fields' => $_fields['cat-labels'],
	],
	[
		'id'     => 'posts-listings-headings',
		'title'  => esc_html__('Block Heading Styles', 'bunyad-admin'),
		'desc'   => '',
		'fields' => $_fields['block-headings'],
	],
	[
		'id'     => 'posts-listings-read-more',
		'title'  => esc_html__('Read More Styles', 'bunyad-admin'),
		'desc'   => '',
		'fields' => $_fields['read-more'],
	],
	[
		'id'     => 'posts-listings-load-button',
		'title'  => esc_html__('Load More Style', 'bunyad-admin'),
		'desc'   => '',
		'fields' => $_fields['load-more'],
	],
	[
		'id'     => 'posts-listings-grid',
		'title'  => esc_html__('Layout: Grid Posts', 'bunyad-admin'),
		'desc'   => '',
		'fields' => $_fields['grid'],
	],
	[
		'id'     => 'posts-listings-list',
		'title'  => esc_html__('Layout: List Posts', 'bunyad-admin'),
		'desc'   => '',
		'fields' => $_fields['list'],
	],
	[
		'id'     => 'posts-listings-overlay',
		'title'  => esc_html__('Layout: Overlays', 'bunyad-admin'),
		'desc'   => '',
		'fields' => $_fields['overlay'],
	],
	[
		'id'     => 'posts-listings-large',
		'title'  => esc_html__('Layout: Large / Classic', 'bunyad-admin'),
		'desc'   => '',
		'fields' => $_fields['large'],
	],
	[
		'id'     => 'posts-listings-small',
		'title'  => esc_html__('Layout: Small Posts & Legacy Widgets', 'bunyad-admin'),
		'desc'   => '',
		'fields' => $_fields['small'],
	],
	[
		'id'     => 'posts-listings-feat-grids',
		'title'  => esc_html__('Layout: Featured Grids', 'bunyad-admin'),
		'desc'   => '',
		'fields' => $_fields['feat-grids'],
	],

];

$options['posts-listings'] = [
	'id'       => 'posts-listings',
	'title'    => esc_html__('Blocks & Listings', 'bunyad-admin'),
	'sections' => $sections,
	'desc'     => '',
];

return $options;