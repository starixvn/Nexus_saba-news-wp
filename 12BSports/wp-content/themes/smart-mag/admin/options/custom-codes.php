<?php
/**
 * Ads and custom codes.
 *
 * @uses Bunyad_Theme_CustomCodes
 */

$fields = [];

$sections = [
	[
		'id'     => 'custom-codes-head-foot',
		'title'  => 'Head Tag & Footer',
		'desc'   => esc_html('Note: These are for non-visual codes, to be added in <head> tag or at bottom of page in footer.'),
		'fields' => [
			[
				'name'    => 'codes_header',
				'label'   => esc_html__('Head Tag Code', 'bunyad-admin'),
				'desc'    => esc_html__('Code to add in the head tag.', 'bunyad-admin'),
				'value'   => '',
				'type'    => 'textarea',
				'sanitize_callback' => '',
			],

			[
				'name'    => 'codes_header_amp',
				'label'   => esc_html__('AMP: Head Code', 'bunyad-admin'),
				'desc'    => esc_html__('Code to add in the head tag for AMP version only, when AMP is in "transitional" mode setting.', 'bunyad-admin'),
				'value'   => '',
				'type'    => 'textarea',
				'sanitize_callback' => '',
			],

			[
				'name'    => 'codes_footer',
				'label'   => esc_html__('Footer Code', 'bunyad-admin'),
				'desc'    => esc_html__('Code to add at end of the body in footer.', 'bunyad-admin'),
				'value'   => '',
				'type'    => 'textarea',
				'sanitize_callback' => '',
			],
		]
	]
];

