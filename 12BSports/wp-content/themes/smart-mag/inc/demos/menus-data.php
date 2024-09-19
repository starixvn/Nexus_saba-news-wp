<?php
/**
 * Sample menus data.
 */
$demo_id    = isset($demo_id) ? $demo_id : '';

$menus_data = [
	'smartmag-main' => [
		'location' => 'smartmag-main',
		'label'    => 'Main Menu',
		'items'    => [
			'home' => [
				'title'   => 'Home',
				'url'     => '{base_url}',
			],
			'features' => [
				'title'   => 'Features',
				'url'     => '#',
				'items'   => [
					// Will point to random by menu script if doesn't exist.
					'example-post' => [
						'title'  => 'Example Post',
						'type'   => 'post',
						'slug'   => 'example-post',
					],
					'typography-post-elements' => [
						'title'  => 'Typography',
						'type'   => 'page',
						'slug'   => 'typography-post-elements',
					],
					'get-in-touch' => [
						'title'  => 'Contact',
						'type'   => 'page',
						'slug'   => 'get-in-touch',
					],
					'view-all' => [
						'title'  => 'View All On Demos',
						'url'    => 'https://theme-sphere.com/demo/smartmag-landing/',
						'target' => '_blank'
					],
				]
			],
			'example-1' => [
				'type' => 'category',
				'slug' => 'example',
				'meta' => [
					'mega_menu' => 'category-a'
				],
			],
			'typography-post-elements' => [
				'title' => 'Typography',
				'type'  => 'page',
				'slug'  => 'typography-post-elements'
			],
			'example-3' => [
				'type' => 'category',
				'slug' => 'example-3',
				'meta' => [
					'mega_menu' => 'category-a'
				],
				'items' => [
					'cat-1' => [
						'type' => 'category',
						'slug' => 'example-1',
					],
					'cat-2' => [
						'type' => 'category',
						'slug' => 'example-2',
					],
					'cat-3' => [
						'type' => 'category',
						'slug' => 'example-4'
					],
				]
			],
			'buy-now' => [
				'title'  => 'Buy Now',
				'url'    => 'https://theme-sphere.com/buy/go.php?theme=smartmag',
				'target' => '_blank'
			]
		]
	],
	'smartmag-top' => [
		'location' => '',
		'label'    => 'Top Links',
		'bunyad_option' => 'header_nav_small_menu',
		'items'    => [
			'demos' => [
				'title'   => 'Demos',
				'url'     => 'https://theme-sphere.com/demo/smartmag-landing/',
			],
			'cat-1' => [
				'type' => 'category',
				'slug' => 'example-1',
			],
			'cat-2' => [
				'type' => 'category',
				'slug' => 'example-2',
			],
			'buy-now' => [
				'title'  => 'Buy Now',
				'url'    => 'https://theme-sphere.com/buy/go.php?theme=smartmag',
				'target' => '_blank'
			]
		]
	],
	'smartmag-footer' => [
		'location' => 'smartmag-footer-links',
		'label'    => 'Footer Links',
		'items'    => [
			'home' => [
				'title'   => 'Home',
				'url'     => '{base_url}',
			],
			'cat-1' => [
				'type' => 'category',
				'slug' => 'example-1',
			],
			'cat-2' => [
				'type' => 'category',
				'slug' => 'example-2',
			],
			'cat-3' => [
				'type' => 'category',
				'slug' => 'example-3',
			],
			'buy-now' => [
				'title'  => 'Buy Now',
				'url'    => 'https://theme-sphere.com/buy/go.php?theme=smartmag',
				'target' => '_blank'
			]
		]
	],
];

return $menus_data;