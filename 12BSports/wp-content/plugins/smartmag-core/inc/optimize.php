<?php
/**
 * Performance optimizations and relevant plugins compatibility.
 */
class SmartMag_Optimize
{
	private $_defer_fonts = [];
	protected $record_media = false;
	protected $recorded_media = [];
	
	public function __construct()
	{
		// Hooking at wp as we need is_amp_endpoint() available to disable for amp
		add_action('wp', [$this, 'init']);
	}

	public function init()
	{
		// AMP? Stop.
		if (function_exists('amp_is_request') && amp_is_request()) {
			return;
		}

		if (defined('AUTOPTIMIZE_PLUGIN_DIR')) {
			$this->autoptimize_plugin();
		}

		// Fix jetpack devicepx script to be deferred.
		add_filter('script_loader_tag', [$this, 'jetpack_defer'], 10, 2);

		if (Bunyad::options()->perf_lazy_oembed) {
			if (
				!class_exists('\Sphere\Debloat\Plugin') ||
				false === strpos(\Sphere\Debloat\Plugin::options()->delay_js_includes, 'twitter.com')
			) {
				add_filter('embed_oembed_html', [$this, 'lazy_oembed']);
			}
		}

		/**
		 * Other optimizations that may be injected into content.
		 */
		$other_optimizations = Bunyad::options()->perf_preload_featured;
		if ($other_optimizations) {

			// We are already at hook 'wp' here.
			ob_start([$this, 'process_buffer']);
		}

		$this->preload_featured();
		$this->lazy_mega_menu_images();

		// Elementor assets to be removed if enabled.
		add_action('elementor/frontend/after_enqueue_styles', [$this, 'remove_elementor_assets'], 999);
		add_action('elementor/frontend/after_enqueue_scripts', [$this, 'remove_elementor_assets'], 999);

		// Disable lazyload on first image.
		add_action('bunyad_header_after', [$this, 'skip_lazy_load']);
	}

	/**
	 * Disable lazyload for the first block post image for LCP.
	 */
	public function skip_lazy_load()
	{
		if (!Bunyad::options()->lazyload_skip_number) {
			return;
		}

		// Not for single posts. Mainly for archives and where blocks are involved.
		if (!(is_archive() || is_front_page() || is_home())) {
			return;
		}

		$set_no_lazy = function($args) use (&$set_no_lazy) {
			static $counter = 0;
			$counter++;

			$skip = absint(Bunyad::options()->lazyload_skip_number);
			if ($counter > $skip) {
				remove_filter('bunyad_media_image_options', $set_no_lazy, 9);
				return $args;
			}
			
			$args['no_lazy'] = true;
			return $args;
		};

		add_filter('bunyad_media_image_options', $set_no_lazy, 9);
	}

	/**
	 * Mega menu should always be lazy loaded, so change context.
	 * 
	 * Note: Only required if bgsrc images are disabled.
	 */
	public function lazy_mega_menu_images()
	{
		if (!Bunyad::options()->perf_disable_bg_images) {
			return;
		}

		$add_mega_context = function($context) {
			return 'bunyad_mega_menu';
		};

		add_action('bunyad_mega_menu_render_before', function() use ($add_mega_context) {
			add_filter('wp_get_attachment_image_context', $add_mega_context, 99);
		});

		add_action('bunyad_mega_menu_render_after', function() use ($add_mega_context) {
			remove_filter('wp_get_attachment_image_context', $add_mega_context, 99);
		});
	}

	/**
	 * Record featured grid or featured images and add as preload.
	 */
	public function preload_featured()
	{
		if (!Bunyad::options()->perf_preload_featured) {
			return;
		}

		$begin_record = function() {
			add_filter('bunyad_media_image_html', [$this, '_record_media'], 10, 4);
			$this->record_media = true;
		};

		$end_record = function() {
			if ($this->record_media) {
				remove_filter('bunyad_media_image_html', [$this, '_record_media'], 10, 4);
				$this->record_media = false;
			}
		};

		// Only for home, category etc.
		if (!is_single()) {
			add_action('bunyad_blocks_loop_render', function($block) use ($begin_record) {
				if ($block->id !== 'feat-grid' || $this->recorded_media) {
					$this->record_media = false;
					return;
				}

				$begin_record();
			});

			add_action('bunyad_blocks_loop_render_after', $end_record);
		}	

		// Single page only.
		if (is_single() && has_post_thumbnail()) {
			add_action('bunyad_post_featured_before', $begin_record);
			add_action('bunyad_post_featured_after', $end_record);
		}
	}

