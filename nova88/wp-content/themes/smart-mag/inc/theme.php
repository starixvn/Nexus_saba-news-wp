<?php
/**
 * SmartMag Theme!
 * 
 * Anything theme-specific that won't go into the core framework goes here. 
 * 
 * Also see: inc/admin.php for the admin bootstrap.
 */
class Bunyad_Theme_SmartMag
{	
	/**
	 * Ensures Bunyad::theme() works for all files included in constructor.
	 * @see Bunyad_Base::factory()
	 */
	public static $singleton = true;
	
	public function __construct() 
	{
		// init skins
		add_action('bunyad_core_post_init', [$this, 'init_skins']);
		
		// perform the after_setup_theme 
		add_action('after_setup_theme', [$this, 'theme_init'], 12);

		// Early initialize of i18n to ensure it's available at options setup.
		$this->init_i18n();

		/**
		 * Loader and register autoloader for some namespaces.
		 */
		// Ensure it wasn't already loaded by a plugin.
		if (!class_exists('\Bunyad\Lib\Loader', false)) {
			require_once get_theme_file_path('lib/loader.php');
		}
		
		$path       = trailingslashit(get_template_directory());
		$namespaces = [
			'Bunyad_Theme_'           => $path . 'inc', 
			'SmartMag\Integrations\\' => $path . 'inc/integrations', 
			'Bunyad\Blocks\\'  => [
				'search_reverse' => true,
				'paths'          => $path . 'blocks',
			],
			'Bunyad\Core\\' => $path . 'inc/core',

			// Fallback for everything else in Bunyad namespace.
			'Bunyad\\'  => $path . 'inc'
		];

		// Prepended to be higher priority than other loaders.
		$loader = new \Bunyad\Lib\Loader($namespaces, true);
		$loader->set_is_theme();

		require_once get_theme_file_path('lib/util.php');
		
		/**
		 * Require theme functions, hooks, and helpers.
		 * 
		 * Can be overriden in Child Themes by creating the same structure. For
		 * instance, you can create inc/block.php in your Child Theme folder
		 * and that will be included.
		 * 
		 * The includes below can be retrieved using the Bunyad::get() method.
		 * 
		 * @see Bunyad::get()
		 */
		//require_once locate_template('inc/block.php');
		require_once get_theme_file_path('blocks/helpers.php');
		require_once get_theme_file_path('inc/navigation.php');
		require_once get_theme_file_path('inc/customizer.php');
		require_once get_theme_file_path('inc/custom-css.php');
		require_once get_theme_file_path('inc/schema.php');
		require_once get_theme_file_path('inc/media.php');
		require_once get_theme_file_path('inc/amp/amp.php');
		require_once get_theme_file_path('inc/custom-codes.php');
		require_once get_theme_file_path('inc/archives.php');
		require_once get_theme_file_path('inc/dark-mode.php');
		require_once get_theme_file_path('inc/icons.php');

		// Authentication related functionality.
		require_once get_theme_file_path('inc/authenticate.php');

		// Legacy / Compat.
		require_once get_theme_file_path('inc/legacy.php');

		// Social sharing / follow related.
		require_once get_theme_file_path('inc/social.php');

		// Lazyloading feature.
		require_once get_theme_file_path('inc/lazyload.php');

		// Search related features.
		require_once get_theme_file_path('inc/search.php');

		// Theme updates - has to be also loaded on non-admin as in with WP core.
		require_once get_theme_file_path('inc/admin/theme-updates.php');
		
		// Admin only or when wpcli is used.
		if (is_admin() || defined('WP_CLI')) {
		
			// Admin (backend) functionality
			require_once get_theme_file_path('inc/admin.php');
		}
			
		if (function_exists('is_woocommerce')) {
			// Init and make available in Bunyad::get('woocommerce')
			Bunyad::register('woocommerce', [
				'class' => 'SmartMag\Integrations\Woocommerce',
				'init'  => true
			]);
		}

		// Include bbPress support.
		if (class_exists('bbpress')) {
			Bunyad::register('bbpress', [
				'class' => 'SmartMag\Integrations\Bbpress',
				'init'  => true
			]);
		}

		// Debloat plugin support.
		if (class_exists('\Sphere\Debloat\Plugin')) {
			Bunyad::register('integrations_debloat', [
				'class' => 'SmartMag\Integrations\Debloat',
				'init'  => true
			]);
		}

		/**
		 * Sphere core configs.
		 */
		add_filter('sphere/adblock/options_strings', function($strings) {
			return array_replace($strings, [
				'title'   => esc_html__('Ad Blocker Enabled!', 'bunyad'),
				'message' => esc_html__('Our website is made possible by displaying online advertisements to our visitors. Please support us by disabling your Ad Blocker.', 'bunyad'),
			]);
		});
	}
	
