<?php
/**
 * Element: Social Icons
 */
$fields = [];

$template = [
	[
		'name'  => '_g_{key}_social',
		'type'  => 'group',
		'style' => 'collapsible',
		'template' => [
			'header' => [
				'label'  => esc_html__('Main Header', 'bunyad-admin'),
				'collapsed' => false,
				'group'     => '',
			],
			'header_mob' => [
				'label' => esc_html__('Mobile Header', 'bunyad-admin'),
				'group'     => '',
			],
		]
	],
	[
		'name'    => '{key}_social_services',
		'label'   => esc_html__('Services to Show', 'bunyad-admin'),
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

	[
		'name'     => '{key}_social_style',
		'label'    => esc_html__('Icons Style', 'bunyad-admin'),
		'desc'     => '',
		'value'    => 'a',
		'type'     => 'select',
		'options'  => [
			'a' => esc_html__('A: Small Icons', 'bunyad-admin'),
			'b' => esc_html__('B: Rounded Icons', 'bunyad-admin'),
			'c' => esc_html__('C: Small with BG Colors', 'bunyad-admin'),
		],
	],

	[
		'name'    => 'css_{key}_social_color',
		'label'   => esc_html__('Icons Color', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix} .spc-social' => ['props' => ['--c-spc-social' => '%s']]
		]
	],
	[
		'name'    => 'css_{key}_social_hov_color',
		'label'   => esc_html__('Icons Hover', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix} .spc-social' => ['props' => ['--c-spc-social-hov' => '%s']]
		]
	],
	[
		'name'    => 'css_{key}_social_color_sd',
		'label'   => esc_html__('Dark: Icons Color', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.s-dark .smart-head-{suffix} .spc-social,
			.smart-head-{suffix} .s-dark .spc-social' => [
				'props' => ['--c-spc-social' => '%s']
			]
		]
	],
	[
		'name'    => 'css_{key}_social_hov_color_sd',
		'label'   => esc_html__('Dark: Icons Hover', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => [
			'.s-dark .smart-head-{suffix} .spc-social,
			.smart-head-{suffix} .s-dark .spc-social' => ['props' => ['--c-spc-social-hov' => '%s']]
		]
	],
	[
		'name'    => 'css_{key}_social_size',
		'label'   => esc_html__('Icons Size', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'number',
		'input_attrs' => ['min' => 8],
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix} .spc-social' => [
				'props' => ['--spc-social-fs' => '%spx']
			]
		]
	],

	[
		'name'    => 'css_{key}_social_box_size',
		'label'   => esc_html__('Icons Box Size', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix} .spc-social' => [
				'props' => ['--spc-social-size' => '%spx']
			]
		]
	],

	[
		'name'    => 'css_{key}_social_space',
		'label'   => esc_html__('Icons Space', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head-{suffix} .spc-social' => [
				'props' => ['--spc-social-space' => '%spx']
			]
		]
	],
];

\Bunyad\Util\repeat_options(
	$template,
	[
		'header'     => [
			'replacements' => ['{suffix}' => 'main'],
			'group'        => '_g_{key}_social'
		],
		'header_mob' => [
			'replacements' => ['{suffix}' => 'mobile'],
			'group'        => '_g_{key}_social'
		],
	],
	$fields,
	[
		'replace_in' => ['css', 'group'],
	]
);

return $fields;