	public function _record_media($html, $args, $size, $img_attribs = []) 
	{
		$post = get_post();
		if ($post && !empty($args['width'])) {
			$this->recorded_media[] = [
				'id'   => $post->ID,
				'args' => $args,
				'size' => $size,
				'img_attribs' => $img_attribs,
				'html' => $html
			];
		}

		return $html;
	}

	/**
	 * Process the content to add our optimizations.
	 */
	public function process_buffer($content)
	{
		/**
		 * Add TS icons to preload.
		 */
		if (wp_style_is('smartmag-icons', 'enqueued')) {
			$preload_icon = $this->preload_tag(
				get_template_directory_uri() . '/css/icons/fonts/ts-icons.woff2?v2.7',
				'font',
				false,
				[
					'type' => 'font/woff2',
					'crossorigin' => 'anonymous'
				]
			);

			$content = $this->inject_to_head($preload_icon, $content, true);
		}

		/**
		 * Inject media preload, if available.
		 */
		if ($this->recorded_media) {
			if (is_single()) {
				$media = $this->recorded_media[0];
			}
			else {

				// Returns largest or first (if all equals).
				$media = array_reduce($this->recorded_media, function($prev, $item) {
					if (!$prev) {
						$prev = $item;
					}

					if ($prev['args']['width'] < $item['args']['width']) {
						return $item;
					}

					return $prev;
				});
			}

			if (!empty($media['img_attribs']['src'])) {
				$preload = $this->get_media_preload($media);
				$content = $this->inject_to_head($preload, $content, true);

				// Add class for first image.
				if (Bunyad::options()->perf_first_img_class) {
					$html = str_replace('class="', 'class="ts-first-image ', $media['html']);
					$content = str_replace($media['html'], $html, $content);
				}
			}
		}

		return $content;
	}

	/**
	 * Get preload tag based on a media array.
	 * 
	 * @param array $media {
	 *    @type array $img_attribs
	 * }
	 */
	protected function get_media_preload($media) 
	{
		$srcset = $media['img_attribs']['src'];
		if (!empty($media['img_attribs']['srcset'])) {
			$srcset = $media['img_attribs']['srcset'];
		}

		return $this->preload_tag(null, 'image', false, [
			'imagesrcset'   => $srcset,
			'imagesizes'    => $media['img_attribs']['sizes'],
			// 'fetchpriority' => 'high'
		]);
	}

	/**
	 * Inject content into head tag of the buffer.
	 */
	protected function inject_to_head($inject, $buffer, $early = false)
	{
		$tag = $early ? '</title>' : '</head>';
		return str_replace($tag, ($early ? $tag . $inject : $inject . $tag), $buffer);
	}

	/**
	 * Convert oembed to lazy, currently for twitter.
	 */
	public function lazy_oembed($html)
	{
		if (strpos($html, 'platform.twitter.com/widgets.js') === false) {
			return $html;
		}

		$html = preg_replace('/(<script[^>]*)src=/', '\\1data-type="lazy" data-src=', $html);
		return $html;
	}