	/**
	 * Setup any skin data and configs
	 */
	public function init_skins()
	{
		// Include our skins constructs
		if (Bunyad::options()->predefined_style) {
			
			$style = $this->get_style();
			
			if (!empty($style['bootstrap'])) {
				require_once get_theme_file_path($style['bootstrap']);
			}
		}
	}
	
	/**
	 * i18n
	 * 
	 * We have split front-end and backend translations in separate files. 
	 * 
	 * For en_US, following files be used:
	 * 
	 * smart-mag/languages/en_US.mo
	 * smart-mag/languages/bunyad-admin-en_US.mo
	 * 
	 * @see Bunyad_Thmeme_SmartMag::load_admin_textdomain()
	 */
	public function init_i18n()
	{
		load_theme_textdomain('bunyad', get_template_directory() . '/languages');
		
		if (is_admin()) {
			$this->load_admin_textdomain('bunyad-admin', get_template_directory() . '/languages');
		}
	}
	
	/**
	 * Setup enque data and actions
	 */
	public function theme_init()
	{
		/**
		 * Use this hook instead of after_setup_theme to keep the priority setting
		 * consistent amongst all helpers and utils.
		 */
		do_action('bunyad_theme_init');

		$this->register_images();
		
		// Register our hook at template_redirect as we want to it to be after 
		// Elementor enqueues its frontend assets.
		add_action('template_redirect', function() {
			add_action('wp_enqueue_scripts', [$this, 'register_assets']);
		}, 11);

		// A bugged plugin or WPMU signup may use wp_enqueue_scripts without template_redirect.
		add_action('wp_enqueue_scripts', function() {
			if (!did_action('template_redirect')) {
				$this->register_assets();
			}
		});
		
		// Setup navigation menu with "main" key.
		register_nav_menu('smartmag-main', esc_html__('Main Navigation', 'bunyad-admin'));
		register_nav_menu('smartmag-mobile', esc_html__('Main Navigation - Mobile Optional', 'bunyad-admin'));
		register_nav_menu('smartmag-footer-links', esc_html__('Footer Links', 'bunyad-admin'));
			
		// Content width is required for for oebmed and Jetpacks.
		global $content_width;
		
		if (!isset($content_width)) {
			$theme_width  = Bunyad::options()->layout_width ? Bunyad::options()->layout_width : 1200;
			$content_width = 788;

			if ($theme_width !== 1200) {
				$content_width = round($content_width * ($theme_width / 1200));
			}
	
		}
		// Fix content_width for full-width posts and for scaling.
		add_filter('wp_head', [$this, 'content_width_fix']);

		// Additional theme supports.
		add_theme_support('html5', [
			'comment-list', 'comment-form', 'search-form', 'gallery', 'caption'
		]);
		
		add_theme_support('custom-background');

		// This is an awkward mix of blocks into widgets.
		if (!Bunyad::options()->widgets_block_editor) {
			remove_theme_support('widgets-block-editor');
		}

		// Setup the init hook
		add_action('init', [$this, 'init']);
		
		/**
		 * Register Sidebars
		 */
		add_action('widgets_init', [$this, 'register_sidebars']);

		/**
		 * Posts related filter
		 */
		// prepare to add body classes in advance
		add_filter('body_class', [$this, 'the_body_class']);
		
		// video format auto-embed
		add_filter('bunyad_featured_video', [$this, 'featured_media_auto_embed']);
		
		// remove hentry microformat, we use schema.org/Article
		add_action('post_class', [$this, 'fix_post_class']);
		
		// add the orig_offset for offset support in blocks
		add_filter('bunyad_block_query_args', [Bunyad::posts(), 'add_query_offset'], 10, 1);
		
		// ajax post content slideshow - add wrapper
		add_filter('the_content', [$this, 'add_post_slideshow_wrap']);
		
		/**
		 * Prevent duplicate posts
		 */
		add_action('bunyad_pre_main_content', function() {
			if (Bunyad::options()->no_home_duplicates) {
			
				if (!is_front_page()) {
					return;
				}

				// Add to removal list on each loop.
				add_action('loop_end', [Bunyad::posts(), '_record_displayed']);
				
				// Exclude on blocks.
				add_filter('bunyad_block_query_args', [Bunyad::posts(), '_exclude_displayed']);
			}
		});

		// Set dynamic widget columns for footer.
		add_filter('dynamic_sidebar_params', [$this, '_set_footer_columns']);

		// Add support for shortcodes in text widget.
		add_filter('widget_text', 'do_shortcode');

		// Disable activation notice for Self-hosted Google Fonts plugin
		add_filter('sgf/admin/active_notice', '__return_false');

		// Read more text.
		Bunyad::posts()->more_text = Bunyad::options()->get_or(
			'post_read_more_text', 
			esc_html__('Read More', 'bunyad')
		);
		Bunyad::posts()->more_html = '';

		/**
		 * Base markup filters.
		 */
		add_filter('bunyad_attribs_main', [$this, '_set_main_classes']);

		// Elementor disable fonts.
		if (Bunyad::options()->google_fonts_disable) {
			add_filter('elementor/frontend/print_google_fonts', '__return_false');
		}

		if (class_exists('\Sphere\Core\Plugin')) {
			Bunyad::register(
				'social-follow', 
				['object' => \Sphere\Core\Plugin::get('social-follow')]
			);
		}
	}
	
