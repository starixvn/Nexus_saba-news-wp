<?php
/**
 * Element: Secondary/Small Nav
 */
$fields = [
	// [
	// 	'name'   => 'nav_small_hov_style',
	// 	'label'  => esc_html__('Menu Hover Style', 'bunyad-admin'),
	// 	'desc'   => '',
	// 	'type'   => 'select',
	// 	'value'  => 'a',
	// 	'options' => [
	// 		'a' => esc_html__('A: Normal Color', 'bunyad-admin'),
	// 		'b' => esc_html__('B: Line Below', 'bunyad-admin'),
	// 	],
	// 	'style' => 'inline-sm'
	// ],

	[
		'name'    => 'header_nav_small_menu',
		'label'   => esc_html__('Select Menu', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'select',
		'style'   => 'inline-sm',
		'classes' => 'sep-bottom',
		'options' => function() {
			return wp_list_pluck(wp_get_nav_menus(), 'name', 'term_id');
		}
	],

	[
		'name'    => 'css_nav_small_font',
		'label'   => esc_html__('Menu Font Family', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'font-family',
		'value'   => '',
		'css'     => [
			// Also applies to small menu etc.
			'.navigation-small' => ['props' => ['font-family' => '%s']]
		],
	],

	[
		'name'             => 'css_nav_small_typo',
		'label'            => esc_html__('Top-Level Typography', 'bunyad-admin'),
		'desc'             => '',
		'type'             => 'group',
		'group_type'       => 'typography',
		'style'            => 'edit',
		'css'              => '.navigation-small .menu > li > a',
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
		'name'       => 'css_nav_small_drop_typo',
		'label'      => esc_html__('Dropdown Typography', 'bunyad-admin'),
		'desc'       => '',
		'type'       => 'group',
		'group_type' => 'typography',
		'style'      => 'edit',
		'css'        => '.navigation-small .menu > li li a',
		'devices'    => false,
	],
	[
		'name'    => 'css_nav_small_height',
		'label'   => esc_html__('Navigation Height', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.navigation-small' => ['props' => ['height' => '%spx']]
		]
	],
	[
		'name'    => 'css_nav_small_space',
		'label'   => esc_html__('Space Between Items', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head .navigation-small' => ['props' => ['--nav-items-space' => '%spx']]
		]
	],
	[
		'name'  => 'css_nav_small_arrows',
		'label' => esc_html__('Hide Dropdown Arrows', 'bunyad-admin'),
		'desc'  => 'Hide dropdown arrows at top-level menu.',
		'type'  => 'checkbox',
		'value' => 0,
		// 'style' => 'inline-sm',
		'css'   => [
			'.navigation-small .menu > li > a:after' => ['props' => ['display' => 'none']]
		],
	],

	[
		'name'  => 'css_nav_small_first_nopad',
		'label' => esc_html__('Remove First Item Left Space', 'bunyad-admin'),
		'desc'  => 'Remove left spacing for first navigation item.',
		'type'  => 'checkbox',
		'value' => 0,
		// 'style' => 'inline-sm',
		'css'   => [
			'.navigation-small' => ['props' => ['margin-left' => 'calc(-1 * var(--nav-items-space))']]
		],
	],

	[
		'name'    => 'css_nav_small_color',
		'value'   => '',
		'label'   => esc_html__('Links Color', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => ['.s-light .navigation-small' => ['props' => ['--c-nav' => '%s']]],
	],
	[
		'name'    => 'css_nav_small_color_dark',
		'value'   => '',
		'label'   => esc_html__('Dark: Links Color', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => ['.s-dark .navigation-small' => ['props' => ['--c-nav' => '%s']]],
	],
	[
		'name'    => 'css_nav_small_hover',
		'value'   => '',
		'label'   => esc_html__('Top-level Hover', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => ['.s-light .navigation-small' => ['props' => ['--c-nav-hov' => '%s']]],
	],
	[
		'name'    => 'css_nav_small_hover_dark',
		'value'   => '',
		'label'   => esc_html__('Dark: Top-level Hover', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => ['.s-dark .navigation-small' => ['props' => ['--c-nav-hov' => '%s']]],
	],

	[
		'name'  => '_n_small_nav',
		'type'  => 'message',
		'label' => '',
		'text'  => 'Rest of Color settings are applied from the Main Navigation.',
		'style' => 'message-info',
	],
];

return $fields;