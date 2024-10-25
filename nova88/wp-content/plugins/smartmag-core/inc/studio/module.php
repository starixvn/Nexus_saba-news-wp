<?php

namespace Bunyad\Studio;

use \SmartMag_Core;

/**
 * The SmartMag Cloud Studio.
 */
class Module
{
	public function __construct()
	{
		add_action('elementor/editor/footer', [$this, 'elementor_views']);
		add_action('elementor/editor/after_enqueue_styles', [$this, 'elementor_scripts']);
		add_action('elementor/preview/enqueue_scripts', [$this, 'elementor_styles']);

		// Ajax request
		add_action('wp_ajax_ts-el-studio-template', [$this, 'get_template']);
	}

	public function elementor_scripts()
	{
		wp_enqueue_script(
			'bunyad-el-studio',
			SmartMag_Core::instance()->path_url . 'inc/studio/js/elementor.js',
			['jquery', 'wp-util', 'masonry', 'imagesloaded'],
			SmartMag_Core::VERSION
		);

		$data = json_decode(
			file_get_contents(__DIR__ . '/data.json')
		);

		wp_localize_script('bunyad-el-studio', 'SphereStudioData', [
			'elTemplates' => $data
		]);

		$this->elementor_styles();
	}

	public function elementor_styles() 
	{
		wp_enqueue_style(
			'bunyad-el-studio',
			SmartMag_Core::instance()->path_url . 'inc/studio/css/elementor.css',
			[],
			SmartMag_Core::VERSION
		);
	}

	/**
	 * Add the modal view templates.
	 */
	public function elementor_views()
	{
		$views = [
			'header',
			'blocks',
			'pages',
			'items',
			'header-preview',
			'preview'
		];

		foreach ($views as $view) {
			include_once SmartMag_Core::instance()->path . 'inc/studio/views/el-' . $view . '.php';
		}
	}

	/**
	 * AJAX request to get a block/template.
	 */
	public function get_template()
	{
		if (!current_user_can('edit_posts') || !$_GET['id']) {
			return wp_send_json_error();
		}
		
		$data = json_decode(
			file_get_contents(__DIR__ . '/templates.json'), 
			true
		);

		if (!isset($data[$_GET['id']])) {
			return wp_send_json_error('Block not found');
		}

		// Process content.
		$content = $data[ $_GET['id'] ];
		$content = $this->elementor_replace_ids($content);

		wp_send_json_success([
			'content' => $content
		]);
	}

	/**
	 * Required to have unique ids for multi-imports.
	 */
	public function elementor_replace_ids($content)
	{
		return \ELementor\Plugin::$instance->db->iterate_data($content, function($element) {
			$element['id'] = dechex(rand());
			return $element;
		});
	}
}