	/**
	 * Action callback: Setup that needs to be done at init hook
	 */
	public function init() 
	{		
		// Define options to initialize early on customizer preview. Normally options
		// are only available after wp_loaded in preview.
		add_filter('bunyad_customizer_early_init_options', function() {
			return ['sidebar_titles_style'];
		});
	}
	
	/**
	 * Register image sizes used internally.
	 * 
	 * Only 3 crops generated since v5.
	 */
	public function register_images()
	{
		/**
		 * INFO: 
		 *  3 extra images sizes are generated in total, by default. Rest are just 
		 *  definitions to be used by the theme for internal calculations.
		 */
		$image_sizes = [

			// Generic images used in many locations.
			'bunyad-small'     => ['width' => 150, 'height' => 0, 'crop' => false, 'generate' => true],
			'bunyad-medium'    => ['width' => 450, 'height' => 0, 'crop' => false, 'generate' => true],
			'bunyad-full'      => ['width' => 1200, 'height' => 0, 'crop' => false, 'generate' => true],

			/**
			 * Images definitions below are not images on disk. They're aliases to native
			 * images generated by WordPress.
			 */

			// Alias for native '2048x2048' size, only generated for WordPress older than 5.3 or if removed.
			'bunyad-viewport'  => ['width' => 2048, 'height' => 2048, 'crop' => false, 'generate' => true],

			// Alias for native 'medium_large', only generated if it was removed by a plugin.
			'bunyad-768'       => ['width' => 768, 'height' => 0, 'crop' => false, 'generate' => true],
		];

		/**
		 * Definitions for CSS sizes and internal calculations.
		 * 
		 * Images definitions below are not images on disk. They're definitions either
		 * for internal calculations or CSS pixels.
		 */
		$pixel_definitions = [
		
			// Featured image size for normal and full width.
			'bunyad-main-full' => ['width' => 1200, 'height' => 574],
			'bunyad-main'      => ['width' => 788, 'height' => 515],

			// Unconstrained main image. Will use 'medium_large' or 'large' size usually.
			'bunyad-main-uc'      => ['width' => 788, 'height' => 0],
		
			// Grid Posts.
			'bunyad-grid' => ['width' => 377, 'height' => 212],

			// List type block and listing.
			'bunyad-list' => ['width' => 300, 'height' => 200],

			'bunyad-thumb' => ['width' => 110, 'height' => 76],

			// Featured Grids - simply for width definitions.
			'bunyad-feat-grid-lg'     => ['width' => 585, 'height' => 0],
			'bunyad-feat-grid-sm'     => ['width' => 292, 'height' => 0],
			'bunyad-feat-grid-lg-vw'  => ['width' => 960, 'height' => 0],
			'bunyad-feat-grid-sm-vw'  => ['width' => 500, 'height' => 0],
		
			// Legacy: Classic Slider
			'bunyad-classic-slider'    => ['width' => 782, 'height' => 374],
			'bunyad-classic-slider-md' => ['width' => 391, 'height' => 206],
			'bunyad-classic-slider-sm' => ['width' => 187, 'height' => 153],

			// Default WordPress thumbnail. 
			// 'post-thumbnail' => ['width'=> 110, 'height' => 76],
		
			// Grid Overlay listing image (categories or Blog block). 
			'bunyad-overlay' => ['width' => 377, 'height' => 283],
		];

		$image_sizes += $pixel_definitions;

		// Register the 3 image sizes with WordPress API.
		$image_sizes = apply_filters('bunyad_image_sizes', $image_sizes);
		foreach ($image_sizes as $key => $size) {

			// For default thumbnail, just redefining size.
			if ($key === 'post-thumbnail') {
				set_post_thumbnail_size($size['width'], $size['height'], true);
				continue;
			}
			
			// Not marked to be generated, skip.
			if (empty($size['generate'])) {
				continue;
			}
			
			// Set default crop to true
			$size['crop'] = (!isset($size['crop']) ? true : $size['crop']);

			add_image_size($key, $size['width'], $size['height'], $size['crop']);	
		}
	}

