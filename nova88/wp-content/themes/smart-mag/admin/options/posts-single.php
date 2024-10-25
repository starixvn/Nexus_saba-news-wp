<?php
/**
 * Single Posts Options
 */

$_common['share_top_styles'] = [
	'b1'      => esc_html__('Default / Background Colors', 'bunyad-admin'),
	'b3'      => esc_html__('Light Style', 'bunyad-admin'),
	'circles' => esc_html__('Small Circles', 'bunyad-admin'),
	'b3-circles' => esc_html__('Light Circles', 'bunyad-admin'),
	'b4'      => esc_html__('Simple Icons', 'bunyad-admin'),
];

$_common['share_top_locations'] = [
	'' => esc_html__('Default', 'bunyad-admin'),
	'meta-right' => esc_html__('Post Meta Right', 'bunyad-admin'),
];

$fields_general = [
	[
		'name'    => 'post_layout_template',
		'label'   => esc_html__('Default Post Style', 'bunyad-admin'),
		'value'   => 'modern',
		'type'    => 'radio',
		'options' => $_common['post_style_options'],
	],

	[
		'name'  => 'post_layout_spacious',
		'label' => esc_html__('Spacious / Narrow Style?', 'bunyad-admin'),
		'value' => 1,
		'desc'  => esc_html__('Enable to add extra left/right spacing to text to create a dynamic spacious feel. Especially great when used with Full Width.', 'bunyad-admin'),
		'type'  => 'toggle',
		'style' => 'inline-sm',
	],

	[
		'name'    => 'single_sidebar',
		'label'   => esc_html__('Single Post/Page Sidebar', 'bunyad-admin'),
		'desc'    => esc_html__('Default is from Main Layout settings. This setting can also be changed per post or page.', 'bunyad-admin'),
		'value'   => '',
		'type'    => 'select',
		'options' => [
			''      => esc_html__('Default / Global', 'bunyad-admin'),
			'none'  => esc_html__('No Sidebar', 'bunyad-admin'),
			'right' => esc_html__('Right Sidebar', 'bunyad-admin'),
		],
	],

	[
		'name'    => 'single_featured_crop',
		'label'   => esc_html__('Crop Featured Image', 'bunyad-admin'),
		'value'   => 1,
		'desc'    => esc_html__('Crop featured image for consistent sizing. Does not apply to Cover and Creative style.', 'bunyad-admin'),
		'type'    => 'toggle',
		'style'   => 'inline-sm',
		'classes' => 'sep-top',
	],

	[
		'name'    => 'single_featured_shadow',
		'label'   => esc_html__('Featured Image Shadow', 'bunyad-admin'),
		'value'   => 0,
		'desc'    => esc_html__('Add a shadow on featured images.', 'bunyad-admin'),
		'type'    => 'toggle',
		'style'   => 'inline-sm',
	],

	[
		'name'    => 'css_single_media_radius',
		'label'   => esc_html__('Featured Image Roundness', 'bunyad-admin'),
		'desc'    => esc_html__('Set custom border radius for featured images.', 'bunyad-admin'),
		'value'   => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'   => [
			'.single-featured .featured, .the-post-header .featured' => [
				'props' => [
					'border-radius' => '%spx',
					// Required for safari.
					'--media-radius' => '%spx',
					'overflow' => 'hidden',
				]
			],
		],
	],

	[
		'name'    => 'single_featured_ratio',
		'label'   => esc_html__('Image Aspect Ratio', 'bunyad-admin'),
		'desc'    => 'Note: Does not apply to Cover and Creative style.',
		'value'   => '',
		'type'    => 'select',
		'style'   => 'inline-sm',
		'options' => $_common['ratio_options'],
		'context' => [['key' => 'single_featured_crop', 'value' => 1]],
	],
	[
		'name'        => 'single_featured_ratio_custom',
		'label'       => esc_html__('Custom Ratio', 'bunyad-admin'),
		'value'       => '',
		'desc'        => 'Calculated using width/height.',
		'type'        => 'number',
		'style'       => 'inline-sm',
		'input_attrs' => ['min' => 0.25, 'max' => 3.5, 'step' => .1],
		'css'         => [
			'.single .featured .ratio-is-custom' => ['props' => ['padding-bottom' => 'calc(100% / %s)']]
		],
		'transport' => 'refresh',
		'context'   => [
			['key' => 'single_featured_ratio', 'value' => 'custom'],
			['key' => 'single_featured_crop', 'value' => 1],
		],
	],
	[
		'name'    => 'single_featured_height_enable',
		'label'   => esc_html__('Use Custom Height', 'bunyad-admin'),
		'desc'    => 'Setting custom height will ignore aspect ratio.',
		'value'   => 0,
		'type'    => 'toggle',
		'style'   => 'inline-sm',
		'context' => [['key' => 'single_featured_crop', 'value' => 1]],
	],
	[
		'name'    => 'css_single_featured_height',
		'label'   => esc_html__('Image Custom Height', 'bunyad-admin'),
		'desc'    => 'Note: Does not apply to Cover and Creative style.',
		'value'   => [],
		'type'    => 'number',
		'style'   => 'inline-sm',
		'devices' => true,
		'css'         => [
			'.single .featured .image-link' => [
				'props' => [
					'padding-bottom' => 'initial',
					'height'         => '%dpx'
				]
			]
		],
		'context' => [
			['key' => 'single_featured_crop', 'value' => 1],
			['key' => 'single_featured_height_enable', 'value' => 1]
		],
	],

	[
		'name'    => 'single_schema_article',
		'value'   => 1,
		'label'   => esc_html__('Enable Article Schema', 'bunyad-admin'),
		'desc'    => esc_html__('Article schema data can be used by Google to qualify for inclusion in Google News etc. Disable if using a Rich Schema plugin.', 'bunyad-admin'),
		'classes' => 'sep-bottom',
		'style'   => 'inline-sm',
		'type'    => 'toggle'
	],

	[
		'name'        => 'single_section_head_style',
		'label'       => esc_html__('Sections Heading Style', 'bunyad-admin'),
		'desc'        => esc_html__('Applies to sections like related posts and comments.', 'bunyad-admin'),
		'value'       => 'a',
		'type'        => 'select',
		'options'     => $_common['block_headings'],
	],

	[
		'name'        => 'single_section_head_tag',
		'label'       => esc_html__('Sections Heading Tag', 'bunyad-admin'),
		'desc'        => '',
		'value'       => 'h4',
		'type'        => 'select',
		'style'       => 'inline-sm',
		'classes'     => 'sep-bottom',
		'options'     => $_common['heading_tags'],
	],

	/**
	 * Group: Post Meta
	 */
	[
		'name'      => '_g_single_meta',
		'label'     => esc_html__('Post Meta', 'bunyad-admin'),
		'desc'      => 'Does not apply to Classic Post Style.',
		'type'      => 'group',
		// 'collapsed' => false,
		'style'     => 'collapsible',
	],
		[
			'name'    => 'post_meta_single_global',
			'label'   => esc_html__('Post Meta: Global Items', 'bunyad-admin'),
			'desc'    => 'Use global settings for post meta.',
			'value'   => 0,
			'type'    => 'toggle',
			'style'   => 'inline-sm',
			'group'   => '_g_single_meta',
		],

		[
			'name'    => 'post_meta_single_above',
			'label'   => esc_html__('Items Above Title', 'bunyad-admin'),
			'desc'    => '',
			'value'   => ['cat'],
			'type'    => 'checkboxes',
			'options' => $_common['meta_options'],
			// Not a global style, specific to checkboxes.
			'style'   => 'sortable',
			'group'   => '_g_single_meta',
			'context' => [['key' => 'post_meta_single_global', 'value' => 0]]
		],

		[
			'name'    => 'post_meta_single_below',
			'label'   => esc_html__('Items Below Title', 'bunyad-admin'),
			'desc'    => '',
			'value'   => ['author', 'date', 'updated', 'comments', 'read_time'],
			'type'    => 'checkboxes',
			'options' => $_common['meta_options'],
			// Not a global style, specific to checkboxes.
			'style'   => 'sortable',
			'group'   => '_g_single_meta',
			'context' => [['key' => 'post_meta_single_global', 'value' => 0]]
		],

		[
			'name'  => 'post_meta_single_author_img',
			'label' => esc_html__('Show Author Image', 'bunyad-admin'),
			'desc'  => '',
			'value' => 1,
			'type'  => 'toggle',
			'style' => 'inline-sm',
			'group' => '_g_single_meta',
		],

		[
			'name'  => 'post_meta_single_all_cats',
			'label' => esc_html__('All Categories in Meta', 'bunyad-admin'),
			'value' => 0,
			'desc'  => esc_html__('If unchecked, only the Primary Category is displayed.', 'bunyad-admin'),
			'type'  => 'toggle',
			'style' => 'inline-sm',
			'group' => '_g_single_meta',
		],

		[
			'name'  => 'post_meta_single_cat_labels',
			'label' => esc_html__('Category Style', 'bunyad-admin'),
			'desc'  => esc_html__('Does not apply to Classic or Cover post style.', 'bunyad-admin'),
			'value' => 'labels',
			'type'  => 'select',
			'style' => 'inline-sm',
			'options' => [
				'labels' => esc_html__('Label/Badge', 'bunyad-admin'),
				''       => esc_html__('Normal Text', 'bunyad-admin'),
			],
			'group' => '_g_single_meta',
		],

		[
			'name'    => 'post_meta_single_labels',
			'label'   => esc_html__('Text Labels', 'bunyad-admin'),
			'desc'    => '',
			'value'   => ['by', 'comments', 'views'],
			'type'    => 'checkboxes',
			'options' => [
				// 'in' => '"In" before category',
				'by'       => '"By" before author',
				'views'    => '"Views" for count',
				'comments' => '"Comments" for count'
			],
			'group' => '_g_single_meta',
		],

		[
			'name'       => 'css_post_meta_single_typo',
			'label'      => esc_html__('Typography', 'bunyad-admin'),
			'desc'       => '',
			'value'      => '',
			'type'       => 'group',
			'group_type' => 'typography',
			'style'      => 'edit',
			'controls'   => ['size', 'weight', 'line_height', 'transform'],
			'css'        => '.post-meta-single .meta-item, .post-meta-single .text-in',
			'group'      => '_g_single_meta',
		],

		[
			'name'  => '_n_single_post_meta',
			'type'  => 'message',
			'label' => '',
			'text'  => 'There are many more post meta design settings that apply to all post meta. See <a href="#" class="focus-link is-with-nav" data-section="bunyad-posts-global">Global Posts Settings > Meta Design</a> section.',
			'style' => 'message-info',
			'group' => '_g_single_meta',
		],

	[
		'name'  => 'single_tags',
		'label' => esc_html__('Show Post Tags', 'bunyad-admin'),
		'value' => 1,
		'desc'  => '',
		'type'  => 'toggle',
		'style' => 'inline-sm'
		// 'classes' => 'sep-top',
	],

	[
		'name'  => 'show_featured',
		'label' => esc_html__('Show Featured Image Area', 'bunyad-admin'),
		'value' => 1,
		'desc'  => esc_html__('Disable to stop displaying the featured media in large posts. Can also be set per post while adding/edit a post.', 'bunyad-admin'),
		'type'  => 'toggle',
		'style' => 'inline-sm'
	],

	[
		'name'  => 'single_featured_link',
		'label' => esc_html__('Linked Featured Image', 'bunyad-admin'),
		'value' => 1,
		'desc'  => esc_html__('Will open the featured image in the lightbox when clicked.', 'bunyad-admin'),
		'type'  => 'toggle',
		'style' => 'inline-sm'
	],

	[
		'name'  => 'single_navigation',
		'label' => esc_html__('Show Next/Previous Post', 'bunyad-admin'),
		'desc'  => esc_html__('Enabling this will add a Previous and Next post link in the single post page.', 'bunyad-admin'),
		'value' => 0,
		'type'  => 'toggle',
		'style' => 'inline-sm'
	],


	/**
	 * Group: Comments
	 */
	[
		'name'  => '_g_comments',
		'label' => esc_html__('Comments Area', 'bunyad-admin'),
		'type'  => 'group',
		'style' => 'collapsible',
		'collapsed' => true
	],
		[
			'name'       => 'single_comments',
			'label'      => esc_html__('Enable Comments Area', 'bunyad-admin'),
			'value'      => 1,
			'desc'       => '',
			'type'       => 'toggle',
			'style'      => 'inline-sm',
			'group'      => '_g_comments',
		],

		[
			'name'      => 'single_comments_button',
			'label'     => esc_html__('Show Comments Button', 'bunyad-admin'),
			'desc'      => 'Hide comments area by default and show a button instead.',
			'value'     => 0,
			'type'      => 'toggle',
			'style'     => 'inline-sm',
			'condition' => [['key' => 'single_comments', 'value' => '1']],
			'group'     => '_g_comments',
		],
		[
			'name'       => 'css_comments_typo',
			'label'      => esc_html__('Text Typography', 'bunyad-admin'),
			'value'      => '',
			'desc'       => '',
			'type'       => 'group',
			'group_type' => 'typography',
			'style'      => 'edit',
			'controls'   => ['size', 'line_height', 'spacing'],
			'css'        => '.comments-area .comment-content',
			'group'      => '_g_comments',
		],
		[
			'name'       => 'css_comments_meta_size',
			'label'      => esc_html__('Meta Size', 'bunyad-admin'),
			'value'      => '',
			'desc'       => '',
			'type'       => 'number',
			'devices'    => true,
			'css'        => [
				'.comments-area .comment-meta' => ['props' => ['font-size' => '%spx']],
			],
			'style'      => 'inline-sm',
			'group'      => '_g_comments',
		],

	// [
	//     'name'  => '_g_single_content',
	//     'label' => esc_html__('Content Colors/Style', 'bunyad-admin'),
	//     'type'  => 'group',
	//     'style' => 'collapsible',
	// ],

	/**
	 * Group: Author Box
	 */
	[
		'name'  => '_g_author_box',
		'label' => esc_html__('Author Box', 'bunyad-admin'),
		'type'  => 'group',
		'style' => 'collapsible',
	],
	[
		'name'  => 'author_box',
		'label' => esc_html__('Show Author Box', 'bunyad-admin'),
		'value' => 1,
		'desc'  => '',
		'type'  => 'checkbox',
		'group' => '_g_author_box',
	],
	[
		'name'       => 'author_box_text_typo',
		'label'      => esc_html__('Bio Typography', 'bunyad-admin'),
		'desc'       => '',
		'value'      => '',
		'type'       => 'group',
		'group_type' => 'typography',
		'style'      => 'edit',
		'css'        => '.author-box .bio',
		'controls'   => ['family', 'size', 'weight', 'line_height'],
		'group'      => '_g_author_box',
	],
	// [
	// 	'name'    => 'author_box_style',
	// 	'label'   => esc_html__('Author Box Style', 'bunyad-admin'),
	// 	'value'   => '',
	// 	'type'    => 'select',
	// 	'options' => [
	// 		''             => esc_html__('Default / Auto', 'bunyad-admin'),
	// 		'author-box'   => esc_html__('Modern Style', 'bunyad-admin'),
	// 		'author-box-b' => esc_html__('Classic Style', 'bunyad-admin'),
	// 	],
	// 	'context' => [['key' => 'author_box', 'value' => 1]],
	// 	'group'   => '_g_author_box',
	// ],

];

