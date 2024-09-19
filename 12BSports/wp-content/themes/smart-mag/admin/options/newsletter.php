<?php
/**
 * Categories & Archives Options
 */
$fields = [
	[
		'name'    => 'newsletter_service',
		'label'   => esc_html__('Subscribe Service', 'bunyad-admin'),
		'value'   => 'mailchimp',
		'type'    => 'select',
		'style'   => 'inline-sm',
		'options' => [
			'mailchimp' => esc_html__('MailChimp', 'bunyad-admin'),
			'custom'    => esc_html__('Others (HTML / Shortcode)', 'bunyad-admin'),
		],
	],

	[
		'name'    => 'newsletter_submit_url',
		'label'   => esc_html__('MailChimp Submit URL', 'bunyad-admin'),
		'desc'    => esc_html__('Paste the whole form or just the URL in action of the form. See docs to learn more.', 'bunyad-admin'),
		'value'   => '',
		'type'    => 'text',
		// 'style'   => 'inline-sm',
		'context' => [['key' => 'newsletter_service', 'value' => 'mailchimp']]
	],

	[
		'name'    => 'newsletter_custom_form',
		'label'   => esc_html__('Form HTML / Shortcode', 'bunyad-admin'),
		'value'   => '',
		'type'    => 'textarea',
		'context' => [['key' => 'newsletter_service', 'value' => 'custom']],
		'sanitize_callback' => '',
	],

	/**
	 * Group: Shared Styling
	 */
	[
		'name'  => '_g_newsletter_style',
		'label' => esc_html__('Shared Styling', 'bunyad-admin'),
		'desc'  => esc_html__('These will apply to all styles, globally.', 'bunyad-admin'),
		'type'  => 'group',
		'style' => 'collapsible',
		'collapsed' => false,
	],

		[
			'name'       => 'css_newsletter_text_color',
			'label'      => esc_html__('Text Color', 'bunyad-admin'),
			'value'      => '',
			'desc'       => '',
			'type'       => 'color',
			'style'      => 'inline-sm',
			'css'        => [
				'.spc-newsletter' => ['props' => ['color' => '%s']]
			],
			'group'      => '_g_newsletter_style',
		],
		[
			'name'       => 'css_newsletter_text_color_sd',
			'label'      => esc_html__('Dark: Text Color', 'bunyad-admin'),
			'value'      => '',
			'desc'       => '',
			'type'       => 'color',
			'style'      => 'inline-sm',
			'css'        => [
				'.s-dark .spc-newsletter' => ['props' => ['color' => '%s']]
			],
			'group'      => '_g_newsletter_style',
		],

		[
			'name'       => 'css_newsletter_heading_typo',
			'label'      => esc_html__('Heading Typography', 'bunyad-admin'),
			'value'      => '',
			'desc'       => '',
			'type'       => 'group',
			'group_type' => 'typography',
			'devices'    => true,
			'style'      => 'edit',
			'css'        => '.spc-newsletter .heading',
			'group'      => '_g_newsletter_style',
		],

		[
			'name'       => 'css_newsletter_heading_color',
			'label'      => esc_html__('Heading Color', 'bunyad-admin'),
			'value'      => '',
			'desc'       => '',
			'type'       => 'color',
			'style'      => 'inline-sm',
			'css'        => [
				'.spc-newsletter .heading' => ['props' => ['color' => '%s']]
			],
			'group'      => '_g_newsletter_style',
		],
		[
			'name'       => 'css_newsletter_heading_color_sd',
			'label'      => esc_html__('Dark: Heading Color', 'bunyad-admin'),
			'value'      => '',
			'desc'       => '',
			'type'       => 'color',
			'style'      => 'inline-sm',
			'css'        => [
				'.s-dark .spc-newsletter .heading' => ['props' => ['color' => '%s']]
			],
			'group'      => '_g_newsletter_style',
		],

		[
			'name'       => 'css_newsletter_message_typo',
			'label'      => esc_html__('Message Typography', 'bunyad-admin'),
			'value'      => '',
			'desc'       => '',
			'type'       => 'group',
			'group_type' => 'typography',
			'devices'    => true,
			'style'      => 'edit',
			'css'        => '.spc-newsletter .message',
			'group'      => '_g_newsletter_style',
		],

		[
			'name'       => 'css_newsletter_disclaimer_typo',
			'label'      => esc_html__('Disclaimer Typography', 'bunyad-admin'),
			'value'      => '',
			'desc'       => '',
			'type'       => 'group',
			'group_type' => 'typography',
			'devices'    => true,
			'style'      => 'edit',
			'css'        => '.spc-newsletter .disclaimer',
			'group'      => '_g_newsletter_style',
		],

		[
			'name'       => 'css_newsletter_button_bg',
			'label'      => esc_html__('Button Background', 'bunyad-admin'),
			'value'      => '',
			'desc'       => '',
			'type'       => 'color',
			'style'      => 'inline-sm',
			'css'        => [
				'.spc-newsletter input[type=submit]' => [
					'props' => ['background' => '%s']
				]
			],
			'group'      => '_g_newsletter_style',
		],

		[
			'name'        => 'css_newsletter_max_width',
			'label'       => esc_html__('Content Max Width', 'bunyad-admin'),
			'value'       => '',
			'desc'        => '',
			'type'        => 'number',
			'style'       => 'inline-sm',
			'input_attrs' => ['min' => 200, 'max' => 1500],
			'css'         => [
				'.spc-newsletter' => ['props' => ['--max-width' => '%spx']],
			],
			'group'      => '_g_newsletter_style',
		],

		[
			'name'        => 'css_newsletter_bradius',
			'label'       => esc_html__('Box Roundness', 'bunyad-admin'),
			'value'       => '',
			'desc'        => '',
			'type'        => 'number',
			'style'       => 'inline-sm',
			'css'         => [
				'.spc-newsletter' => ['props' => ['--box-roundness' => '%spx']],
			],
			'group'      => '_g_newsletter_style',
		],

];

