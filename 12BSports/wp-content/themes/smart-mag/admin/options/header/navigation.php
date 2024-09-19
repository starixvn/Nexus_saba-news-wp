<?php
/**
 * Navigation options.
 */
$fields_nav = [
	[
		'name'   => 'header_nav_hov_style',
		'label'  => esc_html__('Menu Hover Style', 'bunyad-admin'),
		'desc'   => '',
		'type'   => 'select',
		'value'  => 'a',
		'options' => [
			'a' => esc_html__('A: Normal Color', 'bunyad-admin'),
			'b' => esc_html__('B: Line Below', 'bunyad-admin'),
		],
		'style' => 'inline-sm'
	],

	[
		'name'    => 'css_nav_font',
		'label'   => esc_html__('Menu Font Family', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'font-family',
		'value'   => '',
		'css'     => [
			// Also applies to small menu etc.
			'.navigation' => ['props' => ['font-family' => '%s']]
		],
	],

	[
		'name'             => 'css_nav_typo',
		'label'            => esc_html__('Top-Level Typography', 'bunyad-admin'),
		'desc'             => '',
		'type'             => 'group',
		'group_type'       => 'typography',
		'style'            => 'edit',
		'css'              => '.navigation-main .menu > li > a',
		'devices'          => false,
		'controls'         => ['family', 'size', 'weight', 'style', 'transform', 'spacing'],
		'controls_options' => [
			'size' => [
				'css' => [
					'{selector}' => [
						'all' => ['props' => ['font-size' => '%spx']],
					
						// Scale down with min of 10px.
						'large' => ['props' => ['font-size' => 'calc(10px + (%spx - 10px) * .7)'], 'value_key' => 'global']
					]
				]
			],
		],
	],
	[
		'name'       => 'css_nav_drop_typo',
		'label'      => esc_html__('Dropdown Typography', 'bunyad-admin'),
		'desc'       => '',
		'type'       => 'group',
		'group_type' => 'typography',
		'style'      => 'edit',
		'css'        => '.navigation-main .menu > li li a',
		'devices'    => false,
	],
	[
		'name'    => 'css_nav_height',
		'label'   => esc_html__('Navigation Height', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.navigation-main' => ['props' => ['height' => '%spx']]
		]
	],
	[
		'name'    => 'css_nav_space',
		'label'   => esc_html__('Space Between Items', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.navigation-main' => ['props' => ['--nav-items-space' => '%spx']]
		]
	],
	[
		'name'  => 'css_nav_arrows',
		'label' => esc_html__('Hide Dropdown Arrows', 'bunyad-admin'),
		'desc'  => 'Hide dropdown arrows at top-level menu.',
		'type'  => 'checkbox',
		'value' => 0,
		// 'style' => 'inline-sm',
		'css'   => [
			'.navigation-main .menu > li > a:after' => ['props' => ['display' => 'none']]
		],
	],

	// [
	// 	'name'  => 'css_nav_arrows',
	// 	'label' => esc_html__('Disable Active Color', 'bunyad-admin'),
	// 	'desc'  => 'Disable coloring of the active item.',
	// 	'type'  => 'checkbox',
	// 	'value' => 0,
	// 	// 'style' => 'inline-sm',
	// 	'css'   => [
	// 		'.navigation-main .current-menu-item:not(:hover)' => ['props' => ['--c-nav-hov' => 'initial']],

	// 	],
	// ],

	[
		'name'  => 'css_nav_first_nopad',
		'label' => esc_html__('Remove First Item Left Space', 'bunyad-admin'),
		'desc'  => 'Remove left spacing for first navigation item.',
		'type'  => 'checkbox',
		'value' => 0,
		// 'style' => 'inline-sm',
		'css'   => [
			'.navigation-main' => ['props' => ['margin-left' => 'calc(-1 * var(--nav-items-space))']]
		],
	],

	[
		'name'  => 'css_nav_hov_b_weight',
		'label' => esc_html__('Hov Line Weight', 'bunyad-admin'),
		'desc'  => 'Remove left spacing for first navigation item.',
		'type'  => 'number',
		'value' => '',
		'style' => 'inline-sm',
		'css'   => [
			'.nav-hov-b .menu > li > a:before' => ['props' => ['border-width' => '%dpx']]
		],
		'context' => [['key' => 'header_nav_hov_style', 'value' => 'b']]
	],
	[
		'name'  => 'css_nav_hov_b_line_equal',
		'label' => esc_html__('No Line Padding', 'bunyad-admin'),
		'desc'  => 'Remove padding on the hover/active line.',
		'type'  => 'toggle',
		'value' => 0,
		'style' => 'inline-sm',
		'css'   => [
			'.nav-hov-b .menu > li > a:before' => [
				'main' => [
					'props' => [
						'width' => 'calc(100% - (var(--nav-items-space, 15px)*2))',
						'left'  => 'var(--nav-items-space)'
					],
					'value_key' => 'global'
				]
			]
		],
		'context' => [['key' => 'header_nav_hov_style', 'value' => 'b']]
	],
	
	[
		'name'  => 'css_mega_menu_inherit_hover',
		'label' => esc_html__('Mega: Inherit Titles Hover', 'bunyad-admin'),
		'desc'  => 'Use dropdown hover color as post titles hover color.',
		'type'  => 'toggle',
		'value' => 0,
		'style' => 'inline-sm',
		'css'   => [
			'.mega-menu .post-title a:hover' => ['props' => ['color' => 'var(--c-nav-drop-hov)']]
		],
	]
];


/**
 * Group: Colors
 */
$nav_colors = [
	[
		'name'  => '_g_nav_colors_{key}',
		'template' => [
			'light' => [
				'label' => esc_html__('Light: Navigation Colors', 'bunyad-admin'),
				'group' => '',
			],
			'dark' => [
				'label' => esc_html__('Dark: Navigation Colors ', 'bunyad-admin'),
				'group' => '',
			]
		],
		'type'  => 'group',
		'style' => 'collapsible',
	],

	// [
	// 	'name'  => 'css_nav_bg_{key}',
	// 	'value' => '',
	// 	'label' => esc_html__('Nav Background Color', 'bunyad-admin'),
	// 	'desc'  => '',
	// 	'type'  => 'color',
	// 	'css'   => [
	// 		'.s-{key} .navigation' => ['props' => [
	// 			'background-color' => '%s',
	// 		]],
	// 	],
	// ],
	[
		'name'    => 'css_nav_color_{key}',
		'value'   => '',
		'label'   => esc_html__('Top-level Links Color', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => ['.s-{key} .navigation-main' => ['props' => ['--c-nav' => '%s']]],
	],
	[
		'name'    => 'css_nav_hover_{key}',
		'value'   => '',
		'label'   => esc_html__('Top-level Hover', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => ['.s-{key} .navigation-main' => ['props' => ['--c-nav-hov' => '%s']]],
	],
	[
		'name'    => 'css_nav_arrow_color_{key}',
		'value'   => '',
		'label'   => esc_html__('Arrow Color', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => ['.s-{key} .navigation' => ['props' => ['--c-nav-blip' => '%s']]]
	],


	// Applies to dropdown and top-level on hov-b.
	[
		'name'    => 'css_hov_bg_{key}',
		'value'   => '',
		'label'   => esc_html__('Hover/Active Background', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => ['.s-{key} .navigation' => ['props' => ['--c-nav-hov-bg' => '%s']]]
		
	],

	/**
	 * Dropdowns.
	 */

	[
		'name'    => 'css_drop_bg_{key}',
		'value'   => '',
		'label'   => esc_html__('Dropdown Background', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => ['.s-{key} .navigation' => ['props' => ['--c-nav-drop-bg' => '%s']]]
	],


	[
		'name'    => 'css_drop_hov_bg_{key}',
		'value'   => '',
		'label'   => esc_html__('Dropdown Hover/Active BG', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => ['.s-{key} .navigation' => ['props' => ['--c-nav-drop-hov-bg' => '%s']]]
		
	],

	[
		'name'    => 'css_drop_color_{key}',
		'value'   => '',
		'label'   => esc_html__('Dropdown Links', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => ['.s-{key} .navigation' => ['props' => ['--c-nav-drop' => '%s']]]
	],
	[
		'name'    => 'css_drop_active_{key}',
		'value'   => '',
		'label'   => esc_html__('Dropdown Links Hover/Active', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => ['.s-{key} .navigation' => ['props' => ['--c-nav-drop-hov' => '%s']]]
	],

	[
		'name'    => 'css_drop_sep_{key}',
		'value'   => '',
		'label'   => esc_html__('Separator Lines', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => ['.s-{key} .navigation' => ['props' => ['--c-nav-drop-sep' => '%s']]]
	],

	[
		'name'    => 'css_mega_menu_sub_bg_{key}',
		'value'   => '',
		'label'   => esc_html__('Mega Menu: Sub-Nav BG', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color',
		'classes' => 'sep-top',
		'style'   => 'inline-sm',
		'css'     => ['.s-{key} .sub-cats' => ['props' => ['background-color' => '%s']]]
	],

	[
		'name'    => 'css_mega_menu_sub_border_{key}',
		'value'   => '',
		'label'   => esc_html__('Mega Menu: Sub-Nav Border', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => ['.s-{key} .sub-cats' => ['props' => ['border-color' => '%s']]]
	],
];

\Bunyad\Util\repeat_options(
	$nav_colors,
	[
		'light' => [
			'group'   => '_g_nav_colors_light',
		],
		'dark'  => [
			'group'   => '_g_nav_colors_dark',
		],
	],
	$fields_nav
);

return $fields_nav;