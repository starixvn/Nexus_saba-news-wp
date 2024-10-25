<?php
/**
 * Main layout options
 */
$fields = [
	[
		'name'    => 'custom_width',
		'label'   => esc_html__('Custom Layout Width/Space', 'bunyad-admin'),
		'value'   => 0,
		'desc'    => esc_html__('Adjust layout width or padding.', 'bunyad-admin'),
		'type'    => 'toggle',
		'style'   => 'inline-sm',
	],

	[
		'name'    => 'layout_type',
		'label'   => esc_html__('Layout Type', 'bunyad-admin'),
		'desc'    => esc_html__('Select whether you want a boxed or a full-width layout. It affects every page and the whole layout.', 'bunyad-admin'),
		'type'    => 'select',
		'value'   => 'normal',
		'style'   => 'inline-sm',
		'options' => [
			'normal'  => esc_html__('Full Width', 'bunyad-admin'),
			'boxed' => esc_html__('Boxed', 'bunyad-admin'),
		],
	],

	[
		'name'    => 'css_body_bg',
		'label'   => esc_html__('Background Image', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'upload',
		'options' => [
			'type'  => 'image',
		],
		'bg_type' => ['value' => 'cover'],
		'css'      => [
			'.ts-bg-cover' => ['props' => ['background-image' => 'url(%s)']]
		],
		'context' => [['key' => 'layout_type', 'value' => 'boxed']]
	],

	[
		'name'    => '_n_the_notice',
		'type'    => 'message',
		'label'   => '',
		'text'    => esc_html__('Careful! The default layouts are optimized for all devices. Change these only if you understand the implications.', 'bunyad-admin'),
		'style'   => 'message-alert',
		'context' => [['key' => 'custom_width', 'value' => 1]]
	],
	[
		'name'    => 'layout_width',
		'label'   => esc_html__('Layout Main Width', 'bunyad-admin'),
		'desc'    => 'The max width to use. Images are adjusted based on this.',
		'value'   => 1200,
		'type'    => 'slider',
		// 'devices' => true,
		'input_attrs' => ['min' => 960, 'max' => 1920, 'step' => 5],
		'transport'   => 'refresh',
		'css'         => [
			'vars' => ['props' => ['--main-width' => '%spx']],
		],
		'context' => [['key' => 'custom_width', 'value' => 1]]
	],
	[
		'name'        => 'css_layout_width_resp',
		'label'       => esc_html__('Layout Width Percent', 'bunyad-admin'),
		'desc'        => 'Advanced! Recommended to be used only for mobile devices.',
		'value'       => '',
		'type'        => 'slider',
		'devices'     => true,
		'input_attrs' => ['min' => 20, 'max' => 100, 'step' => 1],
		'css'         => [
			'.wrap' => ['props' => ['width' => '%s%']],
		],
		'context' => [['key' => 'custom_width', 'value' => 1]]
	],
	[
		'name'        => 'css_layout_padding',
		'label'       => esc_html__('Main Layout Padding', 'bunyad-admin'),
		'desc'        => '',
		'value'       => ['main' => 35, 'medium' => 35, 'small' => 25],
		'type'        => 'slider',
		'devices'     => true,
		'classes'     => 'sep-bottom',
		'input_attrs' => ['min' => 0, 'max' => 250, 'step' => 1],
		'css'         => [
			'.ts-contain, .main' => [
				'all' => ['props' => 
					['padding-left' => '%spx', 'padding-right' => '%spx']
				],
				// We use root vars, so skip main and global.
				'main'   => [],
				'global' => [],
			],
			'vars' => [
				'all' => ['props' => ['--wrap-padding' => '%spx']],
			],
			':root' => [
				'all' => [],
				// Limit on low widths to 5vw max.
				'@media (min-width: 940px) and (max-width: 1300px)' => [
					'props' => ['--wrap-padding' => 'min(%spx, 5vw)'],
					'value_key' => 'main'
				]
			]

		],
		'context' => [['key' => 'custom_width', 'value' => 1]]
	],

// Sidebar custom widths.
[
	'name'        => 'sidebar_width',
	'label'       => esc_html__('Sidebar Width', 'bunyad-admin'),
	'desc'        => '',
	'value'       => '',
	'type'        => 'select',
	'style'       => 'inline-sm',
	'transport'   => 'postMessage',
	'options'     => [
		''        => esc_html__('Default', 'bunyad-admin'),
		'percent' => esc_html__('Custom Width: Percent', 'bunyad-admin'),
		'pixels'  => esc_html__('Custom Width: Pixels', 'bunyad-admin'),

	]
],
	[
		'name'        => 'css_sidebar_width',
		'label'       => esc_html__('Sidebar Width Percent', 'bunyad-admin'),
		'desc'        => '',
		'value'       => '',
		'type'        => 'slider',
		'input_attrs' => ['min' => 5, 'max' => 100, 'step' => 1],
		'css'         => [
			'vars' => ['props' => ['--sidebar-width' => '%s%']],
		],
		'context' => [['key' => 'sidebar_width', 'value' => 'percent']]
	],
	[
		'name'        => 'css_sidebar_width_px',
		'label'       => esc_html__('Sidebar Width', 'bunyad-admin'),
		'desc'        => '',
		'value'       => '',
		'style'       => 'inline-sm',
		'type'        => 'number',
		'devices'     => ['main', 'large'],
		'input_attrs' => ['min' => 50, 'max' => 800, 'step' => 1],
		'css'         => [
			':root' => ['props' => ['--sidebar-width' => '%spx']],
			'.ts-row, .has-el-gap' => [
				'props' => [
					'--sidebar-c-width' => 'calc(var(--sidebar-width) + var(--grid-gutter-h) + var(--sidebar-c-pad))'
				]
			]
		],
		'context' => [['key' => 'sidebar_width', 'value' => 'pixels']]
	],
[
	'name'        => 'css_sidebar_pad',
	'label'       => esc_html__('Sidebar Gap', 'bunyad-admin'),
	'desc'        => '',
	'value'       => '',
	'type'        => 'number',
	'devices'     => ['main', 'large'],
	'style'       => 'inline-sm',
	'input_attrs' => ['min' => 0, 'max' => 250, 'step' => 1],
	'css'         => [
		':root' => ['props' => [
			'--sidebar-pad'     => '%spx', 
			'--sidebar-sep-pad' => '%spx'
		]],
	],
],

[
	'name'    => 'default_sidebar',
	'label'   => esc_html__('Default Sidebar', 'bunyad-admin'),
	'desc'    => esc_html__('This setting can be changed per post or page.', 'bunyad-admin'),
	'value'   => 'right',
	'type'    => 'radio',
	'options' => [
		'none'  => esc_html__('No Sidebar', 'bunyad-admin'),
		'right' => esc_html__('Right Sidebar', 'bunyad-admin') 
	],
],

[
	'name'    => 'sidebar_sticky',
	'label'   => esc_html__('Sticky Sidebar', 'bunyad-admin'),
	'desc'    => esc_html__('Make the sidebar always stick around while scrolling. Note: Does not affect sidebars within Elementor homepage.', 'bunyad-admin'),
	'value'   => 1,
	'type'    => 'toggle',
	'style'   => 'inline-sm',
],

[
	'name'    => 'sidebar_sticky_type',
	'label'   => esc_html__('Sticky Sidebar Type', 'bunyad-admin'),
	'desc'    => esc_html__('Native sticky is faster on slower laptops, but smart sticky has better UX.', 'bunyad-admin'),
	'value'   => 'smart',
	'type'    => 'select',
	'options' => [
		'native' => esc_html__('Native Sticky', 'bunyad-admin'),
		'smart'  => esc_html__('Smart Sticky', 'bunyad-admin'),
	],
	'style'   => 'inline-sm',
	// 'context' => [['key' => 'sidebar_sticky', 'value' => 1]]
],

[
	'name'    => 'sidebar_separator',
	'label'   => esc_html__('Sidebar Separator Line', 'bunyad-admin'),
	'value'   => 1,
	'desc'    => '',
	'type'    => 'toggle',
	'style'   => 'inline-sm',
],

	/**
	 * Group: Base Settings
	 */
	[
		'name'    => '_g_advanced_layout',
		'label'   => esc_html__('Advanced: Global Layout', 'bunyad-admin'),
		'desc'    => 'Advanced settings for the global layout.',
		'type'    => 'group',
		'style'   => 'collapsible',
	],

		[
			'name'  => 'css_layout_main_margin',
			'label' => esc_html__('Main Wrap Margins', 'bunyad-admin'),
			'desc'  => '',
			'value' => [],
			'type'    => 'dimensions',
			'devices' => true,
			'fields'  => ['top', 'bottom'],
			'css'   => [
				'.main-wrap > .main' => ['dimensions' => 'margin'],
			],
			'group'   => '_g_advanced_layout'
		],


	/**
	 * Group Sidebar Stylings
	 */
	[
		'name'  => '_g_sidebar_style',
		'label' => esc_html__('Sidebar Styling', 'bunyad-admin'),
		'type'  => 'group',
		'style' => 'collapsible',
		// 'collapsed' => false,
	],

		// Fields
		[
			'name'    => 'sidebar_titles_style',
			'label'   => esc_html__('Widget Titles Style', 'bunyad-admin'),
			'desc'    => '',
			'value'   => 'g',
			'type'    => 'select',
			'options' => $_common['sidebar_headings'],
			'group'   => '_g_sidebar_style'
		],

		[
			'name'    => '_n_sidebar_titles_customize',
			'type'    => 'message',
			'label'   => '',
			'text'    => 'The title styles above use Block Headings styles. You can customize this block heading from  <a href="#" class="focus-link is-with-nav" data-section="bunyad-posts-listings-headings">Block Headings Styles.</a>',
			'style'   => 'message-info',
			'group'   => '_g_sidebar_style'
		],

		// [
		// 	'name'    => '_n_sidebar_titles',
		// 	'type'    => 'message',
		// 	'label'   => '',
		// 	'text'    => 'There are customizations active that may change the look of the selected titles style. <a href="#" class="preset-reset">Click here</a> to reset them to defaults.',
		// 	'style'   => 'message-alert',
		// 	'classes' => 'bunyad-cz-hidden',
		// 	'group'   => '_g_sidebar_style'
		// ],

			
		// [
		// 	'name'  => 'css_sidebar_title_color',
		// 	'value' => '',
		// 	'label' => esc_html__('Titles Color', 'bunyad-admin'),
		// 	'desc'  => '',
		// 	'type'  => 'color',
		// 	'css'   => [
		// 		'.sidebar .widget-title' => ['props' => ['color' => '%s']]
		// 	],
		// 	'group' => '_g_sidebar_style'
		// ],

		[
			'name'       => 'css_sidebar_title_typo',
			'label'      => esc_html__('Titles Typography', 'bunyad-admin'),
			'desc'       => '',
			'value'      => '',
			'type'       => 'group',
			'group_type' => 'typography',
			'style'      => 'edit',
			'css'        => '.main-sidebar .widget-title .heading',
			'group'      => '_g_sidebar_style'
		],

		// [
		// 	'name'    => 'css_sidebar_title_align',
		// 	'label'   => esc_html__('Titles Text Align', 'bunyad-admin'),
		// 	'desc'    => '',
		// 	'value'   => '',
		// 	'type'    => 'select',
		// 	'style'   => 'inline',
		// 	'options' => [
		// 		''       => esc_html__('Default', 'bunyad-admin'),
		// 		'left'   => esc_html__('Left', 'bunyad-admin'),
		// 		'center' => esc_html__('Center', 'bunyad-admin'),
		// 		'right'  => esc_html__('Right', 'bunyad-admin'),
		// 	],
		// 	'css'   => [
		// 		'.main-sidebar .widget-title'              => ['props' => ['text-align' => '%s']],
		// 		'.main-sidebar .widget-title .title:after' => [
		// 			'props' => [
		// 				'condition' => [
		// 					'left'  => ['margin-left' => '0'],
		// 					'right' => ['margin-right' => '0'],
		// 				]
		// 			],
		// 		]
		// 	],
		// 	'group' => '_g_sidebar_style'
		// ],

		[
			'name'  => 'css_sidebar_title_margin',
			'label' => esc_html__('Title Space Below', 'bunyad-admin'),
			'desc'  => '',
			'value' => '',
			'type'  => 'slider',
			'css'   => [
				'.main-sidebar .widget-title' => ['props' => ['--space-below' => '%spx']],
			],
			'group' => '_g_sidebar_style'
		],

		[
			'name'    => 'css_sidebar_widget_margin',
			'value'   => 45,
			'label'   => esc_html__('Widget Bottom Spacing', 'bunyad-admin'),
			'desc'    => '',
			'type'    => 'slider',
			'devices' => true,
			'css'     => [
				'.main-sidebar .widget' => ['props' => ['margin-bottom' => '%spx']],
			],
			'group' => '_g_sidebar_style'
		],
];

$fields_breadcrumbs = [
	
	/**
	 * Group Breadcrumbs
	 */
	[
		'name'  => '_g_breadcrumbs',
		'label' => esc_html__('Breadcrumbs', 'bunyad-admin'),
		'type'  => 'group',
		'desc'  => 'If Yoast/RankMath SEO plugin breadcrumbs are used, the settings below will not apply.',
		'style' => 'collapsible',
		// 'collapsed' => false,
	],	

		// Fields
		[
			'name'  => 'breadcrumbs_enable',
			'label' => esc_html__('Enable Breadcrumbs', 'bunyad-admin'),
			'desc'  => '',
			'value' => 1,
			'type'  => 'toggle',
			'style' => 'inline-sm',
			'group' => '_g_breadcrumbs'
		],

		[
			'name'  => 'breadcrumbs_renderer',
			'label' => esc_html__('Breadcrumbs Renderer', 'bunyad-admin'),
			'desc'  => '',
			'value' => '',
			'type'  => 'select',
			'options' => [
				''         => esc_html__('Auto', 'bunyad-admin'),
				'sphere'   => esc_html__('Theme Breadcrumbs', 'bunyad-admin'),
				'rankmath' => esc_html__('RankMath Plugin', 'bunyad-admin'),
				'yoast'    => esc_html__('Yoast', 'bunyad-admin'),
			],
			'style' => 'inline-sm',
			'group' => '_g_breadcrumbs'
		],

		[
			'name'    => 'breadcrumbs_style',
			'label'   => esc_html__('Breadcrumbs Style', 'bunyad-admin'),
			'desc'    => '',
			'value'   => 'a',
			'type'    => 'select',
			'style'   => 'inline-sm',
			'options' => [
				'a' => esc_html__('A: Simple', 'bunyad-admin'),
				'b' => esc_html__('B: With Background', 'bunyad-admin'),
			],
			'group' => '_g_breadcrumbs'
		],

		[
			'name'    => 'breadcrumbs_width',
			'label'   => esc_html__('Breadcrumbs Width', 'bunyad-admin'),
			'desc'    => '',
			'value'   => 'full',
			'type'    => 'select',
			'style'   => 'inline-sm',
			'options' => [
				'full' => esc_html__('Full Width', 'bunyad-admin'),
				'wrap' => esc_html__('Site Width', 'bunyad-admin'),
			],
			'context' => [['key' => 'breadcrumbs_style', 'value' => 'b']],
			'group'   => '_g_breadcrumbs'
		],

		[
			'name'    => 'breadcrumbs_add_context',
			'label'   => esc_html__('Add Context?', 'bunyad-admin'),
			'desc'    => 'Add text such as Category: when on a category page.',
			'value'   => 1,
			'type'    => 'checkbox',
			'group'   => '_g_breadcrumbs',
			'context' => [['key' => 'breadcrumbs_enable', 'value' => 1]]
		],

		[
			'name'    => 'breadcrumbs_add_label',
			'label'   => esc_html__('Add Label?', 'bunyad-admin'),
			'desc'    => 'Add "You are at:" label text.',
			'value'   => 0,
			'type'    => 'checkbox',
			'group'   => '_g_breadcrumbs',
			'context' => [['key' => 'breadcrumbs_enable', 'value' => 1]]
		],
		[
			'name'    => 'breadcrumbs_label_text',
			'label'   => esc_html__('Label Text', 'bunyad-admin'),
			'desc'    => '',
			'value'   => '',
			'placeholder' => esc_html_x('You are at:', 'breadcrumbs', 'bunyad'),
			'type'    => 'text',
			'group'   => '_g_breadcrumbs',
			'context' => [['key' => 'breadcrumbs_add_label', 'value' => 1]]
		],
		[
			'name'    => 'breadcrumbs_search',
			'label'   => esc_html__('On Search Page', 'bunyad-admin'),
			'desc'    => '',
			'value'   => 1,
			'type'    => 'checkbox',
			'group'   => '_g_breadcrumbs',
			'context' => [['key' => 'breadcrumbs_enable', 'value' => 1]]
		],
		[
			'name'    => 'breadcrumbs_single',
			'label'   => esc_html__('On Post/Single', 'bunyad-admin'),
			'desc'    => '',
			'value'   => 1,
			'type'    => 'checkbox',
			'group'   => '_g_breadcrumbs',
			'context' => [['key' => 'breadcrumbs_enable', 'value' => 1]]
		],
		[
			'name'    => 'breadcrumbs_page',
			'label'   => esc_html__('On Pages', 'bunyad-admin'),
			'desc'    => '',
			'value'   => 1,
			'type'    => 'checkbox',
			'group'   => '_g_breadcrumbs',
			'context' => [['key' => 'breadcrumbs_enable', 'value' => 1]]
		],
		[
			'name'    => 'breadcrumbs_archive',
			'label'   => esc_html__('On Archives', 'bunyad-admin'),
			'desc'    => '',
			'value'   => 1,
			'type'    => 'checkbox',
			'group'   => '_g_breadcrumbs',
			'context' => [['key' => 'breadcrumbs_enable', 'value' => 1]]
		],
		[
			'name'    => 'breadcrumbs_pagebuilder',
			'label'   => esc_html__('Pagebuilder Pages (Except Home)', 'bunyad-admin'),
			'desc'    => '',
			'value'   => 0,
			'type'    => 'checkbox',
			'group'   => '_g_breadcrumbs',
			'context' => [['key' => 'breadcrumbs_page', 'value' => 1]]
		],

		[
			'name'    => 'breadcrumbs_current_single',
			'label'   => esc_html__('Show Last/Title Item on Single', 'bunyad-admin'),
			'desc'    => 'Whether to show last item / post title on single posts.',
			'value'   => 1,
			'type'    => 'toggle',
			'style'   => 'inline-sm',
			'group'   => '_g_breadcrumbs',
		],


		[
			'name'    => 'css_breadcrumbs_margins',
			'label'   => esc_html__('Margins', 'bunyad-admin'),
			'desc'    => '',
			'value'   => '',
			'type'    => 'dimensions',
			'classes' => 'sep-top',
			'devices' => true,
			'css'     => [
				'.breadcrumbs' => ['dimensions' => 'margin'],
			],
			'group'   => '_g_breadcrumbs',
		],

		[
			'name'       => 'css_breadcrumbs_typo',
			'label'      => esc_html__('Typography', 'bunyad-admin'),
			'desc'       => '',
			'value'      => '',
			'type'       => 'group',
			'group_type' => 'typography',
			'style'      => 'edit',
			'css'        => '.breadcrumbs',
			'group'      => '_g_breadcrumbs',
		],


		[
			'name'       => 'css_breadcrumbs_c_links',
			'label'      => esc_html__('Links Color', 'bunyad-admin'),
			'desc'       => '',
			'value'      => '',
			'type'       => 'color',
			'style'      => 'inline-sm',
			'css'        => [
				'.s-light .breadcrumbs' => ['props' => ['--c-a' => '%s']],
			],
			'group'      => '_g_breadcrumbs',
		],
		[
			'name'       => 'css_breadcrumbs_c_links_sd',
			'label'      => esc_html__('Dark: Links Color', 'bunyad-admin'),
			'desc'       => '',
			'value'      => '',
			'type'       => 'color',
			'style'      => 'inline-sm',
			'css'        => [
				'.s-dark .breadcrumbs' => ['props' => ['--c-a' => '%s']],
			],
			'group'      => '_g_breadcrumbs',
		],
		
		[
			'name'       => 'css_breadcrumbs_color',
			'label'      => esc_html__('Text Color', 'bunyad-admin'),
			'desc'       => '',
			'value'      => '',
			'type'       => 'color',
			'style'      => 'inline-sm',
			'css'        => [
				'.s-light .breadcrumbs' => ['props' => ['color' => '%s']],
			],
			'group'      => '_g_breadcrumbs',
		],
		[
			'name'       => 'css_breadcrumbs_color_sd',
			'label'      => esc_html__('Dark: Text Color', 'bunyad-admin'),
			'desc'       => '',
			'value'      => '',
			'type'       => 'color',
			'style'      => 'inline-sm',
			'css'        => [
				'.s-dark .breadcrumbs' => ['props' => ['color' => '%s']],
			],
			'group'      => '_g_breadcrumbs',
		],

];

$fields = array_merge($fields, $fields_breadcrumbs);

$options['layout-main'] = [
	'sections' => [[
		'id'     => 'layout-main',
		'title'  => esc_html__('Main Layout & Sidebar', 'bunyad-admin'),
		'fields' => $fields
	]]
];

return $options;