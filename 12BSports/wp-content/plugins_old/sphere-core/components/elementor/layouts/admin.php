<?php

namespace Sphere\Core\Elementor\Layouts;

/**
 * Admin related functionality.
 */
class Admin
{
	public function __construct()
	{
		add_action('admin_init', [$this, 'init']);
		add_action('admin_menu',  [$this, 'add_menu']);
		add_action('admin_enqueue_scripts', [$this, 'register_assets']);
	}

	public function init()
	{
		// Remove page template options for this CPT.
		add_action('add_meta_boxes_' . Module::POST_TYPE, function() {
			unset( 
				$GLOBALS['wp_meta_boxes'][ Module::POST_TYPE ]['side']['core']['pageparentdiv']
			);
		}, 999);

		add_filter('views_edit-' . Module::POST_TYPE, [$this, 'output_tabs']);
		add_action('admin_action_spc_el_layout_add', [$this, 'create_template']);
	}

	public function register_assets()
	{
		wp_enqueue_style(
			'spc-el-layout-admin',
			Module::instance()->path_url . 'css/admin.css',
			[],
			\Sphere\Core\Plugin::VERSION
		);

		$screen = get_current_screen();

		if ($screen->id === 'edit-' . Module::POST_TYPE) {

			wp_enqueue_style('wp-jquery-ui-dialog');
			wp_enqueue_script(
				'spc-el-layout-admin-js',
				Module::instance()->path_url . 'js/admin.js',
				['jquery-ui-dialog'],
				\Sphere\Core\Plugin::VERSION
			);

			add_action('admin_footer', [$this, 'output_modal_template']);
		}
	}

	public function add_menu()
	{
		// Add submenu but render using the default CPT page.
		add_submenu_page(
			'sphere-dash',
			esc_html__('Custom Layouts', 'sphere-core'),
			esc_html__('Custom Layouts', 'sphere-core'),
			'edit_pages',
			'edit.php?post_type=' . Module::POST_TYPE
		);
	}

	/**
	 * Action callback to create the custom template.
	 *
	 * @return void
	 */
	public function create_template()
	{
		if (!check_admin_referer('spc-el-layout-add') || !current_user_can('edit_posts')) {
			wp_die(
				esc_html__('Sorry, you do not have permission for this.', 'sphere-core' ),
				esc_html__('Error', 'sphere-core')
			);
		}

		$template_type = $_POST['template_type'];
		if (!$template_type) {
			wp_die('Missing fields. Please select correct template type.');
		}

		if (!class_exists('\Elementor\Plugin')) {
			wp_die('Elementor plugin is required to create the template. Please install and activate and the Elementor plugin.');
		}

		$document = \Elementor\Plugin::instance()->documents->get_document_type($template_type);
		
		$post_data = [
			'post_type' => Module::POST_TYPE,
			'tax_input' => [
				Module::TAXONOMY => $template_type,
			],
			'post_title' => $_POST['template_name'],
			'meta_input' => [
				'_elementor_edit_mode' => 'builder',
				$document::TYPE_META_KEY => $template_type
			]
		];

		$post_id = wp_insert_post($post_data);

		wp_safe_redirect(
			\Elementor\Plugin::$instance->documents->get($post_id)->get_edit_url()
		);
	}

	/**
	 * Filter callback: Print our custom tabs on views_edit-CPT filter due to lack of 
	 * a better one in core.
	 *
	 * @param  $current
	 * @return void
	 */
	public function output_tabs($current)
	{
		$tabs = [
			'ts-archive' => esc_html__('Archive', 'sphere-core'),
			'ts-footer'  => esc_html__('Footer', 'sphere-core'),
		];

		$tabs = ['all' => esc_html__('All', 'sphere-core')] + $tabs;
		
		/**
		 * Variables below are used by view file.
		 */
		$active_tab = isset($_GET[ Module::TAXONOMY ]) ? $_GET[ Module::TAXONOMY ] : 'all';
		if (!isset($tabs[$active_tab])) {
			$active_tab = 'all';
		}

		$tab_link   = admin_url('edit.php?post_type=' . Module::POST_TYPE);
		$query_arg  = Module::TAXONOMY;

		require_once Module::instance()->path . 'views/tabs.php';

		return $current;
	}

	public function output_modal_template()
	{
		$submit_url = admin_url('admin.php?action=spc_el_layout_add');
		$types      = Module::instance()->types;
		
		require_once Module::instance()->path . 'views/add-modal.php';	
	}
}