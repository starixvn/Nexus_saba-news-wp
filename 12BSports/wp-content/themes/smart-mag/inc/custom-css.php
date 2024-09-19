<?php
/**
 * Dynamic CSS is required for modifications to Typography, Colors, and Custom CSS.
 */
class Bunyad_Theme_CustomCSS
{
	public function __construct()
	{
		add_action('after_setup_theme', [$this, 'init'], 12);
		add_filter('bunyad_custom_css_processed', [$this, 'add_category_css']);

		/**
		 * Flush cache hooks.
		 */
		// Flush cache on category edits for terms meta.
		add_filter('create_category', [$this, 'flush_cache']);
		add_filter('edited_category', [$this, 'flush_cache']);

		add_action('customize_save', [$this, 'flush_cache']);
		add_action('bunyad_import_done', [$this, 'flush_cache']);
		add_action('bunyad_options_reset', [$this, 'flush_cache']);
	}
	
	public function init()
	{	
		// Has to be at less than 200 priority for core.php inline enqueues to work.
		add_action('wp_enqueue_scripts', [$this, 'register_custom_css'], 99);
	}
	
	/**
	 * Remove any custom CSS parser caches.
	 * 
	 * Note: May also be called directly, part of API.
	 */
	public function flush_cache()
	{
		delete_transient('bunyad_custom_css_cache');
		delete_transient('bunyad_custom_css_state');
	}
	
	/**
	 * Check if the theme has any custom css
	 */
	public function has_custom_css()
	{
		if (is_customize_preview()) {
			$css_state = false;
		}
		else {
			$css_state = apply_filters('bunyad_custom_css_state', get_transient('bunyad_custom_css_state'));
		}

		// State 1/truthy: It's known Custom CSS exists.
		if ($css_state) {
			return true;
		}

		// State 0/falsey (except false): Custom CSS was checked and not found.
		if ($css_state !== false && !$css_state) {
			return false;
		}

		// We don't know yet if there's custom CSS.
		if ($css_state === false) {

			$return = false;
			$state  = 0;

			include_once get_template_directory() . '/inc/core/customizer/css-generator.php';

			$css = new Bunyad_Customizer_Css_Generator;
			$css->init();

			// Test for customizer CSS changes and Category/term meta CSS.
			if (count($css->get_css_elements()) || count($this->get_category_css_terms())) {
				$return = true;
				$state  = true;
			}

			// Don't store transient in preview as changesets can differ.
			if (!is_customize_preview()) {
				set_transient('bunyad_custom_css_state', $state);
			}

			return $return;
		}
	}

	/**
	 * Action callback: Register Custom CSS with low priority 
	 */
	public function register_custom_css()
	{
		if (is_admin()) {
			return;
		}

		$rendered_css = '';

		// Add parsed and likely cached custom css.
		if ($this->has_custom_css()) {
			
			include_once get_template_directory() . '/inc/core/customizer/css-generator.php';

			$query_args = array('bunyad_custom_css' => 1);
			
			// Setup renderer
			$render = new Bunyad_Customizer_Css_Generator;
			$render->default_fonts = [
				'primary'   => 'Public Sans',
				'secondary' => 'Public Sans',
			];

			$render->args = $query_args;
			$rendered_css = $render->render();

			// Setup Google Fonts enqueue.
			$google_fonts = $render->get_google_fonts_url();
			if (!Bunyad::options()->google_fonts_disable && $google_fonts) {
				wp_enqueue_style('smartmag-gfonts-custom', $google_fonts, array(), null);
			}
		}

		// Add per-page Custom CSS.
		if (is_singular()) {

			if (Bunyad::options()->layout_type === 'boxed' && Bunyad::posts()->meta('bg_image')) {
				$rendered_css .= "\n";
				$rendered_css .= sprintf(
					'.layout-boxed .ts-bg-cover { background-image: url("%s"); }',
					esc_url( Bunyad::posts()->meta('bg_image'))
				);
			}
		}

		// Enqueue the rendered CSS.
		if ($rendered_css) {

			// Associate custom css at the end.
			$source = 'smartmag-core';
			$check  = array_reverse([
				'smartmag-woocommerce', 'smartmag-skin', 'smartmag-child'
			]);

			foreach ($check as $sheet) {
				if (wp_style_is($sheet, 'enqueued')) {
					$source = $sheet;
					break;
				}
			}
			
			// Add to on-page custom css
			Bunyad::core()->enqueue_css(
				$source, 
				apply_filters('bunyad_custom_css_enqueue', $rendered_css)
			);
		}
	}

	/**
	 * Get all the terms with an option set that would need CSS generation.
	 *
	 * @return array
	 */
	public function get_category_css_terms()
	{
		$terms = get_terms([
			'taxonomy' => 'category',
		]);

		$valid_terms = [];
		foreach ($terms as $term) {
			$meta  = Bunyad::posts()->term_meta(null, $term->term_id);

			if (!empty($meta['main_color']) || !empty($meta['color']) || !empty($meta['bg_image'])) {
				$valid_terms[] = $term;
			}
		}

		return $valid_terms;
	}

	/**
	 * Add required CSS for categories.
	 *
	 * @param string $css
	 * @return string
	 */
	public function add_category_css($css)
	{
		$terms       = $this->get_category_css_terms();
		$terms_css   = [];

		foreach ($terms as $term) {

			$meta  = Bunyad::posts()->term_meta(null, $term->term_id);

			// Change main site color.
			if (!empty($meta['main_color'])) {
				$terms_css[] = sprintf(
					'
						body.category-%1$s,
						body.post-cat-%1$s { 
							--c-main: %2$s; 
						}
					',
					$term->term_id,
					esc_attr($meta['main_color'])
				);
			}
			
			if (!empty($meta['color'])) {
				$terms_css[] = sprintf(
					'
						.term-color-%1$s { --c-main: %2$s; }
						.navigation .menu-cat-%1$s { --c-term: %2$s; }
					',
					$term->term_id,
					esc_attr($meta['color'])
				);
			}

			// Background image.
			if (!empty($meta['bg_image'])) {

				$terms_css[] = sprintf(
					'.layout-boxed.category-%1$s .ts-bg-cover { background-image: url("%2$s"); }',
					$term->term_id,
					esc_url($meta['bg_image'])
				);
			}
		}

		if (!$terms_css) {
			return $css;
		}

		return $css . join("\n", $terms_css);
	}
}

// init and make available in Bunyad::get('custom_css')
Bunyad::register('custom_css', array(
	'class' => 'Bunyad_Theme_CustomCSS',
	'init' => true
));