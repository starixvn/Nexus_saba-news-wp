<?php

namespace Bunyad\Elementor;

/**
 * Base Elementor Class for setup
 */
class PageSettings 
{
	public function __construct()
	{
		/**
		 * Extend page settings
		 */
		add_action('elementor/element/wp-page/document_settings/before_section_end', function($page) {

			/** @var \Elementor\Core\DocumentTypes\Page $page */
			$page->add_control(
				'layout_style',
				[
					'label'       => esc_html__('Layout Style', 'bunyad-admin'),
					'type'        => 'select',
					'description' => '',
					'options'     => [
						''      => esc_html__('Default', 'bunyad-admin'),
						'right' => esc_html__('Right Sidebar', 'bunyad-admin'),
						'full'  => esc_html__('Full Width', 'bunyad-admin'),
					],
					'label_block' => false,
					'default'     => '',
					'meta_key'    => '_bunyad_layout_style',
					'condition'    => [
						'template!' => [
							'page-templates/no-wrapper.php',
						]
					]
				]
			);

			$page->add_control(
				'show_page_title',
				[
					'label'       => esc_html__('Page Heading', 'bunyad-admin'),
					'type'        => 'select',
					'description' => '',
					'options'     => [
						'yes' => esc_html__('Yes', 'bunyad-admin'),
						'no'  => esc_html__('No', 'bunyad-admin'),
					],
					'label_block' => false,
					'default'     => 'yes',
					'meta_key'    => '_bunyad_page_title',
					'condition'    => [
						'template!' => [
							'page-templates/no-wrapper.php',
						]
					]
				]
			);
		});

		/**
		 * Set default page template at launch, if none set.
		 * 
		 * Note: Doing at priority 1, much lower than Elementor handling which uses die().
		 */
		add_action('admin_action_elementor', function() {

			if (apply_filters('bunyad_elementor_skip_page_template', false)) {
				return;
			}

			if (empty($_REQUEST['post'])) {
				return;
			}

			$post_id = absint($_REQUEST['post']);

			$editor = \Elementor\Plugin::$instance->editor;
			if (!is_callable([$editor, 'is_edit_mode']) || !$editor->is_edit_mode($post_id)) {
				return;
			}

			// Only set for pages.
			if (get_post_type($post_id) !== 'page') {
				return;
			}

			$template = get_post_meta($post_id, '_wp_page_template', true);
			$default_template = 'page-templates/no-wrapper.php';

			// Set page template if none set.
			if (!$template || $template === 'default') {

				// If not first edit, we'll use blocks.php instead as default.
				$document = \Elementor\Plugin::$instance->documents->get($post_id);
				if ($document && $document->is_built_with_elementor()) {
					$default_template = 'page-templates/blocks.php';
				}

				update_post_meta($post_id, '_wp_page_template', $default_template);
			}
		}, 1);

		// Change the native hide_title control.
		add_action('elementor/element/wp-page/section_page_style/after_section_end', function($page) {

			$page->update_control('hide_title', [
				'condition'    => [
					'template!' => [
						'page-templates/authors.php', 
						'page-templates/sitemap.php', 
						'page-templates/blocks.php', 
						'page-templates/no-wrapper.php',
					]
				],
			]);
		});

		// Map some of the settings to post meta
		add_action('elementor/editor/localize_settings', [$this, 'load_post_settings_meta'], 10, 2);
		add_filter('elementor/documents/ajax_save/return_data', [$this, 'save_post_settings_meta'], 10, 2);
	}

	/**
	 * Filter Callback: Load the mapped post meta values.
	 */
	public function load_post_settings_meta($settings, $post_id) {

		$document = \Elementor\Plugin::$instance->documents->get_doc_or_auto_save($post_id);

		$controls = [];
		foreach ($document->get_controls() as $key => $control) {
			if (!isset($control['meta_key'])) {
				continue;
			}

			if (isset($control['meta_load_cb'])) {
				$meta_value = call_user_func($control['meta_load_cb'], $post_id);
			}
			else {
				$meta_value = get_post_meta($post_id, $control['meta_key'], true);
			}

			$controls[$key] = $meta_value;
		}

		// Match settings array as specified by Elementor
		$new_settings = [
			'settings' => [
				'page' => ['settings' => $controls]
			]
		];

		return array_replace_recursive($settings, $new_settings);
	}

	/**
	 * Filter Callback: Save the mapped post meta.
	 * 
	 * @param array $return
	 * @param Elementor\Core\DocumentTypes\Post $document
	 * @return array
	 */
	public function save_post_settings_meta($return, $document) {

		$post_id  = $document->get_id();
		$settings = $document->get_settings();

		foreach ($document->get_controls() as $key => $control) {
			if (!isset($control['meta_key'])) {
				continue;
			}

			$has_key = array_key_exists($key, $settings);

			// Have a callback to update meta?
			if (isset($control['meta_save_cb'])) {
				
				call_user_func(
					$control['meta_save_cb'], 
					$post_id, 
					$has_key ? $settings[$key] : null
				);

				continue;
			}

			// If empty or default, remove from post meta too.
			if (!$has_key) {
				delete_post_meta($post_id, $control['meta_key']);
			}
			else {
				update_post_meta($post_id, $control['meta_key'], $settings[$key]);
			}
		}

		return $return;
	}
}