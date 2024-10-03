<?php

namespace Bunyad\Elementor;

use Elementor\Plugin;
use \SmartMag_Core;

/**
 * Base Elementor Class for setup
 */
class Module 
{
	public function register_hooks()
	{
		// Register theme locations for Elementor Pro.
		add_action('elementor/theme/register_locations', function($manager) {
			$manager->register_all_core_location();
		});

		add_action('init', function() {
			if (!function_exists('elementor_theme_do_location')) {
				return;
			}

			$filters = [
				'bunyad_do_partial_404'     => 'single',
				'bunyad_do_partial_single'  => 'single',
				'bunyad_do_partial_page'    => 'single',
				'bunyad_do_partial_header'  => 'header',
				'bunyad_do_partial_footer'  => 'footer',

				// Note: Won't be reached for elementor as they overwrite it with headers-footers.php instead.
				'bunyad_do_partial_archive' => 'archive',
			];

			// Only do these locations if not already handled by elementor.
			foreach ($filters as $filter => $location) {
				// 9 so that it's lower priority than sphere-core custom layouts.
				add_filter($filter, function() use ($location) {
					return !elementor_theme_do_location($location);
				}, 9);
			}
		});

		// Fix: Elementor Pro won't use archive.php in locations API.
		$overridden = false;
		add_filter('elementor/theme/need_override_location', function($override) use (&$overridden) {
			if ($override) {
				$overridden = true;
			}
			return $override;
		});
		add_action('elementor/page_templates/header-footer/before_content', function() use (&$overridden) {
			if ($overridden) {
				\Bunyad::blocks()->load('Breadcrumbs')->render();
			}
		});

		// Add widgets
		add_action('elementor/widgets/register', [$this, 'register_widgets']);

		// Add custom controls.
		add_action('elementor/controls/register', [$this, 'register_controls']);
		
		// Register categories
		add_action('elementor/elements/categories_registered', function($manager) {
			$manager->add_category(
				'smart-mag-blocks',
				[
					'title' => esc_html__('SmartMag Blocks', 'bunyad-admin'),
					'icon'  => 'fa fa-plug'
				]
			);
		});

		// Add assets for the editor.
		add_action('elementor/editor/after_enqueue_scripts', function() {
			wp_enqueue_script(
				'bunyad-elementor-editor', 
				SmartMag_Core::instance()->path_url . 'inc/elementor/js/elementor-editor.js',
				['jquery', 'wp-api-request'], 
				SmartMag_Core::VERSION, 
				true
			);

			wp_enqueue_style(
				'bunyad-elementor-editor', 
				SmartMag_Core::instance()->path_url . 'inc/elementor/css/elementor-editor.css',
				[],
				SmartMag_Core::VERSION
			);
		});

		// Preview only assets.
		add_action('elementor/preview/enqueue_scripts', [$this, 'register_preview_assets']);

		// Extend widget controls.
		new ExtendWidgets;

		// Extend sections.
		new ExtendSection;

		// Extend containers.
		new ExtendContainer;

		/**
		 * Add post-content class to text widget.
		 */
		// add_filter('elementor/widget/print_template', function($content, $element) {
		// 	if ($element->get_name() !== 'text-editor') {
		// 		return $content;
		// 	}
			
		// 	return "<# view.addRenderAttribute( 'editor', 'class', ['post-content'] ); #>\n" . $content;
			
		// }, 10, 2);

		// add_action('elementor/widget/before_render_content', function($element) {
		// 	if ($element->get_name() !== 'text-editor') {
		// 		return;
		// 	}

		// 	$element->add_render_attribute(
		// 		'_wrapper', 'class', [
		// 			'elementor-widget',
		// 			'elementor-widget-text-editor',
		// 			'post-content'
		// 		]
		// 	);

		// 	// $element->add_render_attribute('editor', 'class', ['post-content']);
		// });

		/**
		 * Cleanup.
		 */
		// Cleanup templates library.
		add_filter('option_elementor_remote_info_library', function($data) {
			if (defined('ELEMENTOR_PRO_VERSION')) {
				return $data;
			}

			if (isset($data['templates'])) {
				$data['templates'] = array_filter($data['templates'], function($item) {
					return !$item['is_pro'];
				});
			}

			return $data;
		});
		
		add_filter('elementor/frontend/admin_bar/settings', function($config) {
			if (defined('ELEMENTOR_PRO_VERSION')) {
				return $config;
			}

			if (isset($config['elementor_edit_page']['children'])) {
				$config['elementor_edit_page']['children'] = array_filter(
					$config['elementor_edit_page']['children'],
					function($value) {
						return (
							empty($value['id']) 
							|| !in_array($value['id'], ['elementor_app_site_editor', 'elementor_site_settings'])
						);
					}
				);	
			}

			return $config;
		}, 999);
		
		// Ensure our kit exists.
		add_action('elementor/init', [$this, 'init_smartmag_kit']);

		// Initialize page related settings.
		new PageSettings;

		// Unnecessary redirect on plugin activation.
		add_action('admin_init', function() {
			if (get_transient('elementor_activation_redirect')) {
				delete_transient('elementor_activation_redirect');
			}
		}, -1);

		// Dev notices, comeon. Disable.
		add_filter('elementor/core/admin/notices', function($notices) {
			foreach ($notices as $key => $notice) {
				if (is_a($notice, 'Elementor\Core\Admin\Notices\Elementor_Dev_Notice')) {
					unset($notices[$key]);
				}
			}

			return $notices;
		});

		// Check conflict with Bunyad Page Builder.
		add_action('admin_init', function() {
			if (is_plugin_active('bunyad-siteorigin-panels/bunyad-siteorigin-panels.php')) {
				add_action('admin_notices', [$this, 'notice_bunyad_builder']);
			}
		});
	}