	/**
	 * Autoptimize plugin "defer" mode requires several changes to how things work
	 */
	public function autoptimize_plugin()
	{
		$css_optimize = get_option('autoptimize_css');
		$css_defer    = get_option('autoptimize_css_defer');
		
		// CSS defer enabled, override the default polyfill.
		if ($css_optimize && $css_defer) {
			add_filter('autoptimize_css_preload_polyfill', [$this, 'get_loadcss_polyfill']);
		}

		// Just the CSS optimize
		if ($css_optimize) {
			add_action('wp_enqueue_scripts', [$this, 'dequeue_fonts'], 11);
			add_action('wp_head', [$this, 'defer_fonts']);
			
			// CSS defer, when enabled, will add the polyfill on its own.
			// Note: This polyfill is still required for Firefox.
			if (!$css_defer) {
				add_action('wp_footer', [$this, 'loadcss_polyfill'], 999);
			}

			// Add preload for VC styles.
			add_action('wp_print_styles', function() {

				// VC
				global $wp_styles;
				
				if (
					!is_object($wp_styles) 
					|| !property_exists($wp_styles, 'registered') 
					|| !array_key_exists('js_composer_front', $wp_styles->registered)
				) {
					return;
				}
	
				if (!is_object($wp_styles->registered['js_composer_front'])) {
					return;
				}
	
				$source = $wp_styles->registered['js_composer_front']->src;
				wp_deregister_style('js_composer_front');
	
				echo $this->preload_tag($source);
	
			}, 999);
		}

		// Jetpack bug: https://github.com/Automattic/jetpack/issues/14281
		// remove_action('wp_enqueue_scripts', 'jetpack_woocommerce_lazy_images_compat', 11);

		// Lazysizes should be run earlier.
		add_filter('autoptimize_filter_js_exclude', function($exclude) {
			return $exclude . ',lazyload.js';
		});

		add_filter('autoptimize_filter_js_consider_minified', function($minified) {
			$minified = is_array($minified) ? $minified : [];
			return array_merge($minified, ['lazyload.js']);
		});

		// Remove wp-content/uploads as elementor uses it.
		add_filter('autoptimize_filter_css_exclude', function($excludes) {
			$excludes = is_string($excludes) ? $excludes : '';
			$excludes = preg_replace('#wp-content/uploads/?\s*(,|$)#', '', $excludes);
			return $excludes;
		});

		// Preload some JS early on.
		add_action('wp_head', [$this, 'autoptimize_preload_js'], 2);
	}
	
	/**
	 * Remove google font queue in the header
	 */
	public function dequeue_fonts()
	{
		$prefix = Bunyad::options()->get_config('theme_prefix');

		// Google fonts as default
		if (wp_style_is($prefix . '-fonts', 'enqueued')) {
			
			// Set flag
			$this->_defer_fonts[] = 'google';
			
			// Dequeue it for now
			wp_dequeue_style($prefix . '-fonts');
		}

		// TypeKit active?
		if (wp_style_is($prefix . '-typekit', 'enqueued')) {
			$this->_defer_fonts[] = 'typekit';

			// Dequeue it
			wp_dequeue_style($prefix . '-typekit');
		}
	}

	/**
	 * Preload some critical JS, at minimum.
	 */
	public function autoptimize_preload_js()
	{
		if (wp_script_is('smartmag-lazyload', 'enqueued')) {
			$script = wp_scripts()->query('smartmag-lazyload');
			echo $this->preload_tag($script->src . '?ver=' . $script->ver, 'script', false);
		}
	}
	
	/**
	 * Add preload for fonts.
	 */
	public function defer_fonts()
	{
		$theme_obj = Bunyad::get('theme');
		if (in_array('google', $this->_defer_fonts) && method_exists($theme_obj, 'get_fonts_enqueue')) {
				
			echo $this->preload_tag($theme_obj->get_fonts_enqueue());
		}

		//
		// To use Preload tag instead for TypeKit:
		// 	    echo $this->preload_tag('https://use.typekit.net/'. Bunyad::options()->typekit_id .'.css');
		//

		if (in_array('typekit', $this->_defer_fonts)) {
			?>
			<script>
			  (function(d) {
			    var config = {
			      kitId: '<?php echo esc_js(Bunyad::options()->typekit_id); ?>',
			      scriptTimeout: 3000,
			      async: true
			    },
			    h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
			  })(document);
			</script>
			<?php

		}
	}

