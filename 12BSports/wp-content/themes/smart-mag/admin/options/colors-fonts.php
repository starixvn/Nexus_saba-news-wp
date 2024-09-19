<?php
/**
 * Global Color Options
 */

$notice_settings = <<<EOF
<p>Specific color and typography options are in most individual sections. 

Explore from Customizer: 
<a href="#"  class="focus-link is-with-nav" data-panel="bunyad-header">Header</a>, 
<a href="#" class="focus-link is-with-nav" data-panel="bunyad-posts-listings">Blocks & Listings</a>, 
<a href="#" class="focus-link is-with-nav" data-section="bunyad-posts-single-design">Single Post Page</a> and most of the other sections.</p>

EOF;

$fields = [

	/**
	 * Group: Global Colors
	 */
	[
		'name'  => '_g_colors_global',
		'label' => esc_html__('Global Colors', 'bunyad-admin'),
		'type'  => 'group',
		'style' => 'collapsible',
		// 'collapsed' => false
	],

		[
			'name'  => 'color_scheme',
			'label' => esc_html__('Main Color Scheme', 'bunyad-admin'),
			'desc'  => '',
			'value' => 'light',
			'type'  => 'select',
			'options' => [
				'light' => esc_html__('Default / Light', 'bunyad-admin'),
				'dark'  => esc_html__('Dark Mode', 'bunyad-admin')
			],
			'group' => '_g_colors_global',
		],

		[
			'name'  => 'color_scheme_detect',
			'label' => esc_html__('Auto Dark Mode', 'bunyad-admin'),
			'desc'  => 'Use dark mode if use prefers it based on their device settings.',
			'value' => 0,
			'type'  => 'toggle',
			'style' => 'inline-sm',
			'group' => '_g_colors_global',
		],

		[
			'name'  => 'css_main_color',
			'value' => '#2ab391',
			'label' => esc_html__('Main Theme Color', 'bunyad-admin'),
			'desc'  => '',
			'type'  => 'color',
			'style' => 'inline-sm',
			'css'   => [
				'vars' => [
					'props' => ['--c-main' => '%s', '--c-main-rgb' => 'hexToRgb(%s)']
				]
			],
			'group' => '_g_colors_global',
		],
		[
			'name'  => 'css_main_color_sd',
			'value' => '',
			'label' => esc_html__('Dark: Main Color', 'bunyad-admin'),
			'desc'  => '',
			'type'  => 'color',
			'style' => 'inline-sm',
			'css'   => [
				'.s-dark, .site-s-dark' => [
					'props' => ['--c-main' => '%s', '--c-main-rgb' => 'hexToRgb(%s)']
				]
			],
			'group' => '_g_colors_global',
		],
		
		[
			'name'  => 'css_site_bg',
			'value' => '#ffffff',
			'label' => esc_html__('Site Background Color', 'bunyad-admin'),
			'desc'  => '',
			'type'  => 'color',
			'style' => 'inline-sm',
			'css'   => [
				'.s-light body' => ['props' => ['background-color' => '%s']]
			],
			'group' => '_g_colors_global',
		],

		[
			'name'  => 'css_site_bg_sd',
			'value' => '#151516',
			'label' => esc_html__('Dark: Site Background', 'bunyad-admin'),
			'desc'  => '',
			'type'  => 'color',
			'style' => 'inline-sm',
			'css'   => [
				'.s-dark body' => ['props' => ['background-color' => '%s']]
			],
			'group' => '_g_colors_global',
		],

		[
			'name'  => 'css_body_color',
			'value' => '',
			'label' => esc_html__('Body Text Color', 'bunyad-admin'),
			'desc'  => 'Affects all body text, excerpts, single content. To change specific text colors such as excerpt, go to previous screen and Blocks & Listings > Common. For post body text, check in Single Post Page > Shared Design.',
			'type'  => 'color',
			'style' => 'inline-sm',
			'css'   => [
				'.s-light' => ['props' => [
					'--body-color' => '%s',
					'--c-excerpts' => '%s',
				]],
				'.post-content' => ['props' => ['color' => '%s']],
			],
			'group' => '_g_colors_global',
		],
		[
			'name'  => 'css_body_color_sd',
			'value' => '',
			'label' => esc_html__('Dark: Main Text Color', 'bunyad-admin'),
			'desc'  => '',
			'type'  => 'color',
			'style' => 'inline-sm',
			'css'   => [
				'.s-dark' => ['props' => [
					'--body-color' => '%s',
					'--c-excerpts' => '%s',
				]],
				'.s-dark .post-content' => ['props' => ['color' => '%s']],
			],
			'group' => '_g_colors_global',
		],

		[
			'name'  => 'css_h_color',
			'value' => '#161616',
			'label' => esc_html__('Headings Color', 'bunyad-admin'),
			'desc'  => esc_html__('Affects post titles, widget/block headings, h elements in posts etc. Can be overridden with more specific settings.', 'bunyad-admin'),
			'type'  => 'color',
			'style' => 'inline-sm',
			'css'   => [
				'vars' => ['props' => ['--c-headings' => '%s']],
			],
			'group' => '_g_colors_global',
		],
		[
			'name'  => 'css_h_color_sd',
			'value' => '',
			'label' => esc_html__('Dark: Headings Color', 'bunyad-admin'),
			'desc'  => '',
			'type'  => 'color',
			'style' => 'inline-sm',
			'css'   => [
				'.s-dark, .site-s-dark .s-light' => ['props' => ['--c-headings' => '%s']],
			],
			'group' => '_g_colors_global',
		],

		[
			'name'  => 'css_posts_title_color',
			'value' => '',
			'label' => esc_html__('Post Titles Color', 'bunyad-admin'),
			'desc'  => esc_html__('Changing this affects post title colors globally. See specifics in Single Post, Posts & Listings etc.', 'bunyad-admin'),
			'type'  => 'color',
			'style' => 'inline-sm',
			'css'   => [
				'.post-title' => ['props' => ['--c-headings' => '%s']]
			],
			'group' => '_g_colors_global',
		],
		[
			'name'  => 'css_posts_title_color_sd',
			'value' => '',
			'label' => esc_html__('Dark: Post Titles Color', 'bunyad-admin'),
			'type'  => 'color',
			'style' => 'inline-sm',
			'css'   => [
				'.s-dark .post-title' => ['props' => ['--c-headings' => '%s']]
			],
			'group' => '_g_colors_global',
		],
	
	/**
	 * Group: Global Typography
	 */
	[
		'name'  => '_g_typo_global',
		'label' => esc_html__('Global Typography', 'bunyad-admin'),
		'type'  => 'group',
		'style' => 'collapsible'
	],
		[
			'name'    => 'css_font_text',
			'label'   => esc_html__('Primary Font', 'bunyad-admin'),
			'value'   => '',
			'desc'    => esc_html__('Used for text mainly. Select from list or click and type your own Google Font name (or TypeKit if you have configured it).', 'bunyad-admin'),
			'type'    => 'font-family',
			'css'     => [
				'vars' => ['props' => [
					'--text-font' => '%s', 
					'--body-font' => '%s'
				]]
			],
			'add_global' => false,
			'group' => '_g_typo_global',
		],
		
		[
			'name'    => 'css_font_secondary',
			'label'   => esc_html__('Secondary Font', 'bunyad-admin'),
			'value'   => '',
			'desc'    => esc_html__('Used for headings, meta, navigation and so on.', 'bunyad-admin'),
			'type'    => 'font-family',
			'css'     => [
				'vars' => ['props' => [
					'--ui-font'    => '%s', 
					'--title-font' => '%s', 
					'--h-font'     => '%s',
				]]
			],
			'add_global' => false,
			'group' => '_g_typo_global',
		],

		[
			'name'    => 'css_font_tertiary',
			'label'   => esc_html__('Tertiary Font', 'bunyad-admin'),
			'value'   => '',
			'desc'    => esc_html__('Placeholder to use in your settings.', 'bunyad-admin'),
			'type'    => 'font-family',
			'classes' => 'sep-bottom',
			'css'     => [
				'vars' => ['props' => [
					'--tertiary-font'    => '%s', 
				]]
			],
			'add_global' => false,
			'group' => '_g_typo_global',
		],

		[
			'name'    => 'css_font_headings',
			'label'   => esc_html__('Headings Font', 'bunyad-admin'),
			'value'   => '',
			'desc'    => esc_html__('For headings, post titles etc. Not for block headings.', 'bunyad-admin'),
			'type'    => 'font-family',
			'css'     => [
				'vars' => ['props' => [
					'--title-font' => '%s', 
					'--h-font'     => '%s',
				]]
			],
			'group' => '_g_typo_global',	
		],
		[
			'name'    => 'css_font_headings_in_body',
			'label'   => esc_html__('Same Heading Font in Post Body', 'bunyad-admin'),
			'desc'    => '',
			'value'   => '',
			'type'    => 'toggle',
			'style'   => 'inline-sm',
			'css'     => [
				'vars' => ['props' => [
					'--text-h-font' => 'var(--h-font)', 
				]]
			],
			'context' => [['key' => 'css_font_headings', 'value' => '', 'compare' => '!=']],
			'group'   => '_g_typo_global',	
		],
		
		[
			'name'             => 'css_font_post_titles',
			'label'            => esc_html__('Post Titles Typography', 'bunyad-admin'),
			'value'            => '',
			'desc'             => esc_html__('Global post title, affects all. For more typography settings, go to Posts & Listings and edit for specifc layouts like Grid.', 'bunyad-admin'),
			'type'             => 'group',
			'group_type'       => 'typography',
			'style'            => 'edit',
			'css'              => '.post-title:not(._)',
			'controls_options' => [
				'family' => ['css'  => [
					'vars' => ['props' => ['--title-font' => '%s']]
				]],
			],
			// 'controls' => ['family', 'spacing', 'transform', 'weight', 'style'],
			'group'    => '_g_typo_global',
		],

		/**
		 * Group: Base Settings
		 */
		[
			'name'    => '_g_base_sizes',
			'label'   => esc_html__('Advanced: Base Sizes/Weights', 'bunyad-admin'),
			'desc'    => 'These settings affect several post titles in several listings, blocks, sliders etc.',
			'type'    => 'group',
			'style'   => 'collapsible',
			'group'   => '_g_typo_global'
		],

			[
				'name'  => 'css_title_size_xs',
				'value' => '',
				'label' => esc_html__('Base: X-Small Title', 'bunyad-admin'),
				'desc'  => '',
				'type'  => 'number',
				'style' => 'inline-sm',
				'css'   => [
					'vars' => ['props' => ['--title-size-xs' => '%spx']]
				],
				'group'   => '_g_base_sizes'
			],
			[
				'name'  => 'css_title_size_s',
				'value' => '',
				'label' => esc_html__('Base: Small Title', 'bunyad-admin'),
				'desc'  => '',
				'type'  => 'number',
				'style' => 'inline-sm',
				'css'   => [
					'vars' => ['props' => ['--title-size-s' => '%spx']]
				],
				'group'   => '_g_base_sizes'
			],

			[
				'name'  => 'css_title_size_n',
				'value' => '',
				'label' => esc_html__('Base: Normal Title', 'bunyad-admin'),
				'desc'  => '',
				'type'  => 'number',
				'style' => 'inline-sm',
				'css'   => [
					'vars' => ['props' => ['--title-size-n' => '%spx']]
				],
				'group'   => '_g_base_sizes'
			],

			[
				'name'  => 'css_title_size_m',
				'value' => '',
				'label' => esc_html__('Base: Medium Title', 'bunyad-admin'),
				'desc'  => '',
				'type'  => 'number',
				'style' => 'inline-sm',
				'css'   => [
					'vars' => ['props' => ['--title-size-m' => '%spx']]
				],
				'group'   => '_g_base_sizes'
			],

			[
				'name'  => 'css_title_size_l',
				'value' => '',
				'label' => esc_html__('Base: Large Title', 'bunyad-admin'),
				'desc'  => '',
				'type'  => 'number',
				'style' => 'inline-sm',
				'css'   => [
					'vars' => ['props' => ['--title-size-l' => '%spx']]
				],
				'group'   => '_g_base_sizes'
			],

			[
				'name'  => 'css_title_size_xl',
				'value' => '',
				'label' => esc_html__('Base: X-Large Title', 'bunyad-admin'),
				'desc'  => '',
				'type'  => 'number',
				'style' => 'inline-sm',
				'css'   => [
					'vars' => ['props' => ['--title-size-xl' => '%spx']]
				],
				'group'   => '_g_base_sizes'
			],

			[
				'name'  => 'css_title_fw_bold',
				'value' => '',
				'label' => esc_html__('Title Bold Weight', 'bunyad-admin'),
				'desc'  => '',
				'type'  => 'number',
				'style' => 'inline-sm',
				'css'   => [
					'vars' => ['props' => ['--title-fw-bold' => '%s']]
				],
				'group'   => '_g_base_sizes'
			],
			[
				'name'  => 'css_title_fw_semi',
				'value' => '',
				'label' => esc_html__('Title Semi-bold Weight', 'bunyad-admin'),
				'desc'  => '',
				'type'  => 'number',
				'style' => 'inline-sm',
				'css'   => [
					'vars' => ['props' => ['--title-fw-semi' => '%s']]
				],
				'group'   => '_g_base_sizes'
			],			


			// [
			// 	'name'  => 'css_base_text_size',
			// 	'value' => '',
			// 	'label' => esc_html__('Base: Text Size', 'bunyad-admin'),
			// 	'desc'  => 'Affects body text in some widgets, comments, author bio etc.',
			// 	'type'  => 'number',
			// 	'style' => 'inline-sm',
			// 	'css'   => [
			// 		'vars' => ['props' => ['--text-size' => '%spx']]
			// 	],
			// 	'group'   => '_g_base_sizes'
			// ],
			

	// // Group: Post Content
	// [
	// 	'name'  => '_g_post_content_body',
	// 	'label' => esc_html__('Post/Page Body', 'bunyad-admin'),
	// 	'desc'  => 'These settings apply to single posts & pages.',
	// 	'type'  => 'group',
	// 	'style' => 'collapsible',
	// ],

	/**
	 * Group: Google Font Settings
	 */
	[
		'name'  => '_g_google_fonts',
		'label' => esc_html__('Google Font Settings', 'bunyad-admin'),
		'type'  => 'group',
		'style' => 'collapsible'
	],

		[
			'name'    => 'google_fonts_charset',
			'label'   => esc_html__('Google Fonts Charsets', 'bunyad-admin'),
			'desc'    => esc_html__('Not generally required. Sometimes, an additional character sets maybe necessary.', 'bunyad-admin'),
			'value'   => [],
			'type'    => 'checkboxes',
			'options' => [
				'latin'        => 'Latin',
				'latin-ext'    => 'Latin Extended',
				'cyrillic'     => 'Cyrillic',
				'cyrillic-ext' => 'Cyrillic Extended', 
				'greek'        => 'Greek',
				'greek-ext'    => 'Greek Extended',
				'vietnamese'   => 'Vietnamese',
				'hebrew'       => 'Hebrew',
				'devanagari'   => 'Devanagari',
				'thai'         => 'Thai',
				'korean'       => 'Korean',
			],
			'group' => '_g_google_fonts',
		],

		[
			'name'  => 'font_display',
			'label' => esc_html__('Fonts Display Swap', 'bunyad-admin'),
			'desc'  => sprintf(
				esc_html__('%sRead details here%s. You can control how font is rendered while it loads. Swap is best if you have slow connection users. Block if you dont want to see a different font at all.', 'bunyad-admin'),
				'<a href="https://plugins.theme-sphere.com/docs/sgf-pro/pages/font-display-behavior/#meaning-of-each-option" target="_blank">', '</a>'
			),
			'value'   => '',
			'type'    => 'select',
			'style'   => 'inline-sm',
			'options' => [
				''         => 'Default/Auto',
				'swap'     => 'Swap',
				'block'    => 'Block',
				'fallback' => 'Fallback',
				'optional' => 'Optional',
			],
			'group' => '_g_google_fonts',
		],

		[
			'name'  => 'google_fonts_disable',
			'label' => esc_html__('Force Disable Google Fonts', 'bunyad-admin'),
			'desc'  => 'Disables all theme Google Fonts enqueues. Will also disable Elementor fonts.',
			'value'   => 0,
			'type'    => 'toggle',
			'style'   => 'inline-sm',
			'group' => '_g_google_fonts',
		],


	/**
	 * Group: Typekit
	 */
	[
		'name'  => '_g_typekit',
		'label' => esc_html__('Adobe Fonts / TypeKit', 'bunyad-admin'),
		'type'  => 'group',
		'style' => 'collapsible'
	],

		[
			'name'  => 'typekit_id',
			'label' => esc_html__('Adobe Fonts Project ID', 'bunyad-admin'),
			'value' => '',
			'desc'  => sprintf(
				esc_html__('Refer to the %sdocumentation%s to learn about using Typekit.', 'bunyad-admin'),
				'<a href="https://theme-sphere.com/docs/smartmag/#typekit">', '</a>'
			),
			'type'  => 'text',
			'group' => '_g_typekit',
		],

	[
		'name'  => '_n_more_colors',
		'type'  => 'message',
		'label' => 'More Colors & Typography',
		'text'  => $notice_settings,
		'style' => 'message-info',
	],
];

$options['colors-fonts'] = [
	'sections' => [[
		'title'  => esc_html__('Colors & Typography', 'bunyad-admin'),
		'id'     => 'colors-fonts',
		'fields' => $fields,
	]]
];

return $options;