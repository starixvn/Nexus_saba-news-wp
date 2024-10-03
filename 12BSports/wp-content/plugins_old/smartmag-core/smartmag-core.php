<?php
/**
 * SmartMag Core
 *
 * Plugin Name:       SmartMag Core
 * Description:       Elements and core functionality for SmartMag Theme.
 * Version:           1.4.7
 * Author:            ThemeSphere
 * Author URI:        https://theme-sphere.com
 * License:           ThemeForest Split
 * License URI:       https://themeforest.net/licenses/standard
 * Text Domain:       smartmag
 * Domain Path:       /languages
 * Requires PHP:      7.1
 */

defined('WPINC') || exit;

class SmartMag_Core 
{
	const VERSION    = '1.4.7';

	// Due to legacy reasons, it's named smartmag without dash.
	const THEME_SLUG = 'smartmag';

	protected static $instance;

	/**
	 * Path to plugin folder, trailing slashed.
	 */
	public $path;
	public $path_url;

	/**
	 * Flag to indicat plugin successfuly ran init. This confirms no conflicts.
	 */
	public $did_init = false;

	/**
	 * Whether the correct accompanying theme exists or implementations are done.
	 *
	 * @var boolean
	 */
	public $theme_supports = [];

	public function __construct()
	{
		$this->path = plugin_dir_path(__FILE__);

		// URL for the plugin dir
		$this->path_url = plugin_dir_url(__FILE__);

		/**
		 * Register autoloader. Usually uses the loader from theme if present.
		 */
		if (!class_exists('\Bunyad\Lib\Loader', false)) {
			require_once $this->path . 'lib/loader.php';
		}
		
		$path       = $this->path;
		$namespaces = [
			'SmartMag\\'       => $path . 'inc', 
			'Bunyad\Blocks\\' => [
				'search_reverse' => true,
				'paths'          => $path . 'blocks',
			],
			'Bunyad\Elementor\\' => $path . 'inc/elementor',
			'Bunyad\Widgets\\'   => $path . 'inc/widgets',
			'Bunyad\Studio\\'    => $path . 'inc/studio',
		];

		$loader = new \Bunyad\Lib\Loader($namespaces);

	}
	
	/**
	 * Setup to be hooked after setup theme.
	 */
	public function init()
	{		
		$this->did_init = true;
		$lib_path = $this->path . 'lib/';
		
		/**
		 * When one of our themes isn't active, use shims
		 */
		if (!class_exists('Bunyad_Core')) {
			require_once $this->path . 'lib/bunyad.php';
			require_once $this->path . 'inc/bunyad.php';

			require_once $this->path . 'lib/util.php';
			require_once $this->path . 'blocks/helpers.php';

			// Set path to local as theme isn't active
			Bunyad::$fallback_path = $lib_path;

			Bunyad::options()->set_config([
				'theme_prefix' => self::THEME_SLUG,
				'meta_prefix'  => '_bunyad'
			]);
		}
		else {

			// If we're here, there's a ThemeSphere theme active. All ThemeSphere themes have
			// their own core plugins. Theme's own core plugin should be used instead.
			if (Bunyad::options()->get_config('theme_name') !== self::THEME_SLUG) {
				return;
			}

			$this->theme_supports = ['blocks' => true];
		}

		// Outdated Bunyad from an old theme? Cannot continue.
		if (!property_exists('Bunyad', 'fallback_path')) {
			return;
		}

		// Set local fallback for some components not packaged with theme.
		Bunyad::$fallback_path = $lib_path;

		/**
		 * Setup filters and data
		 */

		// Elementor specific
		if (class_exists('\Elementor\Plugin') || did_action('elementor/loaded')) {
			$elementor = new \Bunyad\Elementor\Module;
			$elementor->register_hooks();

			// And the studio.
			new \Bunyad\Studio\Module;
		}

		// Admin related actions
		add_action('admin_init', [$this, 'register_metaboxes']);

		// User profile fields
		add_filter('user_contactmethods', [$this, 'add_profile_fields']);

		// Register assets
		add_action('admin_enqueue_scripts', [$this, 'admin_assets']);

		// Setup blocks
		$this->setup_blocks();

		// Setup widgets - hook will be handled by Bunyad_Widgets.
		$this->setup_widgets();

		// Performance optimizations.
		require_once $this->path . 'inc/optimize.php';

		// Social Share and Follow.
		require_once $this->path . 'inc/social-share.php';

		// Classic Editor features
		if (is_admin()) {
			require_once $this->path . 'inc/editor.php';
		}

		// Init menu helper classes
		Bunyad::menus();
		add_filter('bunyad_custom_menu_fields', [$this, 'custom_menu_fields']);

		// Init reviews.
		Bunyad::register('reviews', [
			'object' => new SmartMag\Reviews\Module
		]);

		// Translation: To be loaded via theme. Uncomment below to do a local translate.
		// load_plugin_textdomain(
		// 	'bunyad',
		// 	false,
		// 	basename($this->path) . '/languages'
		// );

		/**
		 * Old version migration.
		 */
		// DEBUG: update_option('smartmag_convert_from_v3', 1);
		if (is_admin() && get_option('smartmag_convert_from_v3')) {
			new SmartMag\ConvertV5\ConvertV5;
		}
	}