$style_tpl = [
	/**
	 * Group: Styling
	 */
	[
		'name'  => '_g_newsletter_style_{key}',
		'desc'  => '',
		'type'  => 'group',
		'style' => 'collapsible',
		'template'  => [
			'b' => [
				'label' => esc_html__('Style B: Modern', 'bunyad-admin'),
				'group' => ''
			],
			'c' => [
				'label' => esc_html__('Style C: Classic', 'bunyad-admin'),
				'group' => ''
			],
		]
	],

	[
		'name'       => 'css_newsletter_{key}_border_color',
		'label'      => esc_html__('Border Color', 'bunyad-admin'),
		'value'      => '',
		'desc'       => '',
		'type'       => 'color',
		'style'      => 'inline-sm',
		'css'        => [
			'.spc-newsletter-{key} > .inner' => ['props' => ['border-color' => '%s']]
		],
	],
	[
		'name'       => 'css_newsletter_{key}_border_color_sd',
		'label'      => esc_html__('Dark: Border Color', 'bunyad-admin'),
		'value'      => '',
		'desc'       => '',
		'type'       => 'color',
		'style'      => 'inline-sm',
		'css'        => [
			'.s-dark .spc-newsletter-{key} > .inner' => ['props' => ['border-color' => '%s']]
		],
	],

	[
		'name'       => 'css_newsletter_{key}_bg_color',
		'label'      => esc_html__('Background Color', 'bunyad-admin'),
		'value'      => '',
		'desc'       => '',
		'type'       => 'color',
		'style'      => 'inline-sm',
		'css'        => [
			'.spc-newsletter-{key}' => ['props' => ['background-color' => '%s']]
		],
	],
	[
		'name'       => 'css_newsletter_{key}_bg_color_sd',
		'label'      => esc_html__('Dark: Background Color', 'bunyad-admin'),
		'value'      => '',
		'desc'       => '',
		'type'       => 'color',
		'style'      => 'inline-sm',
		'css'        => [
			'.s-dark .spc-newsletter-{key}' => ['props' => ['background-color' => '%s']]
		],
	],
	
	[
		'name'       => 'css_newsletter_{key}_bg',
		'label'      => esc_html__('Background Image', 'bunyad-admin'),
		'value'      => '',
		'desc'       => '',
		'style'      => 'inline-sm',
		'type'    => 'upload',
		'options' => [
			'type' => 'image'
		],
		'bg_type' => ['value' => 'cover-nonfixed'],
		'css'     => [
			'.spc-newsletter-{key} .bg-wrap' => [
				'props' => ['background-image' =>  'url(%s)']
			]
		],
	],
	[
		'name'        => 'css_newsletter_{key}_bg_opacity',
		'label'       => esc_html__('BG Opacity', 'bunyad-admin'),
		'value'       => 1,
		'desc'        => '',
		'type'        => 'number',
		'style'       => 'inline-sm',
		'input_attrs' => ['min' => 0, 'max' => 1, 'step' => 0.1],
		'css'         => [
			'.spc-newsletter-{key} .bg-wrap' => ['props' => ['opacity' => '%s']],
		],
	],

];

\Bunyad\Util\repeat_options(
	$style_tpl,
	[
		'b' => [
			'group' => '_g_newsletter_style_b',
		],
		'c' => [
			'group' => '_g_newsletter_style_c',
		]
	],
	$fields,
	['replace_in' => ['css']]
);

$options['newsletter'] = [
	'sections' => [[
		'id'     => 'newsletter',
		'title'  => esc_html__('Newsletter / Subscribe', 'bunyad-admin'),
		'fields' => $fields
	]]
];