$fields_design = [
	[
		'name'    => 'css_single_title_typo',
		'label'   => esc_html__('Post Title Typography', 'bunyad-admin'),
		'desc'       => 'Note: For title size, go back and to specific post style, Post Style: Modern for example.',
		'value'      => '',
		'type'       => 'group',
		'group_type' => 'typography',
		'style'      => 'edit',
		'controls'   => ['family', 'weight', 'line_height', 'transform', 'spacing'],
		'css'        => '.the-post-header .post-meta .post-title',
	],
	[
		'name'       => 'css_single_body_typo',
		'label'      => esc_html__('Body Typography', 'bunyad-admin'),
		'value'      => '',
		'desc'       => '',
		'type'       => 'group',
		'group_type' => 'typography',
		'style'      => 'edit',
		'css'        => '.entry-content',
		'controls'   => ['family', 'size', 'weight', 'line_height', 'spacing'],
		'group'      => '_g_post_content_body',
	],
	[
		'name'       => 'css_single_h_typo',
		'label'      => esc_html__('H1-H6 Typography', 'bunyad-admin'),
		'value'      => '',
		'desc'       => '',
		'type'       => 'group',
		'group_type' => 'typography',
		'style'      => 'edit',
		'css'        => '.post-content h1, .post-content h2, .post-content h3, .post-content h4, .post-content h5, .post-content h6',
		'controls'   => ['family', 'weight', 'line_height', 'spacing', 'transform'],
		'group'      => '_g_post_content_body',
	],
	[
		'name'  => 'css_single_body_color',
		'value' => '',
		'label' => esc_html__('Body/Text Color', 'bunyad-admin'),
		'desc'  => '',
		'type'  => 'color',
		'style' => 'inline-sm',
		'css'   => [
			'.site-s-light .entry-content' => ['props' => ['color' => '%s']],
		],
		'group' => '_g_post_content_body',
	],
	[
		'name'  => 'css_single_body_color_sd',
		'value' => '',
		'label' => esc_html__('Dark: Text Color', 'bunyad-admin'),
		'desc'  => '',
		'type'  => 'color',
		'style' => 'inline-sm',
		'css'   => [
			'.s-dark .entry-content' => ['props' => ['color' => '%s']],
		],
		'group' => '_g_post_content_body',
	],

	[
		'name'  => 'css_single_h_color',
		'value' => '',
		'label' => esc_html__('H1-H6 Heading Color', 'bunyad-admin'),
		'desc'  => esc_html__('Does not affect post/page title. Only the h1-h6 within post body.', 'bunyad-admin'),
		'type'  => 'color',
		'style' => 'inline-sm',
		'css'   => [
			'.site-s-light .post-content' => ['props' => ['--c-headings' => '%s']],
		],
		'group' => '_g_post_content_body',
	],
	[
		'name'  => 'css_single_h_color_sd',
		'value' => '',
		'label' => esc_html__('Dark: Headings Color', 'bunyad-admin'),
		'desc'  => '',
		'type'  => 'color',
		'style' => 'inline-sm',
		'css'   => [
			'.s-dark .post-content' => ['props' => ['--c-headings' => '%s']],
		],
		'group' => '_g_post_content_body',
	],

	[
		'name'  => 'css_single_a_color',
		'value' => '',
		'label' => esc_html__('Links Color', 'bunyad-admin'),
		'desc'  => '',
		'type'  => 'color',
		'style' => 'inline-sm',
		'css'   => [
			'.entry-content' => ['props' => ['--c-a' => '%s']],
		],
		'group' => '_g_post_content_body',
	],

	[
		'name'  => 'css_single_a_underline',
		'value' => '',
		'label' => esc_html__('Links Underline', 'bunyad-admin'),
		'desc'  => '',
		'type'  => 'toggle',
		'style' => 'inline-sm',
		'css'   => [
			':where(.entry-content) a' => [
				'props' => [
					'text-decoration' => 'underline',
					'text-underline-offset' => '4px',
					'text-decoration-thickness' => '2px',
				]
			],
		],
		'group' => '_g_post_content_body',
	],

	[
		'name'  => 'css_single_a_color_sd',
		'value' => '',
		'label' => esc_html__('Dark: Links Color', 'bunyad-admin'),
		'desc'  => '',
		'type'  => 'color',
		'style' => 'inline-sm',
		'classes' => 'sep-bottom',
		'css'   => [
			'.s-dark .entry-content' => ['props' => ['--c-a' => '%s']],
		],
		'group' => '_g_post_content_body',
	],


	[
		'name'  => 'css_single_content_gaps',
		'value' => '',
		'label' => esc_html__('Elements Base Gap', 'bunyad-admin'),
		'desc'  => 'Base gap in ems for most elements in body.',
		'type'  => 'number',
		'style' => 'inline-sm',
		'input_attrs' => ['min' => .9, 'max' => 3, 'step' => .1],
		'css'   => [
			'vars' => ['props' => ['--post-content-gaps' => '%sem']],
		],
		'group' => '_g_post_content_body',
	],
	[
		'name'  => 'css_single_p_gap',
		'value' => '',
		'label' => esc_html__('Paragraphs Gap', 'bunyad-admin'),
		'desc'  => 'In ems.',
		'type'  => 'number',
		'input_attrs' => ['min' => .9, 'max' => 3, 'step' => .1],
		'style'   => 'inline-sm',
		'css'   => [
			'.entry-content p' => ['props' => ['--post-content-gaps' => '%sem']],
		],
		'group' => '_g_post_content_body',
	],

	/**
	 * Group: Heading Sizes
	 */
	[
		'name'  => '_g_single_content_headings',
		'label' => esc_html__('Heading Sizes', 'bunyad-admin'),
		'type'  => 'group',
		'style' => 'collapsible',
		'group' => '_g_post_content_body',
	],

	[
		'name'    => 'css_font_post_h1',
		'label'   => esc_html__('Post Body H1', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'slider',
		'devices' => true,
		'classes' => 'sep-top',
		'css'     => [
			'.post-content h1' => ['props' => ['font-size' => '%dpx']],
		],
		'group'   => '_g_single_content_headings',
	],

	[
		'name'    => 'css_font_post_h2',
		'label'   => esc_html__('Post Body H2', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'slider',
		'devices' => true,
		'css'     => [
			'.post-content h2' => ['props' => ['font-size' => '%dpx']],
		],
		'group'   => '_g_single_content_headings',
	],

	[
		'name'    => 'css_font_post_h3',
		'label'   => esc_html__('Post Body H3', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'slider',
		'devices' => true,
		'css'     => [
			'.post-content h3' => ['props' => ['font-size' => '%dpx']],
		],
		'group'   => '_g_single_content_headings',
	],

	[
		'name'    => 'css_font_post_h4',
		'label'   => esc_html__('Post Body H4', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'slider',
		'devices' => true,
		'css'     => [
			'.post-content h4' => ['props' => ['font-size' => '%dpx']],
		],
		'group'   => '_g_single_content_headings',
	],

	[
		'name'    => 'css_font_post_h5',
		'label'   => esc_html__('Post Body H5', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'slider',
		'devices' => true,
		'css'     => [
			'.post-content h5' => ['props' => ['font-size' => '%dpx']],
		],
		'group'   => '_g_single_content_headings',
	],

	[
		'name'    => 'css_font_post_h6',
		'label'   => esc_html__('Post Body H6', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'slider',
		'devices' => true,
		'css'     => [
			'.post-content h6' => ['props' => ['font-size' => '%dpx']],
		],
		'group'   => '_g_single_content_headings',
	],
];

/**
 * Fields: Social Sharing
 */
$fields_social = [
	/**
	 * Group: Sticky Social
	 */
	[
		'name'  => '_g_share_float',
		'label' => esc_html__('Sticky Social Share', 'bunyad-admin'),
		'type'  => 'group',
		'style' => 'collapsible',
	],

	[
		'name'  => 'single_share_float',
		'label' => esc_html__('Enable Sticky Social Buttons', 'bunyad-admin'),
		'value' => 1,
		'desc'  => 'Show floating/sticky social buttons on left of the posts.',
		'type'  => 'toggle',
		'style' => 'inline-sm',
		'group' => '_g_share_float',
	],

	[
		'name'    => 'share_float_label',
		'label'   => esc_html__('Show Share Text', 'bunyad-admin'),
		'desc'    => '',
		'value'   => 1,
		'type'    => 'toggle',
		'style'   => 'inline-sm',
		'context' => [['key' => 'single_share_float', 'value' => 1]],
		'group'   => '_g_share_float',
	],
	[
		'name'    => 'share_float_text',
		'label'   => esc_html__('Share Text', 'bunyad-admin'),
		'value'   => '',
		'placeholder' => esc_html__('Share', 'bunyad'),
		'desc'    => '',
		'type'    => 'text',
		'style'   => 'inline-sm',
		'context' => [
			['key' => 'single_share_float', 'value' => 1],
			['key' => 'share_float_label', 'value' => 1]
		],
		'group'   => '_g_share_float',
	],

	[
		'name'    => 'share_float_style',
		'label'   => esc_html__('Share Style', 'bunyad-admin'),
		'value'   => 'b',
		'type'    => 'select',
		'options' => [
			'a' => esc_html__('A: Squares', 'bunyad-admin'),
			'b' => esc_html__('B: Circles', 'bunyad-admin'),
			'c' => esc_html__('C: Circles BG Color', 'bunyad-admin'),
			'd' => esc_html__('D: Squares BG Color', 'bunyad-admin'),
			'e' => esc_html__('E: Squares Light / Monochrome', 'bunyad-admin'),
		],
		'context' => [['key' => 'single_share_float', 'value' => 1]],
		'group'   => '_g_share_float',
	],

	[
		'name'    => '_n_share_float',
		'type'    => 'message',
		'label'   => '',
		'text'    => 'There are customizations active that may change the look of the selected style. <a href="#" class="preset-reset">Click here</a> to reset them to defaults.',
		'style'   => 'message-alert',
		'classes' => 'bunyad-cz-hidden',
		'group' => '_g_share_float',
	],

	[
		'name'    => 'share_float_services',
		'label'   => esc_html__('Share Services', 'bunyad-admin'),
		'desc'    => '',
		'value'   => ['facebook', 'twitter', 'linkedin', 'pinterest', 'email'],
		'type'    => 'checkboxes',
		'style'   => 'sortable',
		'options' => $_common['social_share_services'],
		'context' => ['control' => ['key' => 'single_share_float', 'value' => 1]],
		'group'   => '_g_share_float',
	],

	[
		'name'       => 'css_share_float_typo',
		'label'      => esc_html__('Share Typography', 'bunyad-admin'),
		'desc'       => '',
		'value'      => '',
		'type'       => 'group',
		'group_type' => 'typography',
		'style'      => 'edit',
		'devices'    => false,
		'controls'   => ['size', 'weight', 'line_height', 'transform'],
		'css'        => '.post-share-float .share-text',
		'context'    => [['key' => 'single_share_float', 'value' => 1]],
		'group'      => '_g_share_float',
	],
	[
		'name'    => 'css_share_float_width',
		'label'   => esc_html__('Services Width', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.post-share-float .service' => ['props' => ['width' => '%spx']],
		],
		'context' => [['key' => 'single_share_float', 'value' => 1]],
		'group' => '_g_share_float',
	],
	[
		'name'    => 'css_share_float_height',
		'label'   => esc_html__('Services Height', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.post-share-float .service' => ['props' => ['height' => '%spx']],
		],
		'context' => [['key' => 'single_share_float', 'value' => 1]],
		'group' => '_g_share_float',
	],
	[
		'name'    => 'css_share_float_spacing',
		'label'   => esc_html__('Spacing Between', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.post-share-float .service' => ['props' => ['margin-bottom' => '%spx']],
		],
		'context' => [['key' => 'single_share_float', 'value' => 1]],
		'group' => '_g_share_float',
	],

	[
		'name'    => 'css_share_float_radius',
		'label'   => esc_html__('Roundness', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.post-share-float .service' => ['props' => ['border-radius' => '%spx']],
		],
		'context' => [['key' => 'single_share_float', 'value' => 1]],
		'group' => '_g_share_float',
	],

	[
		'name'    => 'css_share_float_icon_size',
		'label'   => esc_html__('Icon Size', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.post-share-float .service' => ['props' => ['font-size' => '%spx']],
		],
		'context' => [['key' => 'single_share_float', 'value' => 1]],
		'group' => '_g_share_float',
	],
	[
		'name'    => 'css_share_float_color',
		'label'   => esc_html__('Icons Color', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.post-share-float .service:not(:hover)' => ['props' => ['color' => '%s']],
		],
		'context' => [['key' => 'single_share_float', 'value' => 1]],
		'group' => '_g_share_float',
	],
	[
		'name'    => 'css_share_float_color_sd',
		'label'   => esc_html__('Dark: Icons Color', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.s-dark .post-share-float service:not(:hover)' => ['props' => ['color' => '%s']],
		],
		'context' => [['key' => 'single_share_float', 'value' => 1]],
		'group' => '_g_share_float',
	],

	[
		'name'    => 'css_share_float_color_hov',
		'label'   => esc_html__('Icons Hover', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.post-share-float .service:hover' => ['props' => ['color' => '%s']],
		],
		'context' => [['key' => 'single_share_float', 'value' => 1]],
		'group' => '_g_share_float',
	],
	[
		'name'    => 'css_share_float_color_hov_sd',
		'label'   => esc_html__('Dark: Icons Hover', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.s-dark .post-share-float .service:hover' => ['props' => ['color' => '%s']],
		],
		'context' => [['key' => 'single_share_float', 'value' => 1]],
		'group' => '_g_share_float',
	],

	[
		'name'    => 'css_share_float_bg',
		'label'   => esc_html__('Icons Background', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.post-share-float .service' => ['props' => ['background-color' => '%s']],
		],
		'context' => [['key' => 'single_share_float', 'value' => 1]],
		'group' => '_g_share_float',
	],
	[
		'name'    => 'css_share_float_bg_sd',
		'label'   => esc_html__('Dark: Icons Background', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.post-share-float .service' => ['props' => ['background-color' => '%s']],
		],
		'context' => [['key' => 'single_share_float', 'value' => 1]],
		'group' => '_g_share_float',
	],


	/**
	 * Group: Social Share Top
	 */
	[
		'name'  => '_g_single_share_top',
		'label' => esc_html__('Top Social Share', 'bunyad-admin'),
		'type'  => 'group',
		'desc'  => 'For post styles other than: Cover, Classic.',
		'style' => 'collapsible',
	],

	[
		'name'  => 'single_share_top',
		'label' => esc_html__('Show Share Buttons', 'bunyad-admin'),
		'value' => 1,
		'desc'  => '',
		'type'  => 'toggle',
		'style' => 'inline-sm',
		'group' => '_g_single_share_top',
	],

	[
		'name'  => 'single_share_top_devices',
		'label' => esc_html__('Show On Devices', 'bunyad-admin'),
		'value' => ['all'],
		'desc'  => '',
		'type'  => 'checkboxes',
		// 'style' => 'inline-sm',
		'options' => [
			'all' => esc_html__('Default / All', 'bunyad-admin'),
			'lg'  => esc_html__('Desktop', 'bunyad-admin'),
			'md'  => esc_html__('Tablet', 'bunyad-admin'),
			'sm'  => esc_html__('Phone', 'bunyad-admin'),
		],
		'context' => [['key' => 'single_share_top', 'value' => 1]],
		'group' => '_g_single_share_top',
	],

	[
		'name'    => 'single_share_top_style',
		'label'   => esc_html__('Share Style', 'bunyad-admin'),
		'desc'    => 'Post style "Large Bold" will not use this setting, go back to previous screen and Large section, and change under Bold Style.',
		'value'   => 'b1',
		'type'    => 'select',
		'options' => $_common['share_top_styles'],
		'style'   => 'inline-sm',
		'context' => [['key' => 'single_share_top', 'value' => 1]],
		'group'   => '_g_single_share_top',
	],
	[
		'name'    => 'single_share_top_location',
		'label'   => esc_html__('Share Location', 'bunyad-admin'),
		'desc'    => 'May be overridden under specific post style settings.',
		'value'   => '',
		'type'    => 'select',
		'options' => $_common['share_top_locations'],
		'style'   => 'inline-sm',
		'context' => [['key' => 'single_share_top', 'value' => 1]],
		'group'   => '_g_single_share_top',
	],
	[
		'name'    => '_n_single_share_top',
		'type'    => 'message',
		'label'   => '',
		'text'    => 'There are customizations active that may change the look of the selected style. <a href="#" class="preset-reset">Click here</a> to reset them to defaults.',
		'style'   => 'message-alert',
		'classes' => 'bunyad-cz-hidden',
		'group' => '_g_single_share_top',
	],

	[
		'name'    => 'single_share_top_services',
		'label'   => esc_html__('Share Services', 'bunyad-admin'),
		'value'   => ['facebook', 'twitter', 'pinterest', 'linkedin', 'tumblr', 'email'],
		'desc'    => '',
		'type'    => 'checkboxes',
		'style'   => 'sortable',
		'options' => $_common['social_share_services'],
		'context' => [['key' => 'single_share_top', 'value' => 1]],
		'group'   => '_g_single_share_top',
	],

	[
		'name'    => 'single_share_top_large',
		'label'   => esc_html__('Large Buttons', 'bunyad-admin'),
		'value'   => 3,
		'type'    => 'number',
		'style'   => 'inline-sm',
		'context' => [['key' => 'single_share_top', 'value' => 1]],
		'group'   => '_g_single_share_top',
	],

	[
		'name'    => 'single_share_top_label',
		'label'   => esc_html__('Show Share Text', 'bunyad-admin'),
		'value'   => 0,
		'type'    => 'toggle',
		'style'   => 'inline-sm',
		'context' => [['key' => 'single_share_top', 'value' => 1]],
		'group'   => '_g_single_share_top',
	],
	[
		'name'    => 'css_single_share_top_label_hide_sm',
		'label'   => esc_html__('Hide on Phones', 'bunyad-admin'),
		'value'   => 0,
		'type'    => 'toggle',
		'style'   => 'inline-sm',
		'context' => [
			['key' => 'single_share_top', 'value' => 1],
			['key' => 'single_share_top_label', 'value' => 1]
		],
		'css'     => [
			'.post-share-b .share-text' => ['props' => ['display' => 'none']],
		],
		'group'   => '_g_single_share_top',
	],
	[
		'name'    => 'single_share_top_text',
		'label'   => esc_html__('Share Text', 'bunyad-admin'),
		'value'   => '',
		'placeholder' => esc_html__('Share', 'bunyad'),
		'type'    => 'text',
		'style'   => 'inline-sm',
		'classes' => 'sep-bottom',
		'context' => [
			['key' => 'single_share_top', 'value' => 1],
		],
		'group'   => '_g_single_share_top',
	],

	[
		'name'    => 'css_single_share_top_height',
		'label'   => esc_html__('Buttons Height', 'bunyad-admin'),
		'value'   => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.post-share-b:not(.is-not-global)' => ['props' => ['--service-height' => '%spx']]
		],
		'context' => [['key' => 'single_share_top', 'value' => 1]],
		'group'   => '_g_single_share_top',
	],

	[
		'name'    => 'css_single_share_top_width',
		'label'   => esc_html__('Buttons Width', 'bunyad-admin'),
		'value'   => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.post-share-b:not(.is-not-global)' => [
				'props' => [
					'--service-width' => '%spx',
					'--service-min-width' => 'initial'
				]
			],
		],
		'context' => [['key' => 'single_share_top', 'value' => 1]],
		'group'   => '_g_single_share_top',
	],

	[
		'name'    => 'css_single_share_top_more_width',
		'label'   => esc_html__('Show More Button Width', 'bunyad-admin'),
		'value'   => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.post-share-b:not(.is-not-global) .show-more' => [
				'props' => ['width' => '%spx', 'min-width' => '%spx']
			],
		],
		'context' => [['key' => 'single_share_top', 'value' => 1]],
		'group'   => '_g_single_share_top',
	],

	[
		'name'    => 'css_single_share_top_gap',
		'label'   => esc_html__('Spacing Between', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.post-share-b:not(.is-not-global)' => ['props' => ['--service-gap' => '%spx']],
		],
		'context' => [['key' => 'single_share_top', 'value' => 1]],
		'group' => '_g_single_share_top',
	],

	[
		'name'    => 'css_single_share_top_icon_size',
		'label'   => esc_html__('Icon Size', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.post-share-b:not(.is-not-global) .service i' => ['props' => ['font-size' => '%spx']],
		],
		'context' => [['key' => 'single_share_top', 'value' => 1]],
		'group' => '_g_single_share_top',
	],

	[
		'name'    => 'css_single_share_top_radius',
		'label'   => esc_html__('Roundness', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.post-share-b:not(.is-not-global)' => ['props' => ['--service-b-radius' => '%spx']],
		],
		'context' => [['key' => 'single_share_top', 'value' => 1]],
		'group' => '_g_single_share_top',
	],
	
	[
		'name'    => 'css_single_share_top_label_hide',
		'label'   => esc_html__('Hide Label', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'toggle',
		'style'   => 'inline-sm',
		'css'     => [
			'.post-share-b:not(.is-not-global) .service .label' => ['props' => ['display' => 'none']],
		],
		'context' => [
			['key' => 'single_share_top', 'value' => 1],
			['key' => 'single_share_top_style', 'value' => ['circles', 'b3-circles', 'b4'], 'compare' => '!=']
		],
		'group' => '_g_single_share_top',
	],

	[
		'name'    => 'css_single_share_top_typo_size',
		'label'   => esc_html__('Label Font Size', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'number',
		'devices' => true,
		'style'   => 'inline-sm',
		'css'     => [
			'.post-share-b:not(.is-not-global) .service .label' => ['props' => ['font-size' => '%spx']],
		],
		'context' => [
			['key' => 'single_share_top', 'value' => 1],
			['key' => 'single_share_top_style', 'value' => ['circles', 'b3-circles', 'b4'], 'compare' => '!='],
			['key' => 'css_single_share_top_label_hide', 'value' => 1, 'compare' => '!=']
		],
		'group' => '_g_single_share_top',
	],

	[
		'name'    => 'css_single_share_top_bg',
		'label'   => esc_html__('Background', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.post-share-b:not(.is-not-global) .service' => ['props' => ['background-color' => '%s']],
		],
		'context' => [['key' => 'single_share_top', 'value' => 1]],
		'group' => '_g_single_share_top',
	],
	[
		'name'    => 'css_single_share_top_bg_sd',
		'label'   => esc_html__('Dark: Background', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.post-share-b:not(.is-not-global) .service' => ['props' => ['background-color' => '%s']],
		],
		'context' => [['key' => 'single_share_top', 'value' => 1]],
		'group' => '_g_single_share_top',
	],

	[
		'name'    => '_n_single_share_top_info',
		'type'    => 'message',
		'label'   => '',
		'text'    => 'The styling customizations only apply to global selected share style above. If you select a different style under a specific post style on the previous screen, these will not apply.',
		'style'   => 'message-info',
		'group' => '_g_single_share_top',
	],

	/**
	 * Group: Social Follow Top
	 */
	[
		'name'  => '_g_single_follow_top',
		'label' => esc_html__('Top Social Follow', 'bunyad-admin'),
		'type'  => 'group',
		'desc'  => 'Show social follow next to top social share. Only available when Top Social Share is enabled and is not the right-aligned style.',
		'style' => 'collapsible',
	],
	[
		'name'  => 'single_follow_top',
		'label' => esc_html__('Show Follow Buttons', 'bunyad-admin'),
		'value' => 0,
		'desc'  => '',
		'type'  => 'toggle',
		'style' => 'inline-sm',
		'context' => [
			['key' => 'single_share_top', 'value' => 1],
			['key' => 'single_share_top_location', 'value' => ''],
		],
		'group' => '_g_single_follow_top',
	],
	[
		'name'  => 'single_follow_top_label',
		'label' => esc_html__('Follow Label', 'bunyad-admin'),
		'value' => '',
		'desc'  => '',
		'placeholder' => esc_html__('Follow Us', 'bunyad'),
		'type'  => 'text',
		'style' => 'inline-sm',
		'context' => [
			['key' => 'single_follow_top', 'value' => 1],
			// ['key' => 'single_share_top_location', 'value' => ''],
		],
		'group' => '_g_single_follow_top',
	],
	[
		'name'    => 'single_follow_top_services',
		'label'   => esc_html__('Services', 'bunyad-admin'),
		'value'   => ['google-news', 'flipboard'],
		'desc'    => '',
		'type'    => 'checkboxes',
		'style'   => 'sortable',
		'options' => $_common['social_services_ext'],
		'context' => [
			['key' => 'single_follow_top', 'value' => 1],
			// ['key' => 'single_share_top', 'value' => 1],
			// ['key' => 'single_share_top_location', 'value' => ''],
		],
		'group'   => '_g_single_follow_top',
	],

	/**
	 * Group: Social Share Bottom
	 */
	[
		'name'  => '_g_single_share',
		'label' => esc_html__('Bottom Social Share', 'bunyad-admin'),
		'type'  => 'group',
		'desc'  => 'Show social share buttons below posts.',
		'style' => 'collapsible',
	],
	[
		'name'  => 'single_share_bot',
		'label' => esc_html__('Show Share Buttons', 'bunyad-admin'),
		'value' => 1,
		'desc'  => '',
		'type'  => 'toggle',
		'style' => 'inline-sm',
		'group' => '_g_single_share',
	],
	[
		'name'    => 'single_share_bot_services',
		'label'   => esc_html__('Share Services', 'bunyad-admin'),
		'value'   => ['facebook', 'twitter', 'pinterest', 'linkedin', 'tumblr', 'email'],
		'desc'    => '',
		'type'    => 'checkboxes',
		'style'   => 'sortable',
		'options' => $_common['social_share_services'],
		'context' => [['key' => 'single_share_bot', 'value' => 1]],
		'group'   => '_g_single_share',
	],

	/**
	 * Group: Social Follow Inline
	 */
	[
		'name'  => '_g_single_follow_bot',
		'label' => esc_html__('Bottom Social Follow', 'bunyad-admin'),
		'type'  => 'group',
		'desc'  => 'Show large social follow buttons. Recommended: Only <strong>2</strong> buttons, best for Flipboard and Google News.',
		'style' => 'collapsible',
	],
	[
		'name'  => 'single_follow_bot',
		'label' => esc_html__('Show Follow Buttons', 'bunyad-admin'),
		'value' => 0,
		'desc'  => '',
		'type'  => 'toggle',
		'style' => 'inline-sm',
		'context' => [
			['key' => 'single_share_bot', 'value' => 1],
		],
		'group' => '_g_single_follow_bot',
	],
	[
		'name'  => 'single_follow_bot_format',
		'label' => esc_html__('Text Format', 'bunyad-admin'),
		'value' => '',
		'desc'  => '',
		'placeholder' => esc_html__('Follow on %s', 'bunyad'),
		'type'  => 'text',
		'style' => 'inline-sm',
		'context' => [
			['key' => 'single_follow_bot', 'value' => 1],
		],
		'group' => '_g_single_follow_bot',
	],
	[
		'name'    => 'single_follow_bot_services',
		'label'   => esc_html__('Services', 'bunyad-admin'),
		'value'   => ['google-news', 'flipboard'],
		'desc'    => '',
		'type'    => 'checkboxes',
		'style'   => 'sortable',
		'options' => $_common['social_services_ext'],
		'context' => [
			['key' => 'single_follow_bot', 'value' => 1],
		],
		'group'   => '_g_single_follow_bot',
	],
];

/**
 * Fields: Related Posts
 */
$fields_related = [
	[
		'name'  => 'related_posts',
		'label' => esc_html__('Show Related Posts', 'bunyad-admin'),
		'value' => 1,
		'desc'  => 'Show related posts below the post.',
		'type'  => 'toggle',
		'style' => 'inline-sm',
		// 'group'   => '_g_single_related',
	],
	[
		'name'  => 'related_posts_heading',
		'label' => esc_html__('Heading', 'bunyad-admin'),
		'desc'  => '',
		'value' => '',
		'placeholder' => esc_html__('Related *Posts*', 'bunyad'),
		'type'  => 'text',
		// 'style' => 'inline-sm',
		// 'group'   => '_g_single_related',
	],
	[
		'name'    => 'related_posts_by',
		'label'   => esc_html__('Related Posts Match By', 'bunyad-admin'),
		'value'   => 'cat_tags',
		'desc'    => '',
		'type'    => 'radio',
		'options' => [
			''         => esc_html__('Categories', 'bunyad-admin'),
			'tags'     => esc_html__('Tags', 'bunyad-admin'),
			'cat_tags' => esc_html__('Both', 'bunyad-admin'),

		],
		'context' => [['key' => 'related_posts', 'value' => 1]],
		// 'group'   => '_g_single_related',
	],
	[
		'name'    => 'related_posts_yarpp',
		'label'   => esc_html__('Use YARPP Plugin? (Advanced)', 'bunyad-admin'),
		'desc'    => esc_html__('Enabling this will allow you to use YARPP (Yet Another Related Posts Plugin) with theme styling.', 'bunyad-admin'),
		'value'   => 0,
		'type'    => 'toggle',
		'context' => [['key' => 'related_posts', 'value' => 1]],
		// 'group'   => '_g_single_related',
	],
	[
		'name'    => 'related_posts_number',
		'label'   => esc_html__('Related Posts Number', 'bunyad-admin'),
		'value'   => 3,
		'desc'    => '',
		'type'    => 'number',
		'context' => [['key' => 'related_posts', 'value' => 1]],
		// 'group'   => '_g_single_related',
	],
	[
		'name'        => 'related_posts_number_full',
		'label'       => esc_html__('Number on Full Width Posts', 'bunyad-admin'),
		'value'       => 3,
		'desc'        => '',
		'type'        => 'number',
		'input_attrs' => ['min' => 3, 'max' => 30, 'step' => 1],
		'context'     => [['key' => 'related_posts', 'value' => 1]],
		// 'group'   => '_g_single_related',
	],
	[
		'name'    => 'related_posts_grid',
		'label'   => esc_html__('Related Posts Columns', 'bunyad-admin'),
		'value'   => 3,
		'desc'    => 'Note: Does not affect Full Width posts. Use setting below.',
		'type'    => 'select',
		'options' => [
			3 => esc_html__('3 Columns', 'bunyad-admin'),
			2 => esc_html__('2 Columns', 'bunyad-admin'),
		],
		'context' => [['key' => 'related_posts', 'value' => 1]],
		// 'group'   => '_g_single_related',
	],
	[
		'name'    => 'related_posts_grid_full',
		'label'   => esc_html__('Columns for Full Width', 'bunyad-admin'),
		'value'   => 3,
		'type'    => 'select',
		'options' => [
			3 => esc_html__('3 Columns', 'bunyad-admin'),
			2 => esc_html__('2 Columns', 'bunyad-admin'),
		],
		'context' => [['key' => 'related_posts', 'value' => 1]],
		// 'group'   => '_g_single_related',
	],
	[
		'name'    => 'related_posts_meta_above',
		'label'   => esc_html__('Meta: Above Title', 'bunyad-admin'),
		'desc'    => '',
		'value'   => [],
		'type'    => 'checkboxes',
		'options' => $_common['meta_options'],
		// Not a global style, specific to checkboxes.
		'style'   => 'sortable',
	],

	[
		'name'    => 'related_posts_meta_below',
		'label'   => esc_html__('Meta: Below Title', 'bunyad-admin'),
		'desc'    => '',
		'value'   => ['date'],
		'type'    => 'checkboxes',
		'options' => $_common['meta_options'],
		// Not a global style, specific to checkboxes.
		'style'   => 'sortable',
	],
];

/**
 * Section: Post Style: Modern
 */
$fields_modern = [
	[
		'name'    => 'css_single_modern_title',
		'label'   => esc_html__('Post Title Size', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'slider',
		'devices' => true,
		'css'     => [
			'.s-head-modern-a .post-title' => ['props' => ['font-size' => '%dpx']],
		],
	],
	[
		'name'       => 'css_single_modern_subtitle',
		'label'      => esc_html__('Subtitle Typography', 'bunyad-admin'),
		'type'       => 'group',
		'group_type' => 'typography',
		'style'      => 'edit',
		'devices'    => true,
		'css'        => '.s-head-modern .sub-title',
	],
	// @todo: Dark
	[
		'name'    => 'css_single_modern_subtitle_color',
		'label'   => esc_html__('Subtitle Color', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.site-s-light .s-head-modern .sub-title' => ['props' => ['color' => '%s']],
		],
	],

	[
		'name'    => 'css_single_modern_meta_color',
		'label'   => esc_html__('Post Meta Color', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.site-s-light .s-head-modern-a .post-meta' => ['props' => ['--c-post-meta' => '%s']],
		],
	],
	[
		'name'    => 'css_single_modern_meta_color_sd',
		'label'   => esc_html__('Dark: Meta Color', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.s-dark .s-head-modern-a .post-meta' => ['props' => ['--c-post-meta' => '%s']],
		],
	],

	[
		'name'    => 'css_single_modern_content_width',
		'label'   => esc_html__('Content Max Width', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'number',
		'classes' => 'sep-top',
		'style'   => 'inline-sm',
		'css'     => [
			// '.s-post-modern .entry-content' => [
			// 	'props' => [
			// 		'padding'          => '0 clamp(0px, calc((100% - %spx) / 2), 30%)',
			// 		'--p-spacious-pad' => 'clamp(0px, calc((100% - %spx) / 2), 30%)',
			// 	]
			// ]
			'.s-post-modern .post-content-wrap' => [
				'props' => ['display' => 'grid', 'grid-template-columns' => 'minmax(0, 1fr)']
			],
			'.s-post-modern .entry-content' => [
				'props' => [
					'max-width' => 'min(100%, calc(%spx + var(--p-spacious-pad)*2))',
					'justify-self' => 'center'
				]
			]
		],
	],

	[
		'name'    => 'css_single_modern_title_margins',
		'label'   => esc_html__('Title Margins', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'dimensions',
		'devices' => true,
		'css'     => [
			'.s-head-modern-a .post-title' => ['dimensions' => 'margin'],
		],
	],
	[
		'name'    => 'css_single_modern_meta_margins',
		'label'   => esc_html__('Meta Below Margins', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'dimensions',
		'devices' => true,
		'css'     => [
			'.s-head-modern-a .meta-below' => ['dimensions' => 'margin'],
		],
	],
];

$fields_modern_large = [
	[
		'name'    => 'css_single_large_title',
		'label'   => esc_html__('Post Title Size', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'slider',
		'devices' => true,
		'css'     => [
			'.s-head-large .post-title' => ['props' => ['font-size' => '%dpx']],
		],
	],

	[
		'name'       => 'css_single_large_subtitle',
		'label'      => esc_html__('Subtitle Typography', 'bunyad-admin'),
		'type'       => 'group',
		'group_type' => 'typography',
		'style'      => 'edit',
		'devices'    => true,
		'css'        => '.s-head-large .sub-title',
	],
	// @todo: Dark
	[
		'name'    => 'css_single_large_subtitle_color',
		'label'   => esc_html__('Subtitle Color', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.site-s-light .s-head-large .sub-title' => ['props' => ['color' => '%s']],
		],
	],

	[
		'name'    => 'css_single_large_meta_color',
		'label'   => esc_html__('Post Meta Color', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.site-s-light .s-head-large .post-meta' => ['props' => ['--c-post-meta' => '%s']],
		],
	],
	[
		'name'    => 'css_single_large_meta_color_sd',
		'label'   => esc_html__('Dark: Meta Color', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => [
			'.s-dark .s-head-large .post-meta' => ['props' => ['--c-post-meta' => '%s']],
		],
	],

	[
		'name'    => 'css_single_large_content_width',
		'label'   => esc_html__('Content Max Width', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.s-post-large .post-content-wrap' => [
				'props' => ['display' => 'grid', 'grid-template-columns' => 'minmax(0, 1fr)']
			],
			'.s-post-large .entry-content' => [
				'props' => [
					'max-width' => 'min(100%, calc(%spx + var(--p-spacious-pad)*2))',
					'justify-self' => 'center'
				]
			]
		],
	],

	/**
	 * Group: Post Meta
	 */
	[
		'name'      => '_g_single_large_b',
		'label'     => esc_html__('Bold Style', 'bunyad-admin'),
		'desc'      => 'Specific settings for Large Bold style variation.',
		'type'      => 'group',
		'collapsed' => false,
		'style'     => 'collapsible',
	],
		[
			'name'    => 'single_large_b_share_top_style',
			'label'   => esc_html__('Share Top Style', 'bunyad-admin'),
			'desc'    => '',
			'value'   => 'b4',
			'type'    => 'select',
			'options' => $_common['share_top_styles'],
			'style'   => 'inline-sm',
			'context' => [['key' => 'single_share_top', 'value' => 1]],
			'group'   => '_g_single_large_b',
		],
		[
			'name'    => 'single_large_b_share_top_location',
			'label'   => esc_html__('Share Location', 'bunyad-admin'),
			'value'   => 'meta-right',
			'type'    => 'select',
			'options' => $_common['share_top_locations'],
			'style'   => 'inline-sm',
			'context' => [['key' => 'single_share_top', 'value' => 1]],
			'group'   => '_g_single_large_b',
		],
];

$fields_cover = [
	[
		'name'    => 'css_single_cover_title',
		'label'   => esc_html__('Post Title Size', 'bunyad-admin'),
		'value'   => '',
		'desc'    => '',
		'type'    => 'slider',
		'devices' => true,
		'css'     => [
			'.post-cover .post-meta .post-title' => ['props' => ['font-size' => '%dpx']],
		],
	],

	[
		'name'     => 'css_single_cover_overlay_c',
		'label'    => esc_html__('Overlay Color', 'bunyad-admin'),
		'desc'     => 'Overlay gradient color.',
		'value'    => '',
		'type'     => 'color-alpha',
		'style'    => 'inline-sm',
		'css'      => [
			'.post-cover .overlay' => ['props' => ['--grad-color' => '%s']]
		],
	],
];

$options['posts-single'] = [
	'title'    => esc_html__('Single Post Page', 'bunyad-admin'),
	'id'       => 'posts-single',
	'sections' => [
		[
			'id'     => 'posts-single-general',
			'title'  => esc_html__('General & Post Style', 'bunyad-admin'),
			'fields' => $fields_general,
		],
		[
			'id'     => 'posts-single-design',
			'title'  => esc_html__('Design: Post / Page', 'bunyad-admin'),
			'fields' => $fields_design,
			'desc'   => esc_html__('All settings in this section area shared between single post and pages.', 'bunyad-admin'),
		],
		[
			'id'     => 'posts-single-social',
			'title'  => esc_html__('Social Sharing', 'bunyad-admin'),
			'fields' => $fields_social,
		],
		[
			'id'     => 'posts-single-related',
			'title'  => esc_html__('Related Posts', 'bunyad-admin'),
			'fields' => $fields_related,
		],
		[
			'id'     => 'posts-single-reviews',
			'title'  => esc_html__('Reviews', 'bunyad-admin'),
			'fields' => [
				[
					'name'  => 'user_rating',
					'value' => 1,
					'label' => esc_html__('Enable Users Ratings', 'bunyad-admin'),
					'desc'  => esc_html__('This feature adds a user rating area below criterion to allow readers to click and vote.', 'bunyad-admin'),
					'type'  => 'checkbox'
				],

				[
					'name'  => 'review_schema',
					'value' => 1,
					'label' => esc_html__('Enable Review Schema', 'bunyad-admin'),
					'desc'  => esc_html__('This feature adds a user rating area below criterion to allow readers to click and vote.', 'bunyad-admin'),
					'type'  => 'checkbox'
				],

				[
					'name'    => 'css_review_verdict_radius',
					'label'   => esc_html__('Verdict Box Roundness', 'bunyad-admin'),
					'value'   => '',
					'desc'    => '',
					'type'    => 'number',
					'style'   => 'inline-sm',
					'css'     => [
						'.review-box .overall' => ['props' => ['border-radius' => '%spx']],
					],
				],

				[
					'name'    => 'css_review_bars_height',
					'label'   => esc_html__('Rating Bars Height', 'bunyad-admin'),
					'value'   => '',
					'desc'    => '',
					'type'    => 'number',
					'devices' => true,
					'style'   => 'inline-sm',
					'css'     => [
						'.review-box .rating-bar, .review-box .bar' => ['props' => ['height' => '%spx']],
					],
				],
				[
					'name'    => 'css_review_bars_radius',
					'label'   => esc_html__('Bars Roundness', 'bunyad-admin'),
					'value'   => '',
					'desc'    => '',
					'type'    => 'number',
					'style'   => 'inline-sm',
					'css'     => [
						'.review-box .rating-bar, .review-box .bar' => ['props' => ['border-radius' => '%spx']],
					],
				],

				[
					'name'       => 'css_review_title_typo',
					'label'      => esc_html__('Title Typography', 'bunyad-admin'),
					'value'      => '',
					'desc'       => '',
					'type'       => 'group',
					'group_type' => 'typography',
					'style'      => 'edit',
					'controls'   => ['size', 'weight'],
					'css'        => '.review-box .summary'
				],

				[
					'name'       => 'css_review_labels_typo',
					'label'      => esc_html__('Criteria Labels Typography', 'bunyad-admin'),
					'value'      => '',
					'desc'       => '',
					'type'       => 'group',
					'group_type' => 'typography',
					'style'      => 'edit',
					'controls'   => ['size', 'weight', 'transform'],
					'css'        => '.review-box .label'
				],

				[
					'name'       => 'css_review_pros_cons_typo',
					'label'      => esc_html__('Pros/Cons Typography', 'bunyad-admin'),
					'value'      => '',
					'desc'       => '',
					'type'       => 'group',
					'group_type' => 'typography',
					'style'      => 'edit',
					'controls'   => ['size', 'weight', 'transform'],
					'css'        => '.review-pros-cons li'
				],

				[
					'name'       => 'css_review_summary_typo',
					'label'      => esc_html__('Summary Typography', 'bunyad-admin'),
					'value'      => '',
					'desc'       => '',
					'type'       => 'group',
					'group_type' => 'typography',
					'style'      => 'edit',
					'controls'   => ['size', 'weight', 'line_height'],
					'css'        => '.review-box .summary'
				],
					
				[
					'name'  => 'review_scale',
					'value' => 10,
					//'label' => esc_html__('Advanced: Review Scale', 'bunyad-admin'),
					//'desc'  => esc_html__('WARNING: Only for fresh installs. Changing this number will break previously created review posts.', 'bunyad-admin'),
					'type'  => 'hidden',
				],
			],
		],
		[
			'id'     => 'posts-style-default',
			'title'  => esc_html__('Post Style: Modern', 'bunyad-admin'),
			'desc'   => 'These are specific settings that only apply to the Modern Post Style. See <strong>General</strong> for more on previous screen.',
			'fields' => $fields_modern,
		],
		[
			'id'     => 'posts-style-large',
			'title'  => esc_html__('Post Style: Large', 'bunyad-admin'),
			'desc'   => 'These are specific settings that only apply to the Modern Large Style. See <strong>General</strong> for more on previous screen.',
			'fields' => $fields_modern_large,
		],
		[
			'id'     => 'posts-style-cover',
			'title'  => esc_html__('Post Style: Cover', 'bunyad-admin'),
			'desc'   => 'These are specific settings that only apply to the Post Cover Style. See <strong>General</strong> for more on previous screen.',
			'fields' => $fields_cover,
		],

		[
			'id'     => 'posts-autoload-next',
			'title'  => esc_html__('Auto-load Next Post', 'bunyad-admin'),
			'desc'   => '',
			'fields' => [
				[
					'name' => '_n_autoload_next',
					'type' => 'message',
					'text' => 'Go to main Customizer and <a href="#" class="focus-link" data-section="sphere-auto-load-post">Auto-load Next Post</a> section.',
					'style' => 'message-info',
				]
			],
		],
		// [
		// 	'id'     => 'posts-style-creative',
		// 	'title'  => esc_html__('Post Style: Creative', 'bunyad-admin'),
		// 	'desc'   => 'These are specific settings that only apply to the Creative Post Style. See General for more.',
		// 	'fields' => $fields_creative,
		// ],
	],
];

return $options;