	/**
	 * Register our custom metaboxes. Should run admin side only via admin_init.
	 * 
	 * Note: You may use filter bunyad_meta_boxes from lib/meta-boxes.php file to modify or extend these
	 * in a Child Theme or plugin.
	 * 
	 * Example, to enable post options metabox on CPT my-cpt:
	 * 
	 *     add_filter('bunyad_meta_boxes', function($meta) {
	 *         array_push($meta['post-options']['page'], 'my-cpt');
	 *         return $meta;
	 *     });
	 */
	public function register_metaboxes()
	{
		// Set active metaboxes
		$meta_boxes = (array) Bunyad::options()->get_config('meta_boxes');
		$meta_boxes = array_merge($meta_boxes, [
			// Enabled metaboxes and prefs - id is prefixed with _bunyad_ in init() method of lib/admin/meta-boxes.php
			'post-options' => [
				'id'       => 'post-options', 
				'title'    => esc_html_x('Post Options', 'Admin: Meta', 'bunyad-admin'), 
				'priority' => 'high', 
				'page'     => ['post'],
				'form'     => $this->path . 'metaboxes/post-options.php',
				'options'  => $this->path . 'metaboxes/options/post.php',
			],

			'page-options' => [
				'id'       => 'page-options', 
				'title'    => esc_html_x('Page Options', 'Admin: Meta', 'bunyad-admin'),
				'priority' => 'high', 
				'page'     => ['page'],
				'form'     => $this->path . 'metaboxes/page-options.php',
				'options'  => $this->path . 'metaboxes/options/page.php',
			],
		]);
		
		Bunyad::options()->set_config('meta_boxes', $meta_boxes);

		Bunyad::load_file('admin/meta-base');
		Bunyad::factory('admin/meta-boxes');

		/**
		 * Setup term meta custom fields.
		 */
		$meta = Bunyad::factory('admin/meta-terms', true);
		$meta->taxonomy     = 'category';
		$meta->options_file = $this->path . 'metaboxes/terms/category-options.php';
		$meta->form_file    = $this->path . 'metaboxes/terms/category-form.php';
		
		$meta->init();
	}

	/**
	 * Register assets for admin context only.
	 */
	public function admin_assets($hook)
	{
		wp_enqueue_style(
			'smartmag-admin', 
			$this->path_url . 'css/admin/common.css', 
			[], 
			self::VERSION
		);
		
		wp_register_script(
			'bunyad-lib-options', 
			$this->path_url . 'lib/js/admin/options.js',
			['jquery'],
			self::VERSION
		);

		// We need this globally as widgets may be used on widget screen, customizer, or 
		// in a pagebuilder like Elementor.
		wp_enqueue_script(
			'bunyad-widgets', 
			$this->path_url . 'js/widgets.js',
			['jquery', 'wp-api-request'],
			self::VERSION
		);

		// Enqueue selectize only when theme is active.
		if (Bunyad::get('theme')) {
			wp_enqueue_script(
				'bunyad-customize-selectbox', 
				get_template_directory_uri() . '/inc/core/assets/js/selectize.js',
				['jquery'],
				Bunyad::options()->get_config('theme_version')
			);

			wp_enqueue_style(
				'bunyad-customize-selectbox', 
				get_template_directory_uri() . '/inc/core/assets/css/selectize.css', 
				[], 
				Bunyad::options()->get_config('theme_version')
			);
		}
	}

