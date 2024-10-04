<?php
namespace SmartMag\ConvertV5;

use \SmartMag_Core;
use \Bunyad;
use \Bunyad_Theme_Admin_Migrations_500Update;

/**
 * Convert from an older version to v5.0.
 */
class ConvertV5 
{
	public function __construct()
	{

		if (!Bunyad::get('theme')) {
			return;
		}

		add_action('admin_menu', [$this, 'menu'], 10);
		add_action('admin_init', [$this, 'init']);
	}

	public function init()
	{
		// All the AJAX actions.
		add_action('wp_ajax_smartmag_convert_v5_builder', [$this, 'convert_builder']);
		add_action('wp_ajax_smartmag_convert_v5_profiles', [$this, 'convert_profiles']);
		add_action('wp_ajax_smartmag_convert_v5_terms_meta', [$this, 'convert_terms_meta']);
		add_action('wp_ajax_smartmag_convert_v5_cleanup', [$this, 'convert_cleanup']);
	}

	/**
	 * Register admin views.
	 */
	public function menu()
	{
		add_submenu_page(
			'sphere-dash', 
			'Convert to v5', 
			'Convert to v5', 
			'manage_options', 
			'sphere-dash-convert-v5',
			[$this, 'admin_page']
		);
	}

	public function admin_page()
	{
		wp_register_script(
			'smartmag-convert-v5', 
			SmartMag_Core::instance()->path_url .'/inc/convert-v5/js/convert-v5.js',
			['jquery']
		);

		wp_localize_script('smartmag-convert-v5', 'SmartMag_Convert', [
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		]);

		wp_enqueue_script('smartmag-convert-v5');

		$missing_plugins = [];
		if (!class_exists('SmartMag_Core')) {
			$missing_plugins[] = 'SmartMag Core';
		}
		
		if (!did_action('elementor/loaded')) {
			$missing_plugins[] = 'Elementor Page Builder';
		}
		
		if (!class_exists('\Sphere\Core\Plugin')) {
			$missing_plugins[] = 'Sphere Core';
		}

		if (!is_plugin_active('regenerate-thumbnails/regenerate-thumbnails.php')) {
			$missing_plugins[] = 'Regenerate Thumbnails';
		}

		include __DIR__ . '/views/admin-page.php';
	}

	public function set_env_configs()
	{
		// We want real errors only or AJAX request can fail.
		@error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
		@ini_set('display_errors', '0');
		@ini_set('memory_limit', '350M');
	}

	public function verify_nonce()
	{
		if (!wp_verify_nonce($_POST['nonce'] , 'smartmag_convert_v5')) {
			wp_send_json_error('Nonce missing');
		}
	}

	/**
	 * Convert SO builder to Elementor.
	 */
	public function convert_builder()
	{
		$this->set_env_configs();
		if ($this->verify_nonce()) {
			return;
		}

		$posts = get_posts([
			'posts_per_page' => -1,
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'meta_query'     => [
				[
					'key'     => 'panels_data',
					'value'   => ['', 'a:0:{}'],
					'compare' => 'NOT IN'
				],
			]
		]);

		foreach ($posts as $post) {

			// if ($post->ID !== 2502) continue;
			$convert = new BunyadToElementor($post);
			$convert->convert();
		}

		wp_send_json_success();
	}

	/**
	 * Extra meta for profiles have bunyad_ prefix now.
	 */
	public function convert_profiles()
	{
		$this->set_env_configs();
		if ($this->verify_nonce()) {
			return;
		}

		$users = get_users();
		$fields = [
			'facebook', 'twitter', 'tumblr', 'instagram', 
			'pinterest', 'bloglovin', 'dribble', 'linkedin'
		];

		foreach ($users as $user) {
			foreach ($fields as $field) {
				$meta = get_user_meta($user->ID, $field, true);
				if (!$meta) {	
					continue;
				}

				delete_user_meta($user->ID, $field);
				update_user_meta($user->ID, 'bunyad_' . $field, $meta);
			}
		}

		wp_send_json_success();
	}

	/**
	 * Convert old terms meta, saved in options, before terms meta was a thing.
	 */
	public function convert_terms_meta()
	{
		$this->set_env_configs();
		if ($this->verify_nonce()) {
			return;
		}

		$cat_meta = Bunyad::options()->get_all('cat_meta_');
		foreach ($cat_meta as $key => $meta) {
			$id   = (int) str_replace('cat_meta_', '', $key);
			$meta = $this->map_term_meta($meta);

			foreach ($meta as $meta_key => $meta_value) {
				update_term_meta($id, '_bunyad_' . $meta_key, $meta_value);
			}
		}

		wp_send_json_success();
	}

	/**
	 * Map term meta options to the new values.
	 *
	 * @param array $meta
	 * @return array
	 */
	protected function map_term_meta($meta)
	{
		// Fix pagination.
		if (!empty($meta['pagination_type'])) {
			$meta['pagination_type'] = $meta['pagination_type'] === 'normal' ? 'numbers' : $meta['pagination_type'];
		}

		if (empty($meta['slider'])) {
			$meta['slider'] = 'none';
		}

		if (empty($meta['slider_type'])) {
			$meta['slider_type'] = 'classic';
		}	

		// Update meta template based on the conversion for old 'default_cat_template' option.
		if (!empty($meta['template'])) {
			$meta['template'] = Bunyad_Theme_Admin_Migrations_500Update::get_loop_template($meta['template']);
		}

		// Set correct slider_type and numbers.
		switch ($meta['slider_type']) {
			case 'grid':
				$meta['slider_type'] = 'grid-a';
				$meta['slider_number'] = 5;
				break;

			case 'grid-b':
				$meta['slider_type'] = 'grid-d';
				$meta['slider_number'] = 4;
				break;
			
			case 'classic':
				$meta['slider_number'] = 5; // +3 added
				break;
		}

		return $meta;
	}

	public function convert_cleanup()
	{
		// All done. Remove the flag.
		delete_option('smartmag_convert_from_v3');

		// Flush css caches for cat meta.
		Bunyad::get('custom_css')->flush_cache();

		wp_send_json_success();
	}

}