	/**
	 * Setup our style kit.
	 */
	public function init_smartmag_kit()
	{
		if (!is_admin() || wp_doing_ajax() || !is_user_logged_in()) {
			return;
		}

		$kits_manager = Plugin::$instance->kits_manager;
		if (!$kits_manager || !is_callable([$kits_manager, 'get_active_kit'])) {
			return;
		}

		$active = $kits_manager->get_active_kit();
		if (!is_callable([$active, 'get_post'])) {
			return;
		}
		
		$active_kit = $active->get_post();
		if (strpos($active_kit->post_name, 'smartmag-kit') !== false) {
			return;
		}

		// Create the kit.
		if (is_callable([Plugin::$instance->documents, 'create'])) {
			$new_settings = [
				'viewport_lg' => 940,
				'container_width' => [
					'unit' => 'px',
					'size' => 1200,
					'sizes' => []
				],
				'container_width_tablet' => [
					'unit' => 'px',
					'size' => 940,
					'sizes' => []
				],
				'system_colors' => [[
					'_id' => 'smartmag',
					'title' => 'SmartMag Main',
					'color' => 'var(--c-main)',
				]]
			];

			$kit = Plugin::$instance->documents->create('kit', [
				'post_type'   => 'elementor_library',
				'post_title'  => 'SmartMag Kit',
				'post_name'   => 'smartmag-kit',
				'post_status' => 'publish'
			]);

			if (!$kit || !is_callable([$kit, 'get_settings'])) {
				return;
			}

			$settings = $kit->get_settings();
			$new_settings['system_colors'] = array_merge(
				$new_settings['system_colors'],
				$settings['system_colors']
			);

			// Font-families should be inherited from the theme.
			$new_settings['system_typography'] = array_map(
				function($typography) {
					foreach ($typography as $key => $value) {
						if ($key !== 'typography_font_family') {
							continue;
						}

						unset($typography[$key]);
					}

					return $typography;
				}, 
				$settings['system_typography']
			);

			if (is_callable([$kit, 'update_settings']) && is_callable([$kit, 'get_id'])) {
				$kit->update_settings($new_settings);
				update_option('elementor_active_kit', $kit->get_id());
			}
		}
	}

	/**
	 * Register assets only for the Elementor preview.
	 */
	public function register_preview_assets()
	{
		// Registered a bit later.
		add_action('bunyad_register_assets', function() {
			// Make slick slider always available.
			wp_enqueue_script('smartmag-slick');
		});

		// Register front-end script for widgets.
		wp_enqueue_script(
			'bunyad-elementor-preview',
			SmartMag_Core::instance()->path_url . 'js/elementor/preview.js',
			['elementor-frontend', 'smartmag-theme'],
			SmartMag_Core::VERSION, 
			true
		);

		wp_enqueue_style(
			'bunyad-elementor-preview',
			SmartMag_Core::instance()->path_url . 'inc/elementor/css/preview.css',
			[],
			SmartMag_Core::VERSION
		);
	}

	/**
	 * Register blocks as widgets for Elementor
	 */
	public function register_widgets()
	{
		// Load the class map to prevent unnecessary autoload to save resources
		include_once SmartMag_Core::instance()->path . 'inc/elementor/classmap-widgets.php';

		// Widgets to load
		$blocks = apply_filters('bunyad_elementor_widgets', []);
		
		foreach ($blocks as $block) {
			$class = 'Bunyad\Blocks\\' . $block . '_Elementor';

			Plugin::instance()->widgets_manager
				->register(new $class);
		}

		// Some extra widgets that are Elementor only.
		$elementor_widgets = [
			'LinksList'
		];

		foreach ($elementor_widgets as $widget) {
			$class = 'Bunyad\Elementor\Widgets\\' . $widget;

			Plugin::instance()->widgets_manager
				->register(new $class);
		}
	}

	public function register_controls()
	{
		$manager = Plugin::$instance->controls_manager;
		$manager->register(new Controls\Selectize);

		$manager->add_group_control(
			'bunyad-border',
			new Controls\Groups\Border
		);
	}

	public function add_section_query_controls($section, $args)
	{
		// This is required class.
		if (!class_exists('\Bunyad\Blocks\Base\LoopOptions')) {
			return;
		}

		$options_obj = new SectionOptions;
		$sections    = [
			'sec-query' => [
				'label' => esc_html__('Section Query', 'bunyad-admin'),
				'tab'   => $args['tab']
			]
		];

		LoopWidget::do_register_controls($section, $options_obj, $sections);
	}

	/**
	 * Admin notice for bunyad builder conflict.
	 */
	public function notice_bunyad_builder()
	{
		$message = 'As of SmartMag v5+, <strong>Bunyad Page Builder</strong> is no longer needed and conflicts with Elementor. Please deactivate.';
		printf(
			'<div class="notice notice-error"><h3>Important:</h3><p>%1$s</p></div>',
			wp_kses_post($message)
		);
	}
}