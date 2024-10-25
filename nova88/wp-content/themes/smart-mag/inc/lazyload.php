<?php
/**
 * Lazyload images for speed
 */
class Bunyad_Theme_Lazyload
{
	public $image_sizes;
	public $svg_cache;

	/**
	 * Flag to enable/disable images that follow in queue
	 */
	public $disabled = false;
	public $prev_state = false;
	
	public function __construct()
	{
		if (is_admin()) {
			return;
		}

		// Bunyad::options() isn't initialized yet, wait for it
		add_action('after_setup_theme', [$this, 'init']);

		// Process image attribs.
		add_filter('bunyad_media_image_options', [$this, 'process_media'], 10, 2);
	}

	public function process_media($args, $size)
	{
		if (!empty($args['no_lazy'])) {
			$args['attr']['loading'] = '';
			$args['attr']['class'] = "attachment-$size size-$size no-lazy skip-lazy";
		}

		return $args;
	}
	
	/**
	 * All core ready - initialize
	 */
	public function init()
	{
		if (!Bunyad::options()->lazyload_enabled) {
			return;
		}
		
		// Add attributes for the normal img tags, masonry, some sliders, or legacy.
		add_filter('wp_get_attachment_image_attributes', [$this, 'image_attribs'], 10, 3);

		// Add attributes to the new background images.
		add_filter('bunyad_media_bg_image_attribs', [$this, 'bg_image_attribs']);

		/**
		 * Native lazyload for iframes in content. No longer needed after WP v5.7.
		 * WP 5.5+ = No longer needed for images, but still needed for iframes.
		 */
		if (!function_exists('wp_iframe_tag_add_loading_attr')) {
			$native_lazy = function($content) {
				return $this->process_content($content, 'native');
			};

			add_filter('the_content', $native_lazy);
		}

		// We have our custom lazyload, don't use native lazyloading for featured media.
		add_filter('bunyad_media_post_thumbnail_args', function($args) {
			$args['attr']['loading'] = '';
			return $args;
		});

		// Aggressive lazyload for sidebar and footer
		if (Bunyad::options()->lazyload_aggressive) {

			// WPBakery conflicts in preview with footer buffer and widgets won't update.
			// vc_is_page_editable() isn't reliable at this point.
			if (!empty($_GET['vc_editable'])) { 
				return;
			}

			add_action('dynamic_sidebar_before', [$this, 'start_buffer'], 10);
			add_action('dynamic_sidebar_after', [$this, 'process_buffer'], 10);
		
			add_action('bunyad_pre_footer', [$this, 'start_buffer'], 10);
			add_action('wp_footer', [$this, 'process_buffer'], 2);
		}

		// Enable bg images for thumbnails in lazyload, where possible.
		add_filter('bunyad_media_image_options', function($options) {
			if ($this->should_lazy() && $options['bg_image'] === null) {
				$options['bg_image'] = 'auto';
			}

			return $options;
		});

		// Cache image sizes - used by image_attribs()
		$this->image_sizes = $this->get_registered_sizes();
		
		// Earliest in foot. We don't want it to be a blocking asset in header. 
		add_action('wp_enqueue_scripts', [$this, 'register_assets'], 1);
	}
	
	/**
	 * Setup the JS file - earlier in header.
	 */
	public function register_assets()
	{
		wp_enqueue_script(
			'smartmag-lazyload', 
			get_template_directory_uri() . '/js/lazyload.js', 
			[], 
			BUNYAD_THEME_VERSION, 
			true // class_exists('\Sphere\Debloat\Plugin', false) ? false : true
		);

		wp_localize_script('smartmag-lazyload', 'BunyadLazy', [
			'type' => Bunyad::options()->lazyload_type
		]);
	}
	
	/**
	 * Disable lazy flags addition on images that follow
	 */
	public function disable() {
		$this->prev_state = $this->disabled;
		$this->disabled   = true;
		return $this;
	}
	