	/**
	 * Register and enqueue theme CSS and JS files
	 */
	public function register_assets()
	{
		// Theme version
		$version = Bunyad::options()->get_config('theme_version');

		if (!is_admin()) {

			// Theme version
			$version = Bunyad::options()->get_config('theme_version');
			$core_depends = [];

			/**
			 * Elementor as a dependency, just in case something else uses 'smartmag-core' 
			 * as a dependency which would cause troubles with specificity as theme css
			 * will move before Elementor.
			 * 
			 * Note: Normally not needed as we use we use higher hook priority than Elementor.
			 * Also not needed if plugins/child themes use the 'bunyad_register_assets' hook.
			 */
			if (did_action('elementor/loaded')) {
				if (wp_style_is('elementor-frontend', 'enqueued')) {
					$core_depends[] = 'elementor-frontend';
				}

				if (wp_style_is('elementor-global', 'enqueued')) {
					$core_depends[] = 'elementor-global';
				}
			}

			// Add core CSS
			if (apply_filters('bunyad_enqueue_core_css', true)) {
				$stylesheet = get_parent_theme_file_uri(
					is_rtl() ? 'css/rtl/rtl.css': 'style.css'
				);
				wp_enqueue_style('smartmag-core', $stylesheet, $core_depends, $version);
			}
			
			// Add google fonts.
			$style = $this->get_style();
			if (!empty($style['font_args'])) {
				wp_enqueue_style('smartmag-fonts', $this->get_fonts_enqueue(), [], null);
			}

			// Add Typekit Kit.
			if (Bunyad::options()->typekit_id) {
				wp_enqueue_style(
					'smartmag-typekit', 
					esc_url('https://use.typekit.net/' . Bunyad::options()->typekit_id . '.css'),
					[],
					$version
				);
			}

			// Add lightbox - always added due to being used in search as well. However, no JS for AMP.
			if (!Bunyad::amp()->active()) {
				wp_enqueue_script(
					'magnific-popup', 
					get_template_directory_uri() . '/js/jquery.mfp-lightbox.js', 
					['jquery'], 
					$version, 
					true
				);
			}
						
			// Modified MFP: Our lightbox CSS - needed for search popup in AMP as well.
			wp_enqueue_style(
				'smartmag-magnific-popup', 
				get_template_directory_uri() . '/css/lightbox.css', 
				['smartmag-core'],
				$version
			);
			
			// bbPress styles.
			if (class_exists('bbPress')) {
				wp_enqueue_style('smartmag-bbpress', get_template_directory_uri() . '/css/' . (is_rtl() ? 'rtl/' : '') . 'bbpress-ext.css', [], $version);
			}			
			
			// FontAwesome 4 only if in legacy mode and if not already enqueued by Elementor.
			$legacy = Bunyad::options()->legacy_mode || Bunyad::options()->fontawesome4;
			if ($legacy && !wp_style_is('font-awesome-4-shim', 'enqueued')) {
				wp_enqueue_style(
					'font-awesome4', 
					get_template_directory_uri() . '/css/fontawesome/css/font-awesome.min.css',
					[], 
					$version
				);
			}

			// Our own theme icons.
			wp_enqueue_style(
				'smartmag-icons', 
				get_template_directory_uri() . '/css/icons/icons.css', 
				[], 
				$version
			);

			// Sticky sidebar where enabled.
			wp_enqueue_script('theia-sticky-sidebar',
				get_template_directory_uri() . '/js/jquery.sticky-sidebar.js',
				['jquery'], 
				$version,
				true
			);

			// Register Floating share.
			wp_register_script(
				'smartmag-float-share',
				get_template_directory_uri() . '/js/float-share.js', 
				['smartmag-theme'],
				$version,
				true
			);

			if (is_single() && Bunyad::options()->single_share_float) {
				wp_enqueue_script('smartmag-float-share');
			}

			// Register 3rd Party: Modified slick.
			wp_register_script(
				'smartmag-slick', 
				get_template_directory_uri() . '/js/jquery.slick.js', 
				['jquery'], 
				$version,
				true
			);

			// Register 3rd Party: MicroModal
			wp_register_script(
				'micro-modal',
				get_theme_file_uri('js/micro-modal.js'), 
				[],
				$version,
				true
			);

			/**
			 * Classic Slider and flexslider.
			 */
			wp_register_script(
				'smartmag-flex-slider', 
				get_template_directory_uri() . '/js/' . (is_rtl() ? 'rtl-' : '') . 'jquery.flexslider-min.js', 
				['jquery'],
				$version,
				true
			);

			wp_register_style(
				'smartmag-flex-slider', 
				get_template_directory_uri() . '/css/flexslider.css', 
				['smartmag-core'],
				$version
			);

			wp_register_style(
				'smartmag-classic-slider', 
				get_template_directory_uri() . '/css/' . (is_rtl() ? 'rtl/' : '') . 'classic-slider.css', 
				['smartmag-flex-slider'],
				$version
			);

	
			// Core Theme JS. Registered at end to ensure others are loaded before in order.
			// Note: some like 'smartmag-slick' may still enqueue later.
			wp_enqueue_script(
				'smartmag-theme', 
				get_template_directory_uri() . '/js/theme.js', 
				['jquery'], 
				$version, 
				true
			);

			wp_localize_script('smartmag-theme', 'Bunyad', ['ajaxurl' => admin_url('admin-ajax.php')]);
			
			// Pre-defined scheme / skin CSS - add it below others.
			if (!empty($style['css'])) {
				
				// Enqueue with WooCommerce dependency if it exists.
				wp_enqueue_style(
					'smartmag-skin',
					get_template_directory_uri() . '/css/' . $style['css'] . '.css',
					[(function_exists('is_woocommerce') ? 'smartmag-woocommerce' : 'smartmag-core')],
					$version
				);
			}

			do_action('bunyad_register_assets');
		}
	}
	
