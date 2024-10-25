<?php

namespace Sphere\Core\Elementor\Layouts;

/**
 * Elementor Custom Layouts.
 */
class Module
{
	const TAXONOMY  = 'spc-el-layout';
	const POST_TYPE = 'spc-el-layouts';

	public $path;
	public $path_url;

	/**
	 * Types of custom layouts.
	 *
	 * @var array
	 */
	public $types = [];

	/**
	 * Classes objects references.
	 */
	public $preview;
	public $template;
	public $admin;
	
	protected static $instance;

	public function __construct()
	{
		// Register CPT whether elementor exists or not, mainly for import.
		add_action('init', [$this, 'register_cpt']);

		// Elementor plugin missing.
		if (!did_action('elementor/loaded')) {
			return;
		}

		$this->types = [
			'ts-archive' => [
				'label'     => esc_html__('Archive', 'admin'),
				'doc_class' => 'Archive',
				'doc_id'    => 'ts-archive',
			],
			'ts-footer' => 	[
				'label'     => esc_html__('Footer', 'admin'),
				'doc_class' => 'Footer',
				'doc_id'    => 'ts-footer',
			],
		];

		// Initialize the admin.
		if (is_admin()) {
			$this->admin = new Admin;
		}

		$this->preview = new Preview;

		// Handle templates at the correct locations.
		$this->template = new Template;

		$this->path = \Sphere\Core\Plugin::instance()->path . 'components/elementor/layouts/';
		$this->path_url = \Sphere\Core\Plugin::instance()->path_url . 'components/elementor/layouts/';
	}

	public function register_cpt()
	{
		$args = [
			'labels' => [
				'name'               => esc_html__('Custom Templates', 'sphere-core'),
				'singular_name'      => esc_html__('Template', 'sphere-core'),
				'add_new'            => esc_html__('Add New', 'sphere-core'),
				'add_new_item'       => esc_html__('Add New Template', 'sphere-core'),
				'edit_item'          => esc_html__('Edit Template', 'sphere-core'),
				'new_item'           => esc_html__('Add New Template', 'sphere-core'),
				'view_item'          => esc_html__('View Template', 'sphere-core'),
				'search_items'       => esc_html__('Search Template', 'sphere-core'),
				'not_found'          => esc_html__('No Templates Found', 'sphere-core'),
				'not_found_in_trash' => esc_html__('No Templates Found In Trash', 'sphere-core'),
				'menu_name'          => esc_html__('Custom Templates', 'sphere-core'),
			],
			'public'              => true,
			'hierarchical'        => false,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'exclude_from_search' => true,
			'rewrite'             => false,
			'capability_type'     => 'post',
			'supports'            => [
				'title', 'editor', 'thumbnail', 'author', 'elementor'
			],
		];

		register_post_type(self::POST_TYPE, $args);

		// Taxonomy to store the type.
		register_taxonomy(self::TAXONOMY, self::POST_TYPE, [
			'label'             => esc_html__('Type', 'sphere-core'),
			'hierarchical'      => false,
			'query_var'         => is_admin(),
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'public'            => false,
			'rewrite'           => false,
		]);
	}

	/**
	 * Get custom layouts options array.
	 *
	 * @param string $type
	 * @return array
	 */
	public function get_options($type = '')
	{
		$query_args = [
			'post_type'      => Module::POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => -1
		];

		if ($type) {
			$query_args['tax_query'] = [
				[
					'taxonomy' => Module::TAXONOMY,
					'field'    => 'slug',
					'terms'    => [$type]
				]
			];
		}

		$results = get_posts($query_args);
		$layouts = [];
		foreach ($results as $post) {
			$layouts[$post->ID] = $post->post_title;
		}

		return $layouts;
	}

	/**
	 * Get singleton object
	 * 
	 * @return self
	 */
	public static function instance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self;
		}
		
		return self::$instance;
	}
}