$tpl_ad = [
	[
		'name'    => 'codes_paras_{key}',
		'label'   => esc_html__('After Paragraphs', 'bunyad-admin'),
		'desc'    => esc_html__('Number of paragraphs to insert the ad after.', 'bunyad-admin'),
		'value'   => 2,
		'type'    => 'number',
		'style'   => 'inline-sm',
	],
	[
		'name'    => 'codes_{key}',
		'label'   => esc_html__('Ad / Custom Code', 'bunyad-admin'),
		'desc'    => esc_html__('For ads, use the code provided by your ad network such as Adsense. Shortcodes of plugins like AdRotate are also supported.', 'bunyad-admin'),
		'value'   => '',
		'type'    => 'textarea',
		'sanitize_callback' => '',
	],
	
	[
		'name'    => 'codes_devices_{key}',
		'label'   => esc_html__('Different For Phones / Tablets', 'bunyad-admin'),
		'desc'    => esc_html__('Use different codes for mobile, tablets. When enabled, main code above is only shown on Desktop.', 'bunyad-admin'),
		'value'   => 0,
		'type'    => 'toggle',
		'style'   => 'inline-sm',
	],

	[
		'name'    => 'codes_md_{key}',
		'label'   => esc_html__('Code for Tablets', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'textarea',
		'sanitize_callback' => '',
		'context' => [['key' => 'codes_devices_{key}', 'value' => 1]]
	],

	[
		'name'    => 'codes_sm_{key}',
		'label'   => esc_html__('Code for Phones', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'textarea',
		'classes' => 'sep-bottom',
		'sanitize_callback' => '',
		'context' => [['key' => 'codes_devices_{key}', 'value' => 1]]
	],

	[
		'name'    => 'codes_wrap_{key}',
		'label'   => esc_html__('Wrapper Box', 'bunyad-admin'),
		'desc'    => esc_html__('Place a box around the ad, with a background color.', 'bunyad-admin'),
		'value'   => 0,
		'type'    => 'toggle',
		'style'   => 'inline-sm',
	],

	[
		'name'    => 'codes_hide_{key}',
		'label'   => esc_html__('Hide On', 'bunyad-admin'),
		'desc'    => '',
		'value'   => [],
		'type'    => 'checkboxes',
		'options' => [
			'home'    => esc_html__('Homepage', 'bunyad-admin'),
			'archive' => esc_html__('Archives', 'bunyad-admin'),
			'pages'   => esc_html__('Pages', 'bunyad-admin'),
			'posts'   => esc_html__('Posts', 'bunyad-admin')
		]
	],

	[
		'name'    => 'codes_amp_{key}',
		'label'   => esc_html__('AMP Code', 'bunyad-admin'),
		'desc'    => esc_html__('Normal code cannot be used in AMP pages. If you use AMP, get the AMP code from your ad network.', 'bunyad-admin'),
		'value'   => '',
		'type'    => 'textarea',
		'sanitize_callback' => '',
	],

	[
		'name'    => 'codes_label_{key}',
		'label'   => esc_html__('Heading / Label', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'text',
		'style'   => 'inline-sm'
	],

	[
		'name'  => 'css_codes_bg_{key}',
		'value' => '',
		'label' => esc_html__('Background Color', 'bunyad-admin'),
		'desc'  => '',
		'type'  => 'color',
		'style' => 'inline-sm',
		'css'   => [
			'.a-wrap-{id}' => ['props' => ['background-color' => '%s']]
		],
		'context' => [['key' => 'codes_wrap_{key}', 'value' => 1]]
	],

	[
		'name'  => 'css_codes_bg_sd_{key}',
		'value' => '',
		'label' => esc_html__('Dark: Background Color', 'bunyad-admin'),
		'desc'  => '',
		'type'  => 'color',
		'style' => 'inline-sm',
		'css'   => [
			'.s-dark .a-wrap-{id}' => ['props' => ['background-color' => '%s']]
		],
		'context' => [['key' => 'codes_wrap_{key}', 'value' => 1]]
	],

	[
		'name'  => 'css_codes_pad_{key}',
		'value' => '',
		'label' => esc_html__('Inner Padding', 'bunyad-admin'),
		'desc'  => '',
		'type'    => 'dimensions',
		'devices' => true,
		'css'   => [
			'.a-wrap-{id}:not(._)' => ['dimensions' => 'padding'],
		],
	],

	[
		'name'  => 'css_codes_margin_{key}',
		'label' => esc_html__('Margins', 'bunyad-admin'),
		'value' => '',
		'desc'  => '',
		'type'    => 'dimensions',
		'devices' => true,
		'css'   => [
			'.a-wrap-{id}:not(._)' => ['dimensions' => 'margin'],
		],
	],

	// [
	// 	'name'  => 'css_codes_hide_{key}',
	// 	'label' => esc_html__('Hide on Device', 'bunyad-admin'),
	// 	'value' => 0,
	// 	'desc'  => 'Click on a device icon and activate the toggle button to hide on that device.',
	// 	'type'  => 'toggle',
	// 	'devices' => true,
	// 	'css'   => [
	// 		'.a-wrap-{id}' => ['props' => ['display' => 'none']],
	// 	],
	// ],
];

$locations = Bunyad::get('custom_codes')->get_locations();
foreach ($locations as $key => $location) {

	$the_fields = [];
	$skip       = [];

	// Only needed for single_para.
	if (strpos($key, 'single_para') === false) {
		$skip[] = 'codes_paras_{key}';
	}

	// These only apply to globals.
	if (strpos($key, 'single_') !== false) {
		$skip[] = 'codes_hide_{key}';
	}

	\Bunyad\Util\repeat_options(
		$tpl_ad,
		[
			$key => [
				'replacements' => ['{id}' => $location['id']],
				'skip' => $skip
			]
		],
		$the_fields,
		[
			'replace_in' => ['css', 'context'],
			'replacements' => [

			]
		]
	);

	$sections[] = [
		'title'  => $location['label'],
		'id'     => 'custom-codes-' . $key,
		'fields' => $the_fields
	];
}

$options['custom-codes'] = [
	'id'       => 'custom-codes',
	'title'    => esc_html__('Ads & Custom Codes', 'bunyad-admin'),
	'sections' => $sections
];

return $options;