	/**
	 * Setup the sidebars
	 */
	public function register_sidebars()
	{
		$main_titles  = $this->get_widget_heads('sidebar');
		$widget_class = 'widget';

		// Register main sidebar.
		register_sidebar([
			'name'          => esc_html__('Main Sidebar', 'bunyad-admin'),
			'id'            => 'smartmag-primary',
			'description'   => esc_html__('Widgets in this area will be shown in the default sidebar.', 'bunyad-admin'),
			'before_title'  => $main_titles['before'],
			'after_title'   => $main_titles['after'],
			'before_widget' => '<div id="%1$s" class="' . esc_attr($widget_class) . ' %2$s">',
			'after_widget'  => "</div>",
		]);

		if (function_exists('is_woocommerce')) {
			// Shop sidebar.
			register_sidebar([
				'name'          => esc_html__('Shop Sidebar', 'bunyad-admin'),
				'id'            => 'smartmag-shop',
				'description'   => esc_html__('Widgets in this area will be shown in the default sidebar on Shop page.', 'bunyad-admin'),
				'before_title'  => $main_titles['before'],
				'after_title'   => $main_titles['after'],
				'before_widget' => '<div id="%1$s" class="' . esc_attr($widget_class) . ' %2$s">',
				'after_widget'  => "</div>",
			]);
		}

		if (class_exists('bbpress')) {
			// bbPress sidebar.
			register_sidebar([
				'name'          => esc_html__('bbPress Sidebar', 'bunyad-admin'),
				'id'            => 'smartmag-bbpress',
				'description'   => esc_html__('Widgets for default sidebar on bbPress/Forums page.', 'bunyad-admin'),
				'before_title'  => $main_titles['before'],
				'after_title'   => $main_titles['after'],
				'before_widget' => '<div id="%1$s" class="' . esc_attr($widget_class) . ' %2$s">',
				'after_widget'  => "</div>",
			]);
		}

		// Off Canvas Menu.
		$offcanvas_titles  = $this->get_widget_heads('offcanvas');
		register_sidebar([
			'name'          => esc_html__('Off-Canvas Widgets', 'bunyad-admin'),
			'id'            => 'smartmag-off-canvas',
			'description'   => esc_html__('Widgets below navigation in off-canvas/mobile menu.', 'bunyad-admin'),
			'before_title'  => $offcanvas_titles['before'],
			'after_title'   => $offcanvas_titles['after'],
			'before_widget' => '<div id="%1$s" class="' . esc_attr($widget_class) . ' %2$s">',
			'after_widget'  => "</div>",
		]);
		
		
		// Footer Widgets. 
		$footer_titles  = $this->get_widget_heads('footer');
		register_sidebar([
			'name'          => esc_html__('Footer Widgets', 'bunyad-admin'),

			// Not-prefixed: Due to legacy compatibilty.
			'id'            => 'main-footer',
			'description'   => esc_html__('Widgets in this area will be shown in the footer.', 'bunyad-admin'),
			'before_title'  => $footer_titles['before'],
			'after_title'   => $footer_titles['after'],
			'before_widget' => '<div class="widget column %2$s">',
			'after_widget'  => '</div>'
		]);

		/**
		 * Elementor widgets.
		 */
		add_action('elementor/widgets/wordpress/widget_args', function($args, $object) use($main_titles) {

			$args = array_replace($args, [
				'before_title'  => $main_titles['before'],
				'after_title'   => $main_titles['after'],
			]);

			// Add before and after wrappers that are needed by most core and custom widgets.
			if (is_callable([$object, 'get_widget_instance']) && ($widget = $object->get_widget_instance())) {
				$class = $widget->widget_options['classname'];
				if (!is_string($class)) {
					$class = get_class($class);
				}

				$args['before_widget'] = sprintf('<div class="widget %2$s">', '', $class);
				$args['after_widget']  = '</div>';
			}

			return $args;
		}, 10, 2);
	}

