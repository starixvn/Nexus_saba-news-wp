<?php
/**
 * Main layout options
 */
$fields = [
	[
		'name'    => 'image_logo',
		'value'   => '',
		'label'   => esc_html__('Logo Image', 'bunyad-admin'),
		'desc'    => esc_html__('Highly recommended to use a logo image in PNG format.', 'bunyad-admin'),
		'type'    => 'upload',
		'options' => [
			'type'  => 'image',
		],
	],
	
	[
		'name'    => 'image_logo_2x',
		'label'   => esc_html__('Logo Image Retina (2x)', 'bunyad-admin'),
		'value'   => '',
		'desc'    => esc_html__('This will be used for higher resolution devices like iPhone/Macbook.', 'bunyad-admin'),
		'type'    => 'upload',
		'options' => [
			'type'  => 'image',
		],
	],
	
	[
		'name'    => 'mobile_logo_2x',
		'value'   => '',
		'label'   => esc_html__('Mobile Logo Retina (2x - Optional)', 'bunyad-admin'),
		'desc'    => esc_html__('Use a different logo for mobile devices. Upload a logo twice the normal width and height.', 'bunyad-admin'),
		'type'    => 'upload',
		'options' => [
			'type'  => 'media',
		],
	],

	/**
	 * Group: Dark Mode Logos
	 */
	[
		'name'  => '_g_logos_dark',
		'label' => esc_html__('Dark Mode Logos', 'bunyad-admin'),
		'desc'  => 'Only required when you have a light header but have a dark mode switcher enabled.',
		'type'  => 'group',
		'style' => 'collapsible',
		// 'collapsed' => false,
	],
		[
			'name'    => 'image_logo_sd',
			'value'   => '',
			'label'   => esc_html__('Logo Image', 'bunyad-admin'),
			'desc'    => esc_html__('Highly recommended to use a logo image in PNG format.', 'bunyad-admin'),
			'type'    => 'upload',
			'options' => [
				'type'  => 'image',
			],
			'group'   => '_g_logos_dark',
		],
		
		[
			'name'    => 'image_logo_2x_sd',
			'label'   => esc_html__('Logo Image Retina (2x)', 'bunyad-admin'),
			'value'   => '',
			'desc'    => esc_html__('This will be used for higher resolution devices like iPhone/Macbook.', 'bunyad-admin'),
			'type'    => 'upload',
			'options' => [
				'type'  => 'image',
			],
			'group'   => '_g_logos_dark',
		],
		
		[
			'name'    => 'mobile_logo_2x_sd',
			'value'   => '',
			'label'   => esc_html__('Mobile Logo Retina (2x - Optional)', 'bunyad-admin'),
			'desc'    => esc_html__('Use a different logo for mobile devices. Upload a logo twice the normal width and height.', 'bunyad-admin'),
			'type'    => 'upload',
			'options' => [
				'type'  => 'media',
			],
			'group'   => '_g_logos_dark',
		],

	[
		'name'    => 'text_logo',
		'label'   => esc_html__('Legacy: Text Logo', 'bunyad-admin'),
		'desc'    => esc_html__('NOT RECOMMENDED: Use an image logo instead. An image logo is really important for a real site branding and SEO.', 'bunyad-admin'),
		'value'   => get_bloginfo('name'),
		'type'    => 'text',
	],
];

$options['logos'] = [
	'sections' => [[
		'id'     => 'logos',
		'title'  => esc_html__('Site Logos', 'bunyad-admin'),
		'fields' => $fields
	]]
];

return $options;