	/**
	 * Re-enable lazy load (enabled by default)
	 */
	public function enable() {
		$this->prev_state = $this->disabled;
		$this->disabled   = false;
		return $this;
	}

	/**
	 * Restore to previous state (as set by enable()/disable())
	 */
	public function restore() {
		$this->disabled = $this->prev_state;
		return $this;
	}
	
	/**
	 * Start capturing content to filter later
	 */
	public function start_buffer()
	{
		// Capture sidebar input
		ob_start();
	}
	
	public function process_buffer()
	{
		$content = ob_get_clean();
		$content = $this->process_content($content);

		echo $content;
	}
	
	/**
	 * Process Raw HTML to find and replace images
	 */
	public function process_content($content = '', $type = '')
	{
		if (!$this->should_lazy()) {
			return $content;
		}
		
		preg_match_all('#<(img|iframe)[^>]*>#is', $content, $matches);
		$elements = $matches[0];
		
		foreach ($elements as $key => $element) {
			
			$updated = '';
			$tag     = $matches[1][$key];
			
			// Parse the tag attributes.
			// @todo More testing on regex OR use wp_kses_hair()
			preg_match_all('#(?P<name>[a-z\-]+)=("|\')(?P<value>.*?)\2#is', $element, $match);
			if (empty($match['name'])) {
				continue;
			}
			
			$attr = array_combine((array) $match['name'], (array) $match['value']);
			
			// @todo Include more skips when doing more aggressive lazyload in future.
			if (!$this->should_lazy_image($attr)) {
				continue;
			}

			// Native lazyload has to be handled differently.
			if ($type == 'native') {

				// Skip if an existing loading is defined - which maybe eager.
				if (isset($attr['loading'])) {
					continue;
				}

				$attr['loading'] = 'lazy';
			}
			else {

				// Extend some defaults
				$attr = array_merge([
					'class' => ''
				], $attr);
					
				// Add class
				$attr['class'] .= ' lazyload';
				
				$width  = (!empty($attr['width']) ? $attr['width'] : 1);
				$height = (!empty($attr['height']) ? $attr['height'] : 1);
				
				// Generate src
				$attr['data-src'] = $attr['src'];
				$attr['src'] = $this->svg_placeholder($width, $height);
				
				// Set srcset if exists
				if (!empty($attr['data-srcset'])) {
					$attr['data-srcset'] = $attr['srcset'];
					unset($attr['srcset']);
				}
			}

			$updated = '<' . esc_attr($tag) . ' '
				. Bunyad::markup()->attribs('lazy', $attr, ['esc_src_url' => 0, 'echo' => 0])
				. ($tag == 'img' ? ' />' : '>');

			$content = str_replace($element, $updated, $content);

		}
		
		return $content;
	}
	