	public function get_widget_heads($type = '')
	{
		$title_class = 'block-head block-head-ac';
		$get_title_class = function($option) {

			$style = Bunyad::options()->get($option);
			if (!$style) {
				return '';
			}

			$classes = \Bunyad\Blocks\Heading::get_classes(
				$style,
				[
					'align' => Bunyad::options()->get('bhead_align_' . $style)
				]
			);

			$classes[] = 'has-style';
			return ' ' . join(' ', $classes);
		};

		switch ($type) {
			case 'sidebar': 
				$title_class .= $get_title_class('sidebar_titles_style');
				break;

			case 'footer':
				$title_class .= $get_title_class('footer_head_style');
				break;

			case 'offcanvas':
				$title_class .= ' block-head-b';

				break;
		}

		$before_title = '<div class="widget-title '. esc_attr($title_class) .'"><h5 class="heading">';
		$after_title  = '</h5></div>';

		return [
			'class'  => $title_class,
			'before' => $before_title,
			'after'  => $after_title,
		];
	}
	
	/**
	 * Load admin textdomain
	 * 
	 * WordPress's default theme textdomain can get too cluttered with translations. Our Admin
	 * translations are split up from the main translations.
	 * 
	 * @see load_theme_textdomain()
	 */
	public function load_admin_textdomain($domain, $path)
	{
		/**
		 * Filter a theme's locale.
		 * 
		 * @param string $locale The theme's current locale.
		 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
		 */
		$locale = apply_filters('theme_locale', determine_locale(), $domain);
	
		// Load the textdomain according to the theme
		$mofile = untrailingslashit($path) . "/{$domain}-{$locale}.mo";
		if ($loaded = load_textdomain($domain, $mofile)) {
			return $loaded;
		}
	
		// Otherwise, load from the languages directory
		$mofile = WP_LANG_DIR . "/themes/{$domain}-{$locale}.mo";
		return load_textdomain($domain, $mofile);
	}