	/**
	 * Setup Widgets
	 */
	public function setup_widgets()
	{
		/** @var \Bunyad_Widgets $widgets  */
		$widgets = Bunyad::get('widgets');

		if (!is_object($widgets)) {
			return;
		}

		// Configure the object.
		$widgets->path   = $this->path;
		$widgets->prefix = 'SmartMag_Widgets_';
		$widgets->active = [
			'about',
			'tabbed-recent',
			'tabber',
			'social-follow',
			'bbp-login',
			'latest-reviews',
			'flickr'
		];

		// Only add these for legacy.
		if (Bunyad::options()->legacy_mode) {
			$widgets->active = array_merge($widgets->active, [
				'blocks',
				'ads',
				'latest-posts',
				'popular-posts',
			]);
		}

		/**
		 * Block Widgets.
		 */
		add_action('widgets_init', function() {
			// Load the class map to prevent unnecessary autoload to save resources.
			include_once \SmartMag_Core::instance()->path . 'inc/widgets/classmap-widgets.php';

			// Block widgets to load.
			$blocks = [
				'Loops\Grid', 
				'Loops\Overlay', 
				'Loops\PostsSmall', 
				'Loops\Highlights', 
				'Newsletter',
				'Codes'
			];
			
			foreach ($blocks as $block) {	
				$class = 'Bunyad\Widgets\\' . $block . '_Block';
				register_widget($class);
			}
		});
	}

	/**
	 * Setup blocks and their shortcodes.
	 */
	public function setup_blocks()
	{
		require_once $this->path . 'inc/shortcodes.php';
		
		/**
		 * Register all the blocks
		 */
		$blocks = [
			'ts_loop_feat_grid'   => 'Loops\FeatGrid', 
			'ts_loop_grid'        => 'Loops\Grid', 
			'ts_loop_large'       => 'Loops\Large', 
			'ts_loop_overlay'     => 'Loops\Overlay', 
			'ts_loop_posts_list'  => 'Loops\PostsList', 
			'ts_loop_posts_small' => 'Loops\PostsSmall',
			'ts_loop_news_focus'  => 'Loops\NewsFocus', 
			'ts_loop_focus_grid'  => 'Loops\FocusGrid', 
			'ts_loop_highlights'  => 'Loops\Highlights', 
			'ts_block_newsletter' => 'Newsletter',
			'ts_block_heading'    => 'Heading',
			'ts_breadcrumbs'      => 'Breadcrumbs',
			'ts_social_icons'     => 'SocialIcons',

			// Blocks without shortcodes.
			1  => 'Codes',
		];

		// Register with elementor.
		add_filter('bunyad_elementor_widgets', function($value) use ($blocks) {
			return array_merge((array) $value, $blocks);
		});

		// Register shortcodes.
		foreach ($blocks as $id => $block) {

			if (!is_string($id)) {
				continue;
			}

			Bunyad::get('shortcodes')->add([
				$id => [
					'render'       => 'block',
					'block_class'  => $block
				]
			]);
		}

		//
		// Some necessary legacy shortcodes.
		//
		if (Bunyad::options()->legacy_mode) {
			Bunyad::get('shortcodes')->add([
				'latest_gallery' => [
					'render'      => 'block',
					'map_attribs' => [
						'number'  => 'posts',
						'format'  => 'post_formats',
						'tax_tag' => 'tags',
						'title'   => 'heading',
					],
					'alias'   => 'ts_loop_grid',
					'attribs' => [
						'post_formats'       => 'gallery',
						// Intentionally '0' as false could default to ''.
						'cat_labels'         => '0',
						'posts'              => 5, 
						'title'              => '', 
						'cat'                => '',
						'type'               => '', 
						'tax_tag'            => '', 
						'offset'             => '', 
						'post_type'          => '',
						'show_content'       => false,
						'excerpts'           => false,
						'meta_items_default' => false,
						'carousel'           => true,
						'show_post_formats'  => false,
						'carousel_slides'    => 1,
						'carousel_dots'      => false,
						'meta_below'         => [],
						'meta_above'         => [],
					]
				]
			]);
		}

		/**
		 * Other shortcodes.
		 */
		Bunyad::get('shortcodes')->add([
			'main-color' => [
				'render' => function($atts) {
					return '<span class="main-color">'. esc_html($atts['text']) .'</span>';
				},
				'attribs' => ['text' => '']
			],
		]);
	}

