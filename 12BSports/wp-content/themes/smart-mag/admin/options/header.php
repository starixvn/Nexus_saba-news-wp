<?php
/**
 * Header & Nav Options
 */

$options = is_array($options) ? $options : [];

/**
 * Fields: Presets.
 */
$fields_presets = [
	[
		'name'    => '_n_heade_presets',
		'type'    => 'message',
		'label'   => 'Premade Headers',
		'text'    => 'Applying a preset will undo your header customizations and replace it with the pre-made header configs.',
		'style'   => 'message-info',
	],
	[
		'name'    => 'header_preset',
		'label'   => esc_html__('Header Preset', 'bunyad-admin'),
		'desc'     => '',
		'value'   => '',
		'type'    => 'radio-image',
		'classes' => 'space-lg',
		'options' => [
			'default'   => [
				'label'  => esc_html__('Default', 'bunyad-admin'),
				'image' => get_template_directory_uri() . '/admin/images/header-default.jpg',
			],
			'good-news' => [
				'label'  => esc_html__('Modern Dark (GoodNews)', 'bunyad-admin'),
				'image' => get_template_directory_uri() . '/admin/images/header-good-news.jpg',
			],
			'tech'   => [
				'label'   => esc_html__('Modern Light (Tech)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-tech-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-tech.jpg',
			],
			'tech-2'   => [
				'label'   => esc_html__('Simple Light (Tech 2)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-tech-2-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-tech-2.jpg',
			],
			// 'dark'   => [
			// 	'label'  => esc_html__('Simple Dark', 'bunyad-admin'),
			// 	'image' => get_template_directory_uri() . '/admin/images/header-dark.png',
			// ],
			// 'light'   => [
			// 	'label'  => esc_html__('Simple Light', 'bunyad-admin'),
			// 	'image' => get_template_directory_uri() . '/admin/images/header-light.png',
			// ],
			'trendy'   => [
				'label'   => esc_html__('Old Light (Trendy)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-trendy-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-trendy.jpg',
			],
			'zine'   => [
				'label'  => esc_html__('Old Dark (Zine)', 'bunyad-admin'),
				'image' => get_template_directory_uri() . '/admin/images/header-zine-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-zine.jpg',
			],
			'sports'   => [
				'label'  => esc_html__('Simple Dark (Sports)', 'bunyad-admin'),
				'image' => get_template_directory_uri() . '/admin/images/header-sports-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-sports.jpg',
			],
			'gaming'   => [
				'label'  => esc_html__('Gaming / Bold', 'bunyad-admin'),
				'image' => get_template_directory_uri() . '/admin/images/header-gaming.jpg',
			],
			'geeks-empire'   => [
				'label'   => esc_html__('Centered (Geeks Empire)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-geeks-empire-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-geeks-empire.jpg',
			],
			'informed'   => [
				'label'   => esc_html__('Minimal Dark (Informed)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-informed-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-informed.jpg',
			],
			'social-life'   => [
				'label'   => esc_html__('Colored Mixed (SocialLife)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-social-life-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-social-life.jpg',
			],
			'classic'   => [
				'label'  => esc_html__('Legacy / Classic', 'bunyad-admin'),
				'image' => get_template_directory_uri() . '/admin/images/header-classic-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-classic.jpg',
			],
			'news'   => [
				'label'   => esc_html__('Simple Dark 2 (News)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-news-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-news.jpg',
			],
			'prime-mag'   => [
				'label'   => esc_html__('Traditional (PrimeMag)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-prime-mag-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-prime-mag.jpg',
			],
			'financial'   => [
				'label'   => esc_html__('Mixed (Financial)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-financial-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-financial.jpg',
			],
			'citybuzz'   => [
				'label'   => esc_html__('Colorful (CityBuzz)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-citybuzz-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-citybuzz.jpg',
			],
			'pro-mag'   => [
				'label'   => esc_html__('Compact Light (ProMag)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-pro-mag-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-pro-mag.jpg',
			],
			'coinbase'   => [
				'label'   => esc_html__('Compact Dark (CoinBase)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-coinbase-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-coinbase.jpg',
			],
			'fitness'   => [
				'label'   => esc_html__('Mixed (Fitness)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-fitness-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-fitness.jpg',
			],
			'gossip-mag'   => [
				'label'   => esc_html__('Modern Light (GossipMag)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-gossip-mag-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-gossip-mag.jpg',
			],
			'mag-studio'   => [
				'label'   => esc_html__('Colored Light (MagStudio)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-mag-studio-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-mag-studio.jpg',
			],
			'game-zone'   => [
				'label'   => esc_html__('Colored Dark (GameZone)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-game-zone-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-game-zone.jpg',
			],
			'health'   => [
				'label'   => esc_html__('Complex Light (Health)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-health-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-health.jpg',
			],
			'smart-times'   => [
				'label'   => esc_html__('Wide Light (SmartTimes)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-smart-times-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-smart-times.jpg',
			],
			'news-time'   => [
				'label'   => esc_html__('Classic 2 (NewsTime)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-news-time-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-news-time.jpg',
			],
			'news-board'   => [
				'label'   => esc_html__('Clean Light (NewsBoard)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-news-board-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-news-board.jpg',
			],
			'tech-drop'   => [
				'label'   => esc_html__('Extra Light (TechDrop)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-tech-drop-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-tech-drop.jpg',
			],
			'blogger'   => [
				'label'   => esc_html__('Mixed (Blogger)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-blogger-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-blogger.jpg',
			],
			'tech-blog'   => [
				'label'   => esc_html__('Modern Tech (TechBlog)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-tech-blog-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-tech-blog.jpg',
			],
			'friday-mag'   => [
				'label'   => esc_html__('Modern Mixed (FridayMag)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-friday-mag-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-friday-mag.jpg',
			],
			'smart-post'   => [
				'label'   => esc_html__('Colored Compact (SmartPost)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-smart-post-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-smart-post.jpg',
			],
			'world-mag'   => [
				'label'   => esc_html__('Compact (WorldMag)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-world-mag-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-world-mag.jpg',
			],
			'everyday-news'   => [
				'label'   => esc_html__('Colorul Mixed (EverydayNews)', 'bunyad-admin'),
				'image'   => get_template_directory_uri() . '/admin/images/header-everyday-news-thumb.jpg',
				'preview' => get_template_directory_uri() . '/admin/images/header-everyday-news.jpg',
			],
		],
		'transport' => 'postMessage',
		'json_data' => 'admin/options/header/presets-data.php',
	],
];

/**
 * Fields: Header Layout
 */
$fields_layout = [
	[
		'name'    => 'header_layout',
		'label'   => esc_html__('Header Skin', 'bunyad-admin'),
		'desc'     => '',
		'value'   => 'smart-a',
		'type'    => 'select',
		'options' => [
			'smart-a'      => esc_html__('Smart Header 1', 'bunyad-admin'),
			'smart-b'      => esc_html__('Smart Header 2', 'bunyad-admin'),
			'smart-legacy' => esc_html__('Legacy: Classic', 'bunyad-admin'),
		],
	],

	[
		'name'    => '_n_header_layout',
		'type'    => 'message',
		'label'   => '',
		'text'    => 'There are customizations active that may change the look of the selected header style. <a href="#" class="preset-reset">Click here</a> to reset them to defaults.',
		'style'   => 'message-alert',
		'classes' => 'bunyad-cz-hidden',
	],

	[
		'name'     => 'header_width',
		'label'    => esc_html__('Width', 'bunyad-admin'),
		'desc'     => esc_html__('Width can be overriden below, under each row.', 'bunyad-admin'),
		'value'    => 'full-wrap',
		'type'     => 'select',
		'style'    => 'inline-sm',
		'options'  => $_common['header_widths'],
	],

	[
		'name'     => 'css_header_width',
		'label'    => esc_html__('Custom Wrap Width', 'bunyad-admin'),
		'desc'     => esc_html__('Only applies when using site width option above or for any of the rows.', 'bunyad-admin'),
		'value'    => '',
		'type'     => 'number',
		'style'    => 'inline-sm',
		'css'      => [
			'.smart-head-main' => ['props' => ['--main-width' => '%dpx']]
		],
	],

	[
		'name'     => 'css_header_c_shadow',
		'label'    => esc_html__('Drop Shadow Color', 'bunyad-admin'),
		'desc'     => 'Tip: Set it to white or 0 transparency to hide.',
		'value'    => '',
		'type'     => 'color-alpha',
		'style'    => 'inline-sm',
		'css'      => [
			'.smart-head-main' => ['props' => ['--c-shadow' => '%s']]
		],
	],
];

$header_rows = [
	[
		'name'  => '_g_{headKey}_rows_{key}',
		'type'  => 'group',
		'style' => 'collapsible',
		'template' => [
			'top' => [
				'label' => esc_html__('Top Row', 'bunyad-admin'),
			],
			'mid' => [
				'label' => esc_html__('Main Row', 'bunyad-admin'),
			],
			'bot' => [
				'label' => esc_html__('Bottom Row', 'bunyad-admin'),
			],

		]
	],
		[
			'name'     => '{headKey}_items_{key}_left',
			'label'    => esc_html__('Elements Left', 'bunyad-admin'),
			'desc'     => '',
			'value'    => [],
			'type'     => 'selectize',
			'options'  => $_common['header_elements'],
			'sortable' => true,
			'group'    => '_g_{headKey}_rows_{key}',
			'template' => [
				'header_mob' => [
					'options' => $_common['header_mob_elements']
				]
			]
		],

		[
			'name'     => '{headKey}_items_{key}_center',
			'label'    => esc_html__('Elements Center', 'bunyad-admin'),
			'desc'     => '',
			'value'    => [],
			'type'     => 'selectize',
			'options'  => $_common['header_elements'],
			'sortable' => true,
			'group'    => '_g_{headKey}_rows_{key}',
			'template' => [
				'header_mob' => [
					'options' => $_common['header_mob_elements']
				]
			]
		],

		[
			'name'     => '{headKey}_items_{key}_right',
			'label'    => esc_html__('Elements Right', 'bunyad-admin'),
			'desc'     => '',
			'value'    => [],
			'type'     => 'selectize',
			'options'  => $_common['header_elements'],
			'sortable' => true,
			'group'    => '_g_{headKey}_rows_{key}',
			'template' => [
				'header_mob' => [
					'options' => $_common['header_mob_elements']
				]
			]
		],
		

		[
			'name'     => '{headKey}_scheme_{key}',
			'label'    => esc_html__('Color Scheme', 'bunyad-admin'),
			'desc'     => '',
			'value'    => 'dark',
			'type'     => 'select',
			'options'  => [
				'light' => esc_html__('Light', 'bunyad-admin'),
				'dark'  => esc_html__('Dark', 'bunyad-admin'),
			],
			'group'    => '_g_{headKey}_rows_{key}',
		],

		[
			'name'     => '{headKey}_width_{key}',
			'label'    => esc_html__('Width', 'bunyad-admin'),
			'desc'     => '',
			'value'    => '',
			'type'     => 'select',
			'options'  => [
				'' => esc_html__('Inherit', 'bunyad-admin'),
			] + $_common['header_widths'],
			'group'    => '_g_{headKey}_rows_{key}',
		],

		[
			'name'     => 'css_{headKey}_height_{key}',
			'label'    => esc_html__('Height', 'bunyad-admin'),
			'desc'     => '',
			'value'    => '',
			'type'     => 'number',
			'style'    => 'inline-sm',
			'css'      => [
				'.smart-head{prefix} .smart-head-{key}' => ['props' => ['--head-h' => '%spx']]
			],
			'group'    => '_g_{headKey}_rows_{key}',
		],

		[
			'name'      => 'css_{headKey}_grad_{key}',
			'label'     => esc_html__('Use Gradient BG', 'bunyad-admin'),
			'desc'      => '',
			'value'     => 0,
			'type'      => 'toggle',
			'style'     => 'inline-sm',
			'classes'   => 'sep-top',
			// 'transport' => 'postMessage',
			'css'       => [
				'.smart-head{prefix} .smart-head-{key}' => [
					'props' => ['background' => 
						'linear-gradient({css_{headKey}_grad_{key}_angle}deg, {css_{headKey}_grad_{key}_c1} {css_{headKey}_grad_{key}_pos1}%, {css_{headKey}_grad_{key}_c2} 100%)'
					]
				]
			],
			'group'     => '_g_{headKey}_rows_{key}',
		],

		[
			'name'     => 'css_{headKey}_grad_{key}_c1',
			'label'    => esc_html__('Gradient Color 1', 'bunyad-admin'),
			'desc'     => '',
			'value'    => '',
			'type'     => 'color',
			'style'    => 'inline-sm',
			'transport' => 'postMessage',
			'css'      => [],
			'group'    => '_g_{headKey}_rows_{key}',
			'context'  => [['key' => 'css_{headKey}_grad_{key}', 'value' => 1]]
		],

		[
			'name'     => 'css_{headKey}_grad_{key}_c2',
			'label'    => esc_html__('Gradient Color 2', 'bunyad-admin'),
			'desc'     => '',
			'value'    => '',
			'type'     => 'color',
			'style'    => 'inline-sm',
			'transport' => 'postMessage',
			'css'      => [],
			'group'    => '_g_{headKey}_rows_{key}',
			'context'  => [['key' => 'css_{headKey}_grad_{key}', 'value' => 1]]
		],
		[
			'name'     => 'css_{headKey}_grad_{key}_pos1',
			'label'    => esc_html__('Color 1 Position', 'bunyad-admin'),
			'desc'     => '',
			'value'    => '0',
			'type'     => 'number',
			'input_attrs' => ['min' => 0, 'max' => 10, 'step' => 1],
			'style'    => 'inline-sm',
			'transport' => 'postMessage',
			'css'      => [],
			'group'    => '_g_{headKey}_rows_{key}',
			'context'  => [['key' => 'css_{headKey}_grad_{key}', 'value' => 1]]
		],
		[
			'name'     => 'css_{headKey}_grad_{key}_angle',
			'label'    => esc_html__('Gradient Angle', 'bunyad-admin'),
			'desc'     => '',
			'value'    => '90',
			'type'     => 'number',
			'input_attrs' => ['min' => 0, 'max' => 360, 'step' => 1],
			'style'    => 'inline-sm',
			'transport' => 'postMessage',
			'classes'   => 'sep-bottom',
			'css'      => [],
			'group'    => '_g_{headKey}_rows_{key}',
			'context'  => [['key' => 'css_{headKey}_grad_{key}', 'value' => 1]]
		],

		[
			'name'     => 'css_{headKey}_bg_{key}',
			'label'    => esc_html__('Background Color', 'bunyad-admin'),
			'desc'     => '',
			'value'    => '',
			'type'     => 'color-alpha',
			'style'    => 'inline-sm',
			'css'      => [
				'.smart-head{prefix} .smart-head-{key}' => [
					'props' => ['background-color' => '%s']
				]
			],
			'group'    => '_g_{headKey}_rows_{key}',
			'context'  => [['key' => 'css_{headKey}_grad_{key}', 'value' => 0]]
		],
		[
			'name'     => 'css_{headKey}_bg_sd_{key}',
			'label'    => esc_html__('Dark: Background Color', 'bunyad-admin'),
			'desc'     => '',
			'value'    => '',
			'type'     => 'color-alpha',
			'style'    => 'inline-sm',
			'css'      => [
				'.s-dark .smart-head{prefix} .smart-head-{key},
				.smart-head{prefix} .s-dark.smart-head-{key}' => [
					'props' => ['background-color' => '%s']
				]
			],
			'group'    => '_g_{headKey}_rows_{key}',
			'context'  => [['key' => 'css_{headKey}_grad_{key}', 'value' => 0]]
		],

		[
			'name'    => 'css_{headKey}_bg_image_{key}',
			'value'   => '',
			'label'   => esc_html__('Background Image', 'bunyad-admin'),
			'desc'    => '',
			'type'    => 'upload',
			'options' => [
				'type' => 'image'
			],
			'bg_type' => ['value' => 'cover-nonfixed'],
			'css'     => [
				'.smart-head{prefix} .smart-head-{key}' => ['props' => ['background-image' =>  'url(%s)']]
			],
			'group'    => '_g_{headKey}_rows_{key}',
			'context'  => [['key' => 'css_{headKey}_grad_{key}', 'value' => 0]]
		],

		[
			'name'     => 'css_{headKey}_border_top_{key}',
			'label'    => esc_html__('Border Top', 'bunyad-admin'),
			'desc'     => '',
			'value'    => '',
			'type'     => 'number',
			'style'    => 'inline-sm',
			'css'      => [
				'.smart-head{prefix} .smart-head-{key}' => ['props' => ['border-top-width' => '%spx']]
			],
			'context'  => [['key' => 'css_{headKey}_border_grad_{key}', 'value' => 0]],
			'group'    => '_g_{headKey}_rows_{key}',
		],

		[
			'name'     => 'css_{headKey}_c_border_top_{key}',
			'label'    => esc_html__('Border Top Color', 'bunyad-admin'),
			'desc'     => '',
			'value'    => '',
			'type'     => 'color',
			'style'    => 'inline-sm',
			'css'      => [
				'.smart-head{prefix} .smart-head-{key}' => ['props' => ['border-top-color' => '%s']]
			],
			'context'  => [['key' => 'css_{headKey}_border_grad_{key}', 'value' => 0]],
			'group'    => '_g_{headKey}_rows_{key}',
		],

		[
			'name'     => 'css_{headKey}_c_border_top_sd_{key}',
			'label'    => esc_html__('Dark: Border Top', 'bunyad-admin'),
			'desc'     => '',
			'value'    => '',
			'type'     => 'color',
			'style'    => 'inline-sm',
			'css'      => [
				'.s-dark .smart-head{prefix} .smart-head-{key},
				.smart-head{prefix} .s-dark.smart-head-{key}' => [
					'props' => ['border-top-color' => '%s']
				]
			],
			'context'  => [['key' => 'css_{headKey}_border_grad_{key}', 'value' => 0]],
			'group'    => '_g_{headKey}_rows_{key}',
		],

		[
			'name'     => 'css_{headKey}_border_bottom_{key}',
			'label'    => esc_html__('Border Bottom', 'bunyad-admin'),
			'desc'     => '',
			'value'    => '',
			'type'     => 'number',
			'style'    => 'inline-sm',
			'css'      => [
				'.smart-head{prefix} .smart-head-{key}' => ['props' => ['border-bottom-width' => '%spx']]
			],
			'context'  => [['key' => 'css_{headKey}_border_grad_{key}', 'value' => 0]],
			'group'    => '_g_{headKey}_rows_{key}',
		],

		[
			'name'     => 'css_{headKey}_c_border_bot_{key}',
			'label'    => esc_html__('Border Bottom Color', 'bunyad-admin'),
			'desc'     => '',
			'value'    => '',
			'type'     => 'color',
			'style'    => 'inline-sm',
			'css'      => [
				'.smart-head{prefix} .smart-head-{key}' => ['props' => ['border-bottom-color' => '%s']]
			],
			'context'  => [['key' => 'css_{headKey}_border_grad_{key}', 'value' => 0]],
			'group'    => '_g_{headKey}_rows_{key}',
		],

		[
			'name'     => 'css_{headKey}_c_border_bot_sd_{key}',
			'label'    => esc_html__('Dark: Border Bottom', 'bunyad-admin'),
			'desc'     => '',
			'value'    => '',
			'type'     => 'color',
			'style'    => 'inline-sm',
			'css'      => [
				'.s-dark .smart-head{prefix} .smart-head-{key},
				.smart-head{prefix} .s-dark.smart-head-{key}' => [
					'props' => ['border-bottom-color' => '%s']
				]
			],
			'context'  => [['key' => 'css_{headKey}_border_grad_{key}', 'value' => 0]],
			'group'    => '_g_{headKey}_rows_{key}',
		],

		[
			'name'      => 'css_{headKey}_border_grad_{key}',
			'label'     => esc_html__('Use Gradient Border', 'bunyad-admin'),
			'desc'      => '',
			'value'     => 0,
			'type'      => 'toggle',
			'style'     => 'inline-sm',
			'classes'   => 'sep-top',
			// 'transport' => 'postMessage',
			'css'       => [
				'.smart-head{prefix} .smart-head-{key}' => [
					'props' => [
						'border-image' => 
							'linear-gradient(90deg, {css_{headKey}_border_grad_{key}_c1} {css_{headKey}_grad_{key}_pos1}%, {css_{headKey}_border_grad_{key}_c2} 100%)',
						'border-image-slice' => '1',
						'border-image-width' => '{css_{headKey}_border_grad_{key}_width}px 0 0 0',
					]
				]
			],
			'group'     => '_g_{headKey}_rows_{key}',
		],
		[
			'name'      => 'css_{headKey}_border_grad_{key}_width',
			'label'     => esc_html__('Border Width', 'bunyad-admin'),
			'desc'      => '',
			'value'     => '',
			'type'      => 'number',
			'style'     => 'inline-sm',
			'css'       => [
				'.smart-head{prefix} .smart-head-{key}' => [
					'props' => ['border-width' => '%dpx 0']
				]
			],
			'context'  => [['key' => 'css_{headKey}_border_grad_{key}', 'value' => 1]],
			'group'     => '_g_{headKey}_rows_{key}',
		],
		[
			'name'      => 'css_{headKey}_border_grad_{key}_pos',
			'label'     => esc_html__('Apply To Border', 'bunyad-admin'),
			'desc'      => '',
			'value'     => 'top',
			'type'      => 'select',
			'style'     => 'inline-sm',
			'options'   => [

				'top' => esc_html__('Top', 'bunyad-admin'),
				'bottom' => esc_html__('Bottom', 'bunyad-admin'),
			],
			'css'       => [
				'.smart-head{prefix} .smart-head-{key}' => [
					'props' => [
						'condition' => [
							'bottom' => ['border-image-width' => '0 0 {css_{headKey}_border_grad_{key}_width}px 0'],
						],
					]
				]
			],
			'context'  => [['key' => 'css_{headKey}_border_grad_{key}', 'value' => 1]],
			'group'     => '_g_{headKey}_rows_{key}',
		],
		[
			'name'     => 'css_{headKey}_border_grad_{key}_c1',
			'label'    => esc_html__('Gradient Color 1', 'bunyad-admin'),
			'desc'     => '',
			'value'    => '',
			'type'     => 'color',
			'style'    => 'inline-sm',
			'transport' => 'postMessage',
			'css'      => [],
			'group'    => '_g_{headKey}_rows_{key}',
			'context'  => [['key' => 'css_{headKey}_border_grad_{key}', 'value' => 1]]
		],
		[
			'name'     => 'css_{headKey}_border_grad_{key}_c2',
			'label'    => esc_html__('Gradient Color 2', 'bunyad-admin'),
			'desc'     => '',
			'value'    => '',
			'type'     => 'color',
			'style'    => 'inline-sm',
			'transport' => 'postMessage',
			'css'      => [],
			'group'    => '_g_{headKey}_rows_{key}',
			'context'  => [['key' => 'css_{headKey}_border_grad_{key}', 'value' => 1]]
		],
		[
			'name'     => 'css_{headKey}_border_grad_{key}_pos1',
			'label'    => esc_html__('Color 1 Position', 'bunyad-admin'),
			'desc'     => '',
			'value'    => '0',
			'type'     => 'number',
			'input_attrs' => ['min' => 0, 'max' => 10, 'step' => 1],
			'style'    => 'inline-sm',
			'transport' => 'postMessage',
			'classes'   => 'sep-bottom',
			'css'      => [],
			'group'    => '_g_{headKey}_rows_{key}',
			'context'  => [['key' => 'css_{headKey}_border_grad_{key}', 'value' => 1]]
		],

		[
			'name'    => 'css_{headKey}_inner_pad_{key}',
			'label'   => esc_html__('Inner Padding', 'bunyad-admin'),
			'type'    => 'dimensions',
			'value'   => [],
			'devices' => ['main', 'medium'],
			'css'     => [
				'.smart-head{prefix} .smart-head-{key} > .inner' => ['dimensions' => 'padding']
			],
			'group'    => '_g_{headKey}_rows_{key}',
		],	
];

$layout_tpl = [];
\Bunyad\Util\repeat_options(
	$header_rows,
	[
		'top' => [],
		'mid' => [
			'overrides' => [
				'{headKey}_items_{key}_left' => [
					'value' => ['logo', 'nav-menu']
				],
				'{headKey}_items_{key}_right' => [
					'value' => ['social-icons', 'search']
				],
			]
		],
		'bot' => [],
	],
	$layout_tpl,
	[
		'replace_in' => ['css', 'group', 'template', 'context'],
	]
);

\Bunyad\Util\repeat_options(
	$layout_tpl,
	[
		'header' => []
	],
	$fields_layout,
	[
		'replace_in'   => ['css', 'group', 'context'],
		'key'          => '{headKey}',
		'replacements' => ['{prefix}' => '-main']
	]
);


$fields_layout = array_merge($fields_layout, [
	/**
	 * Group: Header Sticky Bar
	 */
	[
		'name'  => '_g_header_sticky',
		'label' => esc_html__('Sticky Bar', 'bunyad-admin'),
		'type'  => 'group',
		'style' => 'collapsible',
	],
	[
		'name'     => 'header_sticky',
		'label'    => esc_html__('Sticky Bar', 'bunyad-admin'),
		// 'desc'     => esc_html__('Width can be overriden below, under each row.', 'bunyad-admin'),
		'value'    => 'auto',
		'type'     => 'select',
		'style'    => 'inline-sm',
		'options'  => [
			''     => esc_html__('Disabled', 'bunyad-admin'),
			'auto' => esc_html__('Auto', 'bunyad-admin'),
			'top'  => esc_html__('Top Row', 'bunyad-admin'),
			'mid'  => esc_html__('Main Row', 'bunyad-admin'),
			'bot'  => esc_html__('Bottom Row', 'bunyad-admin'),
		],
		'group'    => '_g_header_sticky'
	],

	[
		'name'     => 'header_sticky_full',
		'label'    => esc_html__('Force Full Width', 'bunyad-admin'),
		'desc'     => esc_html__('Use full width row for sticky even if selected row is site width.', 'bunyad-admin'),
		'value'    => 0,
		'type'     => 'toggle',
		'style'    => 'inline-sm',
		'context'  => [['key' => 'header_sticky', 'value' => '', 'compare' => '!=']],
		'group'    => '_g_header_sticky'
	],

	[
		'name'     => 'header_sticky_type',
		'label'    => esc_html__('Sticky Type', 'bunyad-admin'),
		'desc'     => esc_html__('Fixed is always visible on scroll, whereas Smart appears when the user scrolls up.', 'bunyad-admin'),
		'value'    => 'smart',
		'type'     => 'select',
		'style'    => 'inline-sm',
		'options'  => [
			'fixed'   => esc_html__('Fixed (Always Visible)', 'bunyad-admin'),
			'smart'   => esc_html__('Smart (On Scrolling Up)', 'bunyad-admin'),
		],
		'context'  => [['key' => 'header_sticky', 'value' => '', 'compare' => '!=']],
		'group'    => '_g_header_sticky'
	],

	[
		'name'     => 'css_header_sticky_height',
		'label'    => esc_html__('Sticky Custom Height', 'bunyad-admin'),
		'value'    => '',
		'type'     => 'number',
		'style'    => 'inline-sm',
		'context'  => [['key' => 'header_sticky', 'value' => '', 'compare' => '!=']],
		'css'      => [
			'.smart-head-main .smart-head-sticky' => [
				'props' => [
					'max-height' => '%dpx',
					'--head-h'   => '%dpx',
				]
			]
		],
		'group'    => '_g_header_sticky'
	],
]);

// Misc.
// $fields_layout = array_merge($fields_layout, [
// 	/**
// 	 * Group: Misc
// 	 */
// 	[
// 		'name'  => '_g_header_layout_misc',
// 		'label' => esc_html__('Common / Misc Design', 'bunyad-admin'),
// 		'type'  => 'group',
// 		'style' => 'collapsible',
// 	],
// ]);


/**
 * Mobile Header
 */
$fields_mobile = [
	[
		'name'     => 'header_mob_sticky',
		'label'    => esc_html__('Sticky Bar', 'bunyad-admin'),
		// 'desc'     => esc_html__('Width can be overriden below, under each row.', 'bunyad-admin'),
		'value'    => 'mid',
		'type'     => 'select',
		'style'    => 'inline-sm',
		'options'  => [
			''     => esc_html__('Disabled', 'bunyad-admin'),
			'top'  => esc_html__('Top Row', 'bunyad-admin'),
			'mid'  => esc_html__('Main Row', 'bunyad-admin'),
		]
	],

	[
		'name'     => 'header_mob_sticky_type',
		'label'    => esc_html__('Sticky Type', 'bunyad-admin'),
		'desc'     => esc_html__('Fixed is always visible on scroll, whereas Smart appears when the user scrolls up.', 'bunyad-admin'),
		'value'    => 'smart',
		'type'     => 'select',
		'style'    => 'inline-sm',
		'options'  => [
			'fixed'   => esc_html__('Fixed (Always Visible)', 'bunyad-admin'),
			'smart'   => esc_html__('Smart (On Scrolling Up)', 'bunyad-admin'),
		],
		'context'  => [['key' => 'header_sticky', 'value' => '', 'compare' => '!=']],
		'group'    => '_g_header_sticky'
	],

	[
		'name'     => 'css_header_mob_sticky_height',
		'label'    => esc_html__('Sticky Custom Height', 'bunyad-admin'),
		'value'    => '',
		'type'     => 'number',
		'style'    => 'inline-sm',
		'context'  => [['key' => 'header_mob_sticky', 'value' => '', 'compare' => '!=']],
		'css'      => [
			'.smart-head-mobile .smart-head-sticky' => [
				'props' => [
					'max-height' => '%dpx',
					'--head-h'   => '%dpx',
				]
			]
		]
	],
];
$mobile_layout = [];

\Bunyad\Util\repeat_options(
	$header_rows,
	[
		'top' => [],
		'mid' => [
			'overrides' => [
				'{headKey}_items_{key}_left' => [
					'value' => ['hamburger']
				],
				'{headKey}_items_{key}_center' => [
					'value' => ['logo']
				],
				'{headKey}_items_{key}_right' => [
					'value' => ['search']
				],
			]
		],
	],
	$mobile_layout,
	[
		'replace_in' => ['css', 'group', 'template', 'context'],	
	]
);

\Bunyad\Util\repeat_options(
	$mobile_layout,
	[
		'header_mob' => []
	],
	$fields_mobile,
	[
		'replace_in' => ['css', 'group', 'context'],
		'key'        => '{headKey}',
		'replacements' => ['{prefix}' => '-mobile']
	]
);

$fields_mobile[] = [
	'name'    => '_n_header_mob',
	'type'    => 'message',
	'label'   => 'About Customization',
	'text'    => '
		<p>Elements like logo, search, hamburger etc. each have customization settings specific for mobile header.</p>
		<p>Go back to any supported Element section and you will find a Mobile sub-section.</p>
	',
	'style'   => 'message-info',
];


$_fields = [];
$field_files = [
	'navigation', 
	'nav-small',
	'buttons',
	'hamburger',
	'off-canvas',
	'search',
	'social',
	'switcher',
	'ticker',
	'text',
	'date',
	'cart',
	'auth',
	'logo'
];

foreach ($field_files as $key) {
    $_fields[$key] = include get_theme_file_path('admin/options/header/' . $key . '.php');
}

/**
 * Combined settings
 */
$options['header'] = [
	'title'    => esc_html__('Header & Nav Menu', 'bunyad-admin'),
	'id'       => 'header',
	'sections' => [
		[
			'id'     => 'header-presets',
			'title'  => esc_html__('Premade Headers / Presets', 'bunyad-admin'),
			'fields' => $fields_presets,
		],		
		[
			'id'     => 'header-layout',
			'title'  => esc_html__('Layout: Main Header', 'bunyad-admin'),
			'fields' => $fields_layout,
		],
		[
			'id'     => 'header-nav',
			'title'  => esc_html__('Navigation Menu', 'bunyad-admin'),
			'fields' => $_fields['navigation'],
		],
		[
			'id'     => 'header-mobile',
			'title'  => esc_html__('Layout: Mobile Header', 'bunyad-admin'),
			'fields' => $fields_mobile,
		],
		[
			'id'     => 'header-offcanvas',
			'title'  => esc_html__('Offcanvas / Hamburger Menu', 'bunyad-admin'),
			'fields' => $_fields['off-canvas'],
		],
		[
			'id'     => 'header-nav-small',
			'title'  => esc_html__('Element: Secondary Nav', 'bunyad-admin'),
			'fields' => $_fields['nav-small'],
		],
		[
			'id'     => 'header-social',
			'title'  => esc_html__('Element: Social Icons', 'bunyad-admin'),
			'fields' => $_fields['social'],
		],
		[
			'id'     => 'header-logo',
			'title'  => esc_html__('Element: Logo', 'bunyad-admin'),
			'fields' => $_fields['logo'],
		],
		[
			'id'     => 'header-search',
			'title'  => esc_html__('Element: Search', 'bunyad-admin'),
			'fields' => $_fields['search'],
		],
		[
			'id'     => 'header-switcher',
			'title'  => esc_html__('Element: Dark Switcher', 'bunyad-admin'),
			'fields' => $_fields['switcher'],
		],
		[
			'id'     => 'header-hamburger',
			'title'  => esc_html__('Element: Hamburger Icon', 'bunyad-admin'),
			'fields' => $_fields['hamburger'],
		],
		[
			'id'     => 'header-buttons',
			'title'  => esc_html__('Elements: Buttons', 'bunyad-admin'),
			'fields' => $_fields['buttons'],
		],
		[
			'id'     => 'header-ticker',
			'title'  => esc_html__('Element: News Ticker', 'bunyad-admin'),
			'fields' => $_fields['ticker'],
		],
		[
			'id'     => 'header-date',
			'title'  => esc_html__('Element: Date', 'bunyad-admin'),
			'fields' => $_fields['date'],
		],
		[
			'id'     => 'header-cart',
			'title'  => esc_html__('Element: Cart Icon', 'bunyad-admin'),
			'fields' => $_fields['cart'],
		],
		[
			'id'     => 'header-auth',
			'title'  => esc_html__('Element: Login/Auth', 'bunyad-admin'),
			'fields' => $_fields['auth'],
		],
		[
			'id'     => 'header-text',
			'title'  => esc_html__('Elements: Text/HTML', 'bunyad-admin'),
			'fields' => $_fields['text'],
		],
					
	], // sections
];

return $options;