	/**
	 * Get a skin settings
	 * 
	 * @param string $style
	 */
	public function get_style($style = '')
	{
		if (empty($style)) {
			$style = Bunyad::options()->predefined_style;
		}

		if (empty($style)) {
			$style = 'default';
		}
		
		$styles = [
			'default' => [
				'fonts' => [
					'text' => ['Public Sans', '400,400i,500,600,700']
				],
				'css' => ''
			],
			
			'trendy' => [
				'fonts' => [
					'text'        => ['Libre Franklin', '400,400i,500,600'],
					'secondary'   => ['Lato', '400,700,900'],
					'post_titles' => ['Hind', '400,500,600']
				]
			],

			'thezine' => [
				'fonts' => [
					'text'        => ['Roboto', '400,400i,500,700'],
				]
			],

			'classic' => [
				'fonts' => [
					'text'        => ['Open Sans', '400,400i,600,700'],
					'headings'    => ['Roboto Slab', '400,500']
				]
			],
		];
		
		$the_style = $styles[$style];

		// The skin CSS file - not added for default.
		if (!isset($the_style['css'])) {
			$the_style['css'] = 'skin-' . $style;
		}

		// Fonts disabled, nothing more to do. Return.
		if (Bunyad::options()->google_fonts_disable) {
			return $the_style;
		}

		// Process fonts for the google fonts enqueue.
		if (isset($the_style['fonts'])) {
			$fonts = $the_style['fonts'];

			// Remove fonts that have been changed in settings.
			foreach ($fonts as $key => $font) {
				if (!empty(Bunyad::options()->get('css_font_' . $key))) {
					unset($fonts[$key]);
				}
			}

			$fonts = array_reduce($fonts, function($acc, $item) {	
				list($family, $weights) = $item;
				$acc[] = $family . ':' . $weights;
				return $acc;
			}, []);

			if ($fonts) {
				$the_style['font_args'] = [
					'family' => implode('|', $fonts)
				];
			}
		}
		
		return $the_style;
	}
	
	/**
	 * Get Google Fonts Embed URL
	 * 
	 * @return string URL for enqueue
	 */
	public function get_fonts_enqueue()
	{
		// Add google fonts
		$style = $this->get_style();
		if (empty($style['font_args'])) {
			return '';
		}

		$args  = $style['font_args'];
		
		if (Bunyad::options()->google_fonts_charset) {
			$args['subset'] = implode(',', array_keys(array_filter(Bunyad::options()->google_fonts_charset)));
		}

		if (Bunyad::options()->font_display) {
			$args['display'] = Bunyad::options()->font_display;
		}

		return add_query_arg(
			urlencode_deep($args), 
			'https://fonts.googleapis.com/css'
		);
	}
	
	
	/**
	 * Filter callback: Add classes to body.
	 */
	public function the_body_class($classes)
	{
		
		// Add body class for pages with slider
		// if (is_page() && Bunyad::posts()->meta('featured_slider')) {
		// 	$classes[] = 'has-featured';
		// }
		
		if (is_single()) {
			$category = Bunyad::blocks()->get_primary_cat();
			if (is_object($category)) {
				$classes[] = 'post-cat-' . $category->term_id;
			}
		}

		// Denotes if lightbox is active.
		if (Bunyad::options()->enable_lightbox) {
			$classes[] = 'has-lb';

			if (Bunyad::options()->enable_lightbox_mobile) {
				$classes[] = 'has-lb-sm';
			}
		}

		// Add body classes for effects
		if (Bunyad::options()->image_effects) {
			$classes[] = 'img-effects';
		}
		
		// Sidebar separator. 
		if (Bunyad::options()->sidebar_separator) {
			$classes[] = 'has-sb-sep';
		}

		$classes[] = 'layout-' . Bunyad::options()->layout_type;

		return $classes;
	}
	
	/**
	 * Filter callback: Auto-embed video using a link.
	 * 
	 * @param string $content
	 */
	public function featured_media_auto_embed($content) 
	{
		global $wp_embed;
		
		if (!is_object($wp_embed)) {
			return $content;
		}
		
		// Also supports a shortcode instead.
		return do_shortcode($wp_embed->autoembed($content));
	}
	
	/**
	 * Filter callback: Remove unnecessary classes
	 */
	public function fix_post_class($classes = [])
	{
		// Remove outdated hentry.
		$classes = array_diff($classes, ['hentry']);
		
		return $classes;
	}

	/**
	 * Adjust content width for full-width posts
	 */
	public function content_width_fix()
	{
		global $content_width;
	
		if (Bunyad::core()->get_sidebar() === 'none') {
			$content_width = Bunyad::options()->layout_width;
		}
	}
	
	/**
	 * Filter callback: Add a wrapper to the content slideshow wrapper
	 * 
	 * @param string $content
	 */
	public function add_post_slideshow_wrap($content)
	{
		if (is_single() && Bunyad::posts()->meta('content_slider') === 'ajax') {
			return '<div class="content-page">' . $content . '</div>';
		}
		
		return $content;
	}

