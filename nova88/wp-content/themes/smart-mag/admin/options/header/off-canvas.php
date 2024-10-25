<?php
/**
 * Offcanvas menu settings.
 */
$fields = [
	[
		'name'    => '_n_header_offcanvas',
		'type'    => 'message',
		'label'   => '',
		'text'    => '
		<ul>
			<li>Offcanvas menu is shown with the click on Hamburger Icon. So make sure to enable the icon to one of the Header rows from Header Layout settings.</li>
			<li>The mobile logo will also be displayed in offcanvas.</li>
			<li>You may add a widgets to the offcanvas menu by adding them to "Off-Canvas Widgets" widget area.</li>
		</ul>
		',
		'style'   => 'message-info',
	],
	[
		'name'     => 'header_offcanvas_scheme',
		'label'    => esc_html__('Color Scheme', 'bunyad-admin'),
		'desc'     => '',
		'value'    => 'dark',
		'type'     => 'select',
		'options'  => [
			'light' => esc_html__('Light', 'bunyad-admin'),
			'dark'  => esc_html__('Dark', 'bunyad-admin'),
		],
	],

	[
		'name'     => 'header_offcanvas_mobile_widgets',
		'label'    => esc_html__('Mobile: Show Widgets', 'bunyad-admin'),
		'desc'     => 'Show offcanvas widgets on phones/tablets too, if added.',
		'value'    => 1,
		'type'     => 'toggle',
		'style'    => 'inline-sm',
	],

	[
		'name'     => 'header_offcanvas_desktop_menu',
		'label'    => esc_html__('Desktops: Show Menu', 'bunyad-admin'),
		'desc'     => 'As there is usually already a menu on desktop screens, another menu is not recommended.',
		'value'    => 0,
		'type'     => 'toggle',
		'style'    => 'inline-sm',
	],
	
	[
		'name'             => 'css_header_offcanvas_menu_typo',
		'label'            => esc_html__('Typography', 'bunyad-admin'),
		'desc'             => '',
		'type'             => 'group',
		'group_type'       => 'typography',
		'style'            => 'edit',
		'css'              => '.mobile-menu',
		// 'controls'         => ['size', 'weight', 'style', 'transform', 'spacing'],
		// 'controls_options' => [

		// 	// Only top-level.
		// 	'size' => [
		// 		'css' => [
		// 			'.mobile-menu > li > a' => ['props' => ['font-size' => '%spx']],
		// 		]
		// 	],
		// ],
	],

	[
		'name'     => 'css_header_offcanvas_bg',
		'label'    => esc_html__('Background Color', 'bunyad-admin'),
		'desc'     => '',
		'value'    => '',
		'type'     => 'color-alpha',
		'style'    => 'inline-sm',
		'css'      => [
			'.off-canvas' => ['props' => ['background-color' => '%s']]
		],
	],
	[
		'name'     => 'css_header_offcanvas_bg_sd',
		'label'    => esc_html__('Dark: Background Color', 'bunyad-admin'),
		'desc'     => '',
		'value'    => '',
		'type'     => 'color-alpha',
		'style'    => 'inline-sm',
		'css'      => [
			'.s-dark .off-canvas, .off-canvas.s-dark' => ['props' => ['background-color' => '%s']]
		],
	],

	[
		'name'    => 'header_offcanvas_social',
		'label'   => esc_html__('Social Icons', 'bunyad-admin'),
		'value'   => ['facebook', 'twitter', 'instagram'],
		'desc'    => sprintf(
			esc_html__('NOTE: Configure these icons URLs from %1$sSocial Media Links%2$s.', 'bunyad-admin'),
			'<a href="#" class="focus-link is-with-nav" data-section="bunyad-misc-social">',
			'</a>'
		),
		'type'    => 'checkboxes',
		'style'   => 'sortable',
		'options' => $_common['social_services'],
	],
];

return $fields;