	/**
	 * Check if lazy load should be applied
	 */
	public function should_lazy()
	{
		if ($this->disabled || is_feed() || is_preview() || is_admin() || is_embed()) {
			return false;
		}

		// Disable for all REST requests. We don't use REST api but AJAX instead for blocks, so it's alright.
		// Note: This only works after 'init' action but it's fine here.
		if (defined('REST_REQUEST') && REST_REQUEST) {
			return false;
		}

		// WPBakery page builder loads shortcode this way.
		// vc_is_page_editable() isn't reliable at this point.
		if (!empty($_GET['vc_editable'])) { 
			return false;
		}
		
		if (function_exists('amp_is_request') && amp_is_request()) {
			return false;
		}
		
		// WooCommerce image zoom is bugged
		if (function_exists('is_product') && is_product()) {
			return false;
		}

		// WP Recipe Maker print.
		if (did_action('wprm_print_output') || doing_action('wprm_print_output')) {
			return false;
		}
		
		// Filter that can disable lazyload by returning false
		if (!apply_filters('bunyad_lazyload_enabled', true)) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Skip adding lazyload if meets certain conditions, given the attributes of an image.
	 *
	 * @param array $attrs
	 * @return boolean 
	 */
	public function should_lazy_image($attrs)
	{
		if (empty($attrs) || !is_array($attrs)) {
			return true;
		}

		if (isset($attrs['class'])) {
			$skip_classes = [
				// Already lazyloaded.
				'lazyload',

				// Manual skip like wprocket.
				'no-lazy',

				// Autoptimize compat and others.
				'skip-lazy'
			];
			
			$classes = array_map('trim', explode(' ', $attrs['class']));
			if (array_intersect($skip_classes, $classes)) {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Add image attributes 
	 * 
	 * @see wp_get_attachment_image()
	 * @param string $size
	 */
	public function image_attribs($attr, $attachment, $size) 
	{
		if (!$this->should_lazy() || !$this->should_lazy_image($attr)) {
			return $attr;
		}

		/**
		 * Get width and height
		 */
		$width  = 1;
		$height = 1;
		
		$attachment = wp_get_attachment_metadata($attachment->ID);
		
		if (is_string($size)) {
			
			// From attachment metadata first - fallback to global setting next
			if (!empty($attachment['sizes']) && array_key_exists($size, $attachment['sizes'])) {
				$info   = $attachment['sizes'][$size];
			}
			else if (array_key_exists($size, $this->image_sizes)) {
				$info   = $this->image_sizes[$size];

			}
			
			if (!empty($info)) {
				$width  = $info['width'];
				$height = $info['height'];
			}
		}
		
		// Have srcset?
		if (!empty($attr['srcset'])) {
			
			$attr['data-srcset'] = $attr['srcset'];
			unset($attr['srcset']);
		}

		// Add placeholder and move orig to data-src
		$attr['data-src'] = $attr['src'];
		$attr['src']      = esc_attr($this->svg_placeholder($width, $height));
		$attr['class']   .= ' lazyload';
		
		return $attr;
	}

	/**
	 * Filter callback: Add the lazyload class to bg images.
	 * 
	 * Filter: bunyad_media_bg_image_attribs
	 */
	public function bg_image_attribs($attrs) 
	{
		if (!$this->should_lazy() || !$this->should_lazy_image($attrs)) {
			return $attrs;
		}

		// Add lazy load class.
		$attrs['class']  = !isset($attrs['class']) ? '' : $attrs['class'];
		$attrs['class'] .= ' lazyload';

		return $attrs;
	}
	
	/**
	 * Create an SVG placeholder for data URI
	 * 
	 * @param integer $width
	 * @param integer $height
	 * @return string Data URI format svg
	 * 
	 */
	public function svg_placeholder($width = 1, $height = 1)
	{
		$id = "{$width}x{$height}";
		
		if (!empty($this->svg_cache[$id])) {
			return $this->svg_cache[$id];
		}
		
		$svg = "<svg viewBox='0 0 {$width} {$height}' xmlns='http://www.w3.org/2000/svg'></svg>";
		$svg = base64_encode($svg);
		
		return ($this->svg_cache[$id] = 'data:image/svg+xml;base64,' . $svg); 
	}
	
	/**
	 * Get all registered image sizes, including default ones
	 * 
	 * @link http://core.trac.wordpress.org/ticket/18947 Reference ticket
	 */
	public function get_registered_sizes()
	{
		global $_wp_additional_image_sizes;
	
		$default_sizes = ['thumbnail', 'medium', 'medium_large', 'large'];
		 
		foreach ($default_sizes as $size) {
			$image_sizes[$size]['width']  = intval(get_option("{$size}_size_w"));
			$image_sizes[$size]['height'] = intval(get_option("{$size}_size_h"));
			$image_sizes[$size]['crop']   = get_option("{$size}_crop") ? get_option("{$size}_crop") : false;
		}
		
		if (!empty($_wp_additional_image_sizes)) {
			$image_sizes = array_merge($image_sizes, $_wp_additional_image_sizes);
		}
			
		return $image_sizes;
	}
}

// init and make available in Bunyad::get('lazyload')
Bunyad::register('lazyload', [
	'class' => 'Bunyad_Theme_Lazyload',
	'init'  => true
]);