	/**
	 * Filter callback: Custom menu fields.
	 *
	 * Required for both back-end and front-end.
	 *
	 * @see Bunyad_Menus::init()
	 */
	public function custom_menu_fields($fields)
	{
		$fields = [
			'mega_menu' => [
				'label'   => esc_html__('Mega Menu', 'bunyad-admin'),
				'element' => [
					'type'    => 'select',
					'class'   => 'widefat',
					'options' => [
						0              => esc_html__('Disabled', 'bunyad-admin'),
						'category-a'   => esc_html__('Category Modern', 'bunyad-admin'),
						'category'     => esc_html__('Category Legacy: (Subcats, Featured & Recent)', 'bunyad-admin'),
						'normal'       => esc_html__('Mega Menu for Links', 'bunyad-admin')
					]
				],
				'parent_only' => true,
			]
		];
	
		return $fields;
	}

	/**
	 * Filter callback: Add theme-specific profile fields
	 */
	public function add_profile_fields($fields)
	{
		$fields = array_merge((array) $fields, [
			'bunyad_facebook'  => esc_html__('Facebook URL', 'bunyad-admin'),	
			'bunyad_twitter'   => esc_html__('X (Twitter) URL', 'bunyad-admin'),
			'bunyad_instagram' => esc_html__('Instagram URL', 'bunyad-admin'),
			'bunyad_pinterest' => esc_html__('Pinterest URL', 'bunyad-admin'),
			'bunyad_bloglovin' => esc_html__('BlogLovin URL', 'bunyad-admin'),
			'bunyad_dribble'   => esc_html__('Dribble URL', 'bunyad-admin'),
			'bunyad_linkedin'  => esc_html__('LinkedIn URL', 'bunyad-admin'),
			'bunyad_tumblr'    => esc_html__('Tumblr URL', 'bunyad-admin'),
		]);
		
		return $fields;
	}

	/**
	 * Singleton instance
	 * 
	 * @return SmartMag_Core
	 */
	public static function instance() 
	{
		if (!isset(self::$instance)) {
			self::$instance = new self;
		}
		
		return self::$instance;
	}
}

/**
 * Add notice and bail if there's an incompatible plugin active.
 * 
 * Note: Needed for outdated libs in ContentBerg Core. Not required for others, but 
 * good practice to keep them out for conflicting features.
 */
add_action('after_setup_theme', function() {
	$core_plugins = [
		'contentberg-core' => 'ContentBerg_Core',
		'cheerup-core'     => 'CheerUp_Core',
		'bunyad-widgets'   => 'Bunyad_Widgets',
	];

	$conflict = false;
	foreach ($core_plugins as $plugin => $class) {	

		// Check but don't auto-load this class.
		if (class_exists($class, false)) {
		
			add_action('admin_notices', function() use ($plugin) {

				// Path to plugin/plugin.php file.
				$plugin_file = $plugin . '/' . $plugin . '.php';

				include_once ABSPATH . 'wp-admin/includes/plugin.php';
				$plugin_full_path = WP_PLUGIN_DIR . '/' . $plugin_file;

				if (file_exists($plugin_full_path)) {
					$plugin_data = get_plugin_data($plugin_full_path);
				}
				else {
					$plugin_data = ['Name' => $plugin];
				}

				$message = sprintf(
					'Plugin %1$s is incompatible with current theme\'s Core Plugin. Please deactivate.',
					'<strong>' . esc_html($plugin_data['Name']) . '</strong>'
				);

				printf(
					'<div class="notice notice-error"><h3>Important:</h3><p>%1$s</p></div>',
					wp_kses_post($message)
				);
			});

			$conflict = true;
		}
	}

	if ($conflict) {
		return;
	}

	/**
	 * Initialize the plugin at correct hook.
	 */
	$smartmag = SmartMag_Core::instance();
	add_action('after_setup_theme', [$smartmag, 'init']);

}, 1);
