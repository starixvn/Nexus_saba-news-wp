<?php
/**
 * Recommended / packaged plugins.
 */
return [
	[
		'name'     => 'Sphere Core',
		'slug'     => 'sphere-core',
		'source'   => get_template_directory() . '/lib/vendor/plugins/sphere-core.zip',
		'required' => true,
		'version'  => /*@version:sphere-core*/ '1.6.6'
	],

	[
		'name'     => 'SmartMag Core',
		'slug'     => 'smartmag-core',
		'source'   => get_template_directory() . '/lib/vendor/plugins/smartmag-core.zip',
		'required' => true,
		'version'  => /*@version:smartmag-core*/ '1.4.6'
	],

	[
		'name'     => 'Elementor Page Builder',
		'slug'     => 'elementor',
		'required' => true,
		'version'  => '3.16.2',
	],		
	
	[
		'name'     => 'Regenerate Thumbnails',
		'slug'     => 'regenerate-thumbnails',
		'optional' => true,
		'required' => false,
	],

	[
		'name'     => 'Contact Form 7',
		'slug'     => 'contact-form-7',
		'required' => false,
		'optional' => true,
	],

	[
		'name'     => 'Custom sidebars',
		'slug'     => 'custom-sidebars',
		'required' => false,
		'optional' => true,
	],

	[
		'name'     => 'Self-Hosted Google Fonts',
		'slug'     => 'selfhost-google-fonts',
		'required' => false,
		'optional' => true,
	],
	[
		'name'     => 'Debloat - Optimize Performance',
		'slug'     => 'debloat',
		'required' => false,
		// 'optional' => true,
	],
	[
		'name'     => 'Sphere Post Views',
		'slug'     => 'sphere-post-views',
		'source'   => get_template_directory() . '/lib/vendor/plugins/sphere-post-views.zip',
		'required' => false,
		'optional' => true,
		'version'  => /*@version:sphere-post-views*/ '1.0.1'
	],
	[
		'name'     => 'Bunyad Instagram Widget',
		'slug'     => 'bunyad-instagram-widget',
		'source'   => get_template_directory() . '/lib/vendor/plugins/bunyad-instagram-widget.zip',
		'required' => false,
		'optional' => true,
	],
	[
		'name'         => 'Bunyad AMP',
		'slug'         => 'bunyad-amp',
		'required'     => false,
		'optional'     => true,
		'source'       => get_template_directory() . '/lib/vendor/plugins/bunyad-amp.zip', // The plugin source
		'version'      => /*@version:bunyad-amp*/ '2.4.2',
		'external_url' => 'https://theme-sphere.com/docs/smartmag/#amp'
	],
	[
		'name'     => esc_html__('Bunyad Demo Import', 'bunyad-admin'),
		'slug'     => 'bunyad-demo-import',
		'required' => false,
		'version'  => /*@version:bunyad-demo-import*/ '2.6.2',
		'source'   => get_template_directory() . '/lib/vendor/plugins/bunyad-demo-import.zip', // The plugin source
	],
	[
		'name'     => 'cryptocurrency-price-ticker-widget',
		'slug'     => 'cryptocurrency-price-ticker-widget',
		'required' => false,
		'optional' => true,
		'hidden'   => true,
	]
];