	/**
	 * Filter callback: Set column for widgets where dynamic widths are set
	 * 
	 * @access private
	 * @see dynamic_sidebar()
	 * @param array $params
	 */
	public function _set_footer_columns($params)
	{
		static $count = 0, $columns, $last_id;
		
		if (empty($columns)) {
			$cols = Bunyad::options()->footer_upper_cols;
			if ($cols === 'custom') {
				$cols = Bunyad::options()->footer_upper_cols_custom;
			}

			$columns = [
				'main-footer' => $this->parse_column_setting($cols)
			];
		}
		
		/**
		 * Set correct column class for each widget in footer
		 */
		
		$id = $params[0]['id'];
		
		// Reset counter if last sidebar id was different than current
		if ($last_id !== $id) {
			$count = 0;
		}
		
		// Only apply to main-footer sidebar.
		if (in_array($params[0]['id'], ['main-footer'])) {

			// If have gone beyond known columns, reset.
			if ($count+1 > count($columns[$id])) {
				$count = 0;
			}
			
			if (isset($columns[$id][$count])) {
				$params[0]['before_widget'] = str_replace('column', $columns[$id][$count], $params[0]['before_widget']);
			}
			
			$count++;	
		}
		
		$last_id = $id;
	
		return $params;	
	}	
	
	/**
	 * Parse columns of format 1/2+1/4+1/4 into an array of col-X.
	 * 
	 * @param   string  $cols
	 * @return  array   Example: array('col-6', 'col-3', ...)
	 */
	public function parse_column_setting($cols)
	{
		$columns = [];

		// Pre-parsed map to save computation time
		$map = [
			'0.08' => 'col-1', 
			'0.17' => 'col-2', 
			'0.20' => 'col-2-4', 
			'0.25' => 'col-3', 
			'0.33' => 'col-4', 
			'0.42' => 'col-5', 
			'0.50' => 'col-6', 
			'0.58' => 'col-7', 
			'0.67' => 'col-8', 
			'0.75' => 'col-9', 
			'0.83' => 'col-10', 
			'0.92' => 'col-11', 
			'1.00' => 'col-12'
		];

		// Handle equal columns.
		if (is_numeric($cols)) {
			$cols    = intval($cols);
			$percent = number_format(1 / $cols, 2);
			$class   = isset($map[$percent]) ? $map[$percent] : 'col-4';

			return array_fill(0, $cols, $class);
		}

		// Manually specified columns.
		foreach (explode('+', $cols) as $format) {
			$format = trim($format);
			$col    = explode('/', $format);
			$width  = null;

			if (!empty($col[0]) && !empty($col[1])) {
				$width = number_format($col[0] / $col[1], 2);
			}
			else if (is_numeric($format)) {
				$width = $format;
			}

			if ($width && array_key_exists($width, $map)) {
				array_push($columns, $map[$width]);
			}
		}
		
		return $columns;
	}

	/**
	 * Filter Callback: Set markup main wrap's classes.
	 * 
	 * @access private
	 * @param array $attribs
	 * @return array
	 */
	public function _set_main_classes($attribs)
	{
		$attribs['class'] = !isset($attribs['class']) ? [] : (array) $attribs['class'];
		$attribs['class'][] = 'main ts-contain cf';

		array_push(
			$attribs['class'], 
			Bunyad::core()->get_sidebar() == 'right' ? 'right-sidebar' : 'no-sidebar'
		);

		return $attribs;
	}

	/**
	 * Get cache logo data by provided attachment URL.
	 * 
	 * @return array
	 */
	public function get_logo_data($logo_url)
	{
		$logo_data = [
			'src' => $logo_url,
		];

		// This transient is cleared on customizer save, in customizer.php.
		$cache_key = 'bunyad_logo_' . md5($logo_url);
		$dimensions = get_transient($cache_key);
		
		if (!$dimensions) {
			$dimensions = wp_get_attachment_image_src(
				attachment_url_to_postid($logo_url),
				'full'
			);

			set_transient($cache_key, $dimensions, time() + WEEK_IN_SECONDS);
		}

		if (is_array($dimensions)) {
			$logo_data += [
				'width'  => $dimensions[1],
				'height' => $dimensions[2],
			];
		}

		return $logo_data;
	}

	/**
	 * Output footer copyright.
	 */
	public function the_copyright()
	{
		$replace = [
			'{year}' => date_i18n('Y'),
			'{copy}' => '&copy;',
			'{url}'  => home_url(),
		];

		$copyright = str_replace(
			array_keys($replace),
			array_values($replace),
			Bunyad::options()->footer_copyright
		);

		echo do_shortcode(wp_kses_post($copyright));
	}
}