	/**
	 * Output the preload tag
	 * 
	 * @param string $url   The script/style url
	 * @param string $type  style or script
	 * @param string $apply Whether to apply the style immediately
	 * @param array  $attrs Extra attributes
	 */
	public function preload_tag($url = '', $type = 'style', $apply = true, $attrs = [])
	{
		if ($type !== 'style' && $apply) {
			$apply = false;
		}

		$the_attrs = ['as' => $type];

		// href has to be omitted in some cases like for imagesrcset, so old browsers can ignore it.
		if (!empty($url)) {
			$the_attrs['href'] = esc_url($url);
		}

		if ($apply && $type === 'style') {
			$the_attrs['onload'] = "this.onload=null;this.rel='stylesheet'";
		}

		if ($type === 'style') {
			$the_attrs['media']  = 'all';
		}

		// Everything above can be overridden.
		$the_attrs = array_replace($the_attrs, $attrs);

		return sprintf(
			'<link rel="preload" %s />',
			Bunyad::markup()->attribs('preload-tag', $the_attrs, ['echo' => false])
		);
	}

	/**
	 * Quick CSS / JS minify that doesn't do much at all 
	 */
	public function _quick_minify($text)
	{
		return str_replace(["\r", "\n", "\t"], '', $text);
	}
	
	/**
	 * Fix Jetpack not using deferred JS for devicepx 
	 */
	public function jetpack_defer($tag, $handle)
	{		
		if ($handle == 'devicepx') {
			$tag = str_replace('src=', 'defer src=', $tag);
		}
		
		return $tag;
	}

	/**
	 * Add loadCSS polyfill 
	 */
	public function get_loadcss_polyfill($existing = '')
	{
		// First few lines modified to hook into DOMContentLoaded
		$preloadPolyfill = '
		<script data-cfasync=\'false\'>
		var t = window;
		document.addEventListener("DOMContentLoaded", 
			function(){
				t.loadCSS||(t.loadCSS=function(){});var e=loadCSS.relpreload={};if(e.support=function(){var e;try{e=t.document.createElement("link").relList.supports("preload")}catch(t){e=!1}return function(){return e}}(),e.bindMediaToggle=function(t){function e(){t.media=a}var a=t.media||"all";t.addEventListener?t.addEventListener("load",e):t.attachEvent&&t.attachEvent("onload",e),setTimeout(function(){t.rel="stylesheet",t.media="only x"}),setTimeout(e,3e3)},e.poly=function(){if(!e.support())for(var a=t.document.getElementsByTagName("link"),n=0;n<a.length;n++){var o=a[n];"preload"!==o.rel||"style"!==o.getAttribute("as")||o.getAttribute("data-loadcss")||(o.setAttribute("data-loadcss",!0),e.bindMediaToggle(o))}},!e.support()){e.poly();var a=t.setInterval(e.poly,500);t.addEventListener?t.addEventListener("load",function(){e.poly(),t.clearInterval(a)}):t.attachEvent&&t.attachEvent("onload",function(){e.poly(),t.clearInterval(a)})}"undefined"!=typeof exports?exports.loadCSS=loadCSS:t.loadCSS=loadCSS
			}
		);
		</script>';

		return $preloadPolyfill;
	}

	/**
	 * Load CSS polyfill in footer
	 */
	public function loadcss_polyfill($content) {
		echo $this->get_loadcss_polyfill();
	}

	/**
	 * Remove disabled Elementor assets.
	 */
	public function remove_elementor_assets()
	{
		// Elementor is necessary.
		if (!did_action('elementor/loaded')) {
			return;
		}

		$assets = (array) Bunyad::options()->perf_disable_elementor_assets;
		if (!$assets) {
			return;
		}

		$is_editor = did_action('elementor/preview/init');
		foreach ($assets as $asset) {

			if ($asset === 'animations') {
				wp_dequeue_style('elementor-animations');
				!$is_editor || wp_dequeue_script('elementor-waypoints');
				continue;
			}

			// Don't remove JS, and certain CSS in editor or there can be errors.
			if ($is_editor) {
				continue;
			}

			// Won't be removed when logged in, as elementor-common is added which needs it.
			if ($asset === 'icons') {
				wp_dequeue_style('elementor-icons');
				continue;
			}

			wp_dequeue_script('elementor-' . str_replace('-js', '', $asset));
		}
	}
}

// init and make available in Bunyad::get('smartmag_optimize')
Bunyad::register('smartmag_optimize', [
	'class' => 'SmartMag_Optimize',
	'init'  => true
]);