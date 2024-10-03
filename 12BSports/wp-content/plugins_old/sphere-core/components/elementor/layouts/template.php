<?php

namespace Sphere\Core\Elementor\Layouts;
use \Bunyad;
use Elementor\Core\Base\Document;

/**
 * Template handlers.
 */
class Template
{
	public $types = [];

	/**
	 * @var array
	 */
	protected $templates = null;

	public function __construct()
	{
		add_action('elementor/documents/register', [$this, 'register_documents']);
		add_action('wp_enqueue_scripts',[$this, 'enqueue_styles']);

		// 11 = after WooCommerce.
		add_filter('template_include', [$this, 'set_editor_template'], 11);

		/**
		 * Change page template if one of the content locations have a custom layout.
		 * 
		 * Currently 'ts-archive' is the location that goes in content and requires a 
		 * canvas page template.
		 */
		add_filter('template_include', function($current) {
			if (!$this->get_for_current('content')) {
				return $current;
			}

			$template = Module::instance()->path . 'page-templates/canvas.php';
			return apply_filters('sphere/el-layouts/page_template', $template);

		}, 99);

		/**
		 * Render custom templates using the filters.
		 */
		$filters = [
			'bunyad_do_partial_footer' => 'ts-footer',
		];

		foreach ($filters as $filter => $location) {
			add_filter($filter, function() use ($location) {
				return !$this->render_by_location($location);
			});
		}
	}

	public function enqueue_styles()
	{
		$templates = $this->get_templates();
		if (!$templates) {
			return;
		}

		$css_files = [];
		$current_post_id = get_the_ID();

		foreach ($templates as $template_id) {

			// Don't enqueue current post here (let the  preview/frontend components to handle it)
			if ($current_post_id !== $template_id) {
				$css_files[] = new \Elementor\Core\Files\CSS\Post($template_id);
			}
		}

		if ($css_files) {
			$front_end = \Elementor\Plugin::instance()->frontend;
			if (!is_object($front_end)) {
				return;
			}
			$front_end->enqueue_styles();
			
			foreach ($css_files as $css_file) {
				$css_file->enqueue();
			}
		}
	}

	/**
	 * Set the template for CPT (hence the editor).
	 *
	 * @param string $template
	 * @return string
	 */
	public function set_editor_template($template)
	{
		if (!is_singular(Module::POST_TYPE)) {
			return $template;
		}

		$doc_type = $this->get_doc_type();
		$template = 'canvas.php';

		switch($doc_type) {
			case 'ts-footer':
				$template = 'ts-footer.php';
				break;
		}

		$template = Module::instance()->path . 'page-templates/' . $template;
		return apply_filters('sphere/el-layouts/editor_template', $template);
	}

	/**
	 * Get document meta type: ts-archive, ts-footer etc.
	 *
	 * @return string
	 */
	protected function get_doc_type()
	{
		return get_post_meta(get_the_ID(), Document::TYPE_META_KEY, true);
	}

	/**
	 * Get templates for current WP view by location.
	 * 
	 * @see self::get_templates()
	 * @return array|string
	 */
	public function get_for_current(string $location)
	{
		$templates = $this->get_templates();
		$current_template = null;

		if ($location && isset($templates[$location])) {
			$current_template = $templates[$location];
		}
		else if ($location === 'content') {
			/**
			 * Content locations have a single result, usually. See if there's any available
			 * in the $templates array from above.
			 * 
			 * Note: 'ts-archive' is the only template that goes in content location yet.
			 */
			$content_templates = array_intersect_key($templates, array_flip([
				'ts-archive'
			]));

			if (count($content_templates)) {
				$current_template = current($content_templates);
			}
		}

		return apply_filters('sphere/el-layouts/current_template', $current_template, $location);
	}

	/**
	 * Get valid configured templates based on current WP view.
	 *
	 * @return array
	 */
	protected function get_templates()
	{
		// Already computed for current view.
		if (is_array($this->templates)) {
			return $this->templates;
		}

		$templates = [];

		/**
		 * Custom Archives templates.
		 */
		if (is_archive()) {

			// Woocommerce doesn't respect is_post_type_archive().
			$is_woocommerce = class_exists('woocommerce') && is_woocommerce();

			if (Bunyad::options()->category_loop_custom && is_category()) {
				$templates['ts-archive'] = Bunyad::options()->category_loop_custom;
			}
			else if (Bunyad::options()->author_loop_custom && is_author()) {
				$templates['ts-archive'] = Bunyad::options()->author_loop_custom;
			}
			else if (Bunyad::options()->archive_loop_custom && !is_post_type_archive()) {
	
				// These have explicit settings, checked above.
				if (!$is_woocommerce && !is_author() && !is_search() && !is_category()) {
					$templates['ts-archive'] = Bunyad::options()->archive_loop_custom;
				}
			}
			else if (Bunyad::options()->cpt_loop_custom && is_post_type_archive()) {
				if (!$is_woocommerce) {
					$templates['ts-archive'] = Bunyad::options()->cpt_loop_custom;
				}
			}

			/**
			 * Setup template from term meta configs, such as per-category settings.
			 */
			$object = get_queried_object();
			if (is_object($object) && property_exists($object, 'term_id')) {
				$term_template = get_term_meta($object->term_id, '_bunyad_custom_template', true);

				if ($term_template) {
					$templates['ts-archive'] = $term_template;
				}

				// If it's none, the global shouldn't apply either.
				if ($term_template === 'none') {
					unset($templates['ts-archive']);
				}
			}
		}

		/**
		 * Custom footer templates.
		 */
		if (Bunyad::options()->footer_custom) {

			// Global layout.
			$templates['ts-footer'] = Bunyad::options()->footer_custom;
		}

		if (Bunyad::options()->footer_custom_conditions) {
			$conditions = [
				'pages'    => 'is_page',
				'posts'    => 'is_single',
				'archives' => 'is_archive',
				'home' => function() {
					return is_home() || is_front_page();
				},
			];

			$template_id = false;
			foreach ($conditions as $type => $condition) {

				// Check if a custom layout for this type even set.
				$value = Bunyad::options()->get('footer_custom_' . $type);
				if (!$value) {
					continue;
				}

				if (call_user_func($condition)) {
					$template_id = $value;
					break;
				}
			}

			if ($template_id) {
				$templates['ts-footer'] = $template_id;
			}
		}

		// Remove any empty values.
		$this->templates = array_filter($templates);

		return apply_filters('sphere/el-layouts/location_templates', $templates);
	}

	/**
	 * Render a template for a particular location.
	 *
	 * @param string $location
	 * @return boolean
	 */
	public function render_by_location($location)
	{
		// Showing the template CPT (editor or front preview)
		if (is_singular(Module::POST_TYPE)) {

			if ($location === 'ts-footer' && $this->get_doc_type() === 'ts-footer') {
				the_content();
				return true;
			}
		}

		$template = $this->get_for_current($location);
		if (!$template) {
			return false;
		}

		$this->render($template);
		return true;
	}

	public function render_content()
	{
		if (did_action('elementor/preview/init')) {
			return the_content();
		}

		$this->render_by_location('content');
	}

	public function render($template_id)
	{
		echo \Elementor\Plugin::instance()->frontend->get_builder_content($template_id, false); // XSS ok
	}

	public function register_documents($manager)
	{
		foreach (Module::instance()->types as $type) {
			$manager->register_document_type(
				$type['doc_id'], 
				'\Sphere\Core\Elementor\Layouts\Documents\\' . $type['doc_class']
			);
		}
	}
}