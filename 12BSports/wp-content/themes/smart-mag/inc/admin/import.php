<?php
/**
 * Demo Importer - Requires Bunyad Demo Import plugin
 * 
 * @see Bunyad_Demo_Import
 */
class Bunyad_Theme_Admin_Import
{
	public $demos = [];
	public $admin_page;
	public $importer;
	
	public function __construct()
	{
		add_filter('bunyad_import_demos', [$this, 'get_sources']);
		// add_filter('pt-ocdi/importer_options', [$this, 'importer_options']);
		add_action('tgmpa_register', [$this, 'register_plugins']);
		
		// Disable thumbnail creation. Manually prompted via Regenerate thumbnails instead.
		add_filter('pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false');

		// At beginning of import action.
		add_action('bunyad_import_begin', [$this, 'pre_import']);

		// After import actions.
		add_action('bunyad_import_done', [$this, 'update_options'], 10, 3);
		add_action('bunyad_import_done', [$this, 'post_import'], 10, 3);
		
		// Register an informational section on customizer
		add_action('customize_register', [$this, 'customizer_info'], 12);
	}

	/**
	 * Return the demo data for the importer.
	 */
	public function get_sources()
	{
		if ($this->demos) {
			return $this->demos;
		}

		// Known plugin slugs and names.
		$plugins = [
			'elementor'             => 'Elementor Page Builder',
			'sphere-core'           => 'Sphere Core',
			'smartmag-core'         => 'SmartMag Core',
			'regenerate-thumbnails' => 'Regenerate Thumbnails',
			'sphere-post-views'     => 'Sphere Post Views',
			'cryptocurrency-price-ticker-widget' => 'Cryptocurrency Widgets',
		];

		// Base required plugins for all demos.
		$required_plugins = [
			'elementor'             => $plugins['elementor'], 
			'sphere-core'           => $plugins['sphere-core'],
			'smartmag-core'         => $plugins['smartmag-core'],
			'regenerate-thumbnails' => $plugins['regenerate-thumbnails'],
		];

		// Demo configs
		$this->demos = [
			'good-news' => [
				'demo_name'           => 'GoodNews / General',
				'demo_description'    => 'GoodNews Demo.',
				'demo_url'            => 'https://smartmag.theme-sphere.com/good-news/',
				'depends'             => $required_plugins,
			],
			
			'tech-1' => [
				'demo_name'          => 'Tech 1: iGadgets',
				'demo_description'   => 'Tech 1 iGadgets Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/tech-1/',
				'depends'            => $required_plugins,
			],

			'tech-2' => [
				'demo_name'          => 'Tech 2: TheWire',
				'demo_description'   => 'Tech 2 Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/tech-2/',
				'depends'            => $required_plugins,
			],

			'tech-blog' => [
				'demo_name'          => 'TechBlog',
				'demo_description'   => 'TechBlog Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/tech-blog/',
				'depends'            => $required_plugins + [
					'sphere-post-views' => $plugins['sphere-post-views']
				],
			],

			'geeks-empire' => [
				'demo_name'          => 'Geeks Empire: Entertainment',
				'demo_description'   => 'Geeks Empire Entertainment Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/geeks-empire/',
				'depends'            => $required_plugins,
			],
			
			'financial' => [
				'demo_name'          => 'Financial',
				'demo_description'   => 'Financial SmartMag Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/financial/',
				'depends'            => $required_plugins,
			],

			'news' => [
				'demo_name'          => 'News: Observer',
				'demo_description'   => 'News Observer Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/news/',
				'depends'            => $required_plugins,
			],

			'smart-times' => [
				'demo_name'          => 'SmartTimes',
				'demo_description'   => 'SmartTimes Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/smart-times/',
				'depends'            => $required_plugins,
			],
			
			'prime-mag' => [
				'demo_name'          => 'PrimeMag',
				'demo_description'   => 'PrimeMag SmartMag Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/prime-mag/',
				'depends'            => $required_plugins,
			],

			'health' => [
				'demo_name'          => 'Heath',
				'demo_description'   => 'Health Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/health/',
				'depends'            => $required_plugins,
			],

			'news-time' => [
				'demo_name'          => 'NewsTime',
				'demo_description'   => 'NewsTime Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/news-time/',
				'depends'            => $required_plugins + [
					'sphere-post-views' => $plugins['sphere-post-views']
				]
			],

			'fitness' => [
				'demo_name'          => 'Muscle+Fitness',
				'demo_description'   => 'Fitness Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/fitness/',
				'depends'            => $required_plugins,
			],

			'zine' => [
				'demo_name'          => 'TheZine',
				'demo_description'   => 'TheZine Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/zine/',
				'depends'            => $required_plugins,
			],

			'pro-mag' => [
				'demo_name'          => 'ProMag',
				'demo_description'   => 'ProMag SmartMag Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/pro-mag/',
				'depends'            => $required_plugins,
			],

			'coinbase' => [
				'demo_name'          => 'CoinBase',
				'demo_description'   => 'CoinBase SmartMag Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/coinbase/',
				'depends'            => $required_plugins + [
					'cryptocurrency-price-ticker-widget' => $plugins['cryptocurrency-price-ticker-widget']
				]
			],

			'citybuzz' => [
				'demo_name'          => 'CityBuzz',
				'demo_description'   => 'CityBuzz SmartMag Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/citybuzz/',
				'depends'            => $required_plugins,
			],

			'gaming' => [
				'demo_name'          => 'Gaming',
				'demo_description'   => 'Gaming Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/gaming/',
				'depends'            => $required_plugins,
			],

			'tech-drop' => [
				'demo_name'          => 'TechDrop',
				'demo_description'   => 'TechDrop Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/tech-drop/',
				'depends'            => $required_plugins,
			],

			'digi-tech' => [
				'demo_name'          => 'DigiTech',
				'demo_description'   => 'DigiTech Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/digi-tech/',
				'depends'            => $required_plugins + [
					'sphere-post-views' => $plugins['sphere-post-views']
				]
			],

			'blogger' => [
				'demo_name'          => 'Blogger',
				'demo_description'   => 'Blogger Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/blogger/',
				'depends'            => $required_plugins,
			],

			'sports' => [
				'demo_name'          => 'Sports',
				'demo_description'   => 'Sports Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/sports/',
				'depends'            => $required_plugins,
			],
			
			'social-life' => [
				'demo_name'          => 'SocialLife',
				'demo_description'   => 'SocialLife Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/social-life/',
				'depends'            => $required_plugins,
			],

			'classic' => [
				'demo_name'          => 'Classic/Legacy',
				'demo_description'   => 'Legacy SmartMag Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/classic/',
				'depends'            => $required_plugins,
			],

			'trendy' => [
				'demo_name'          => 'Trendy',
				'demo_description'   => 'Trendy SmartMag Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/trendy/',
				'depends'            => $required_plugins,
			],

			'informed' => [
				'demo_name'          => 'Informed News',
				'demo_description'   => 'Informed news Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/informed/',
				'depends'            => $required_plugins,
			],

			'gaming-dark' => [
				'demo_name'          => 'Gaming Dark',
				'demo_description'   => 'Gaming Dark Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/gaming-dark/',
				'depends'            => $required_plugins,
			],

			'news-bulletin' => [
				'demo_name'          => 'NewsBulletin',
				'demo_description'   => 'NewsBulletin Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/news-bulletin/',
				'depends'            => $required_plugins,
			],

			'gossip-mag' => [
				'demo_name'          => 'GossipMag',
				'demo_description'   => 'GossipMag Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/gossip-mag/',
				'depends'            => $required_plugins,
			],

			'mag-studio' => [
				'demo_name'          => 'MagStudio',
				'demo_description'   => 'MagStudio Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/mag-studio/',
				'depends'            => $required_plugins,
			],

			'game-zone' => [
				'demo_name'          => 'GameZone',
				'demo_description'   => 'GameZone Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/game-zone/',
				'depends'            => $required_plugins,
			],

			'news-board' => [
				'demo_name'          => 'NewsBoard',
				'demo_description'   => 'NewsBoard Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/news-board/',
				'depends'            => $required_plugins,
			],

			'rtl' => [
				'demo_name'          => 'RTL/Arabic',
				'demo_description'   => 'RTL/Arabic Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/rtl/',
				'depends'            =>  $required_plugins + [
					'sphere-post-views' => $plugins['sphere-post-views']
				]
			],
			
			'discover' => [
				'demo_name'          => 'Discover',
				'demo_description'   => 'Discover Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/discover/',
				'depends'            =>  $required_plugins + [
					'sphere-post-views' => $plugins['sphere-post-views']
				]
			],

			'friday-mag' => [
				'demo_name'          => 'FridayMag',
				'demo_description'   => 'FridayMag Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/friday-mag/',
				'depends'            =>  $required_plugins + [
					'sphere-post-views' => $plugins['sphere-post-views']
				]
			],

			'smart-post' => [
				'demo_name'          => 'SmartPost',
				'demo_description'   => 'SmartPost Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/smart-post/',
				'depends'            =>  $required_plugins + [
					'sphere-post-views' => $plugins['sphere-post-views']
				]
			],

			'magazine-co' => [
				'demo_name'          => 'MagazineCo',
				'demo_description'   => 'MagazineCo Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/magazine-co/',
				'depends'            =>  $required_plugins + [
					'sphere-post-views' => $plugins['sphere-post-views']
				]
			],

			'smart-life' => [
				'demo_name'          => 'SmartLife',
				'demo_description'   => 'SmartLife Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/smart-life/',
				'depends'            =>  $required_plugins + [
					'sphere-post-views' => $plugins['sphere-post-views']
				]
			],

			'digital-hub' => [
				'demo_name'          => 'DigitalHub',
				'demo_description'   => 'DigitalHub Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/digital-hub/',
				'depends'            =>  $required_plugins + [
					'sphere-post-views' => $plugins['sphere-post-views']
				]
			],

			'city-today' => [
				'demo_name'          => 'CityToday',
				'demo_description'   => 'CityToday Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/city-today/',
				'depends'            =>  $required_plugins,
			],

			'everyday-news' => [
				'demo_name'          => 'EverydayNews',
				'demo_description'   => 'EverydayNews Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/everyday-news/',
				'depends'            =>  $required_plugins + [
					'sphere-post-views' => $plugins['sphere-post-views']
				]
			],

			'world-mag' => [
				'demo_name'          => 'WorldMag',
				'demo_description'   => 'WorldMag Demo.',
				'demo_url'           => 'https://smartmag.theme-sphere.com/world-mag/',
				'depends'            =>  $required_plugins + [
					'sphere-post-views' => $plugins['sphere-post-views']
				]
			],
		];

		foreach ($this->demos as $key => $demo) {
			$this->demos[$key] = array_replace([
				'demo_image'                   => get_template_directory_uri() . "/inc/demos/{$key}.jpg",
				'local_import_file'            => get_template_directory() . "/inc/demos/{$key}.xml",
				'local_import_widget_file'     => get_template_directory() . "/inc/demos/{$key}-widgets.json",
				'local_import_customizer_file' => get_template_directory() . "/inc/demos/{$key}-customizer.dat"
			], $demo);
		}

		return $this->demos;
	}
	
	/**
	 * Register a few extra plugins with TGMPA
	 */
	public function register_plugins()
	{	
		// Some plugin calling the hook incorrectly when tgmpa doesn't exist yet?
		if (!function_exists('tgmpa')) {
			return;
		}

		tgmpa([
			[
				'name'      => esc_html__('Regenerate Thumbnails', 'bunyad-admin'),
				'slug'      => 'regenerate-thumbnails',
				'required'  => false,
			],
		], ['is_automatic' => true]);
	}

	/**
	 * Callback run at beginning of import.
	 */
	public function pre_import()
	{
		/**
		 * Earlier import data exists. Clear it.
		 */
		if (Bunyad::options()->installed_demo) {

			// Refresh options.
			Bunyad::options()->init();

			// These settings shouldn't generally be overwritten by demos so preserve them.
			$keep_options = [

				// @todo Logos too with an option to not replace.
				// Misc
				'social_profiles',
				'search_posts_only',
				'enable_lightbox',
				'enable_lightbox_mobile',
				'amp_enabled',
				'guten_styles',
				'fontawesome4',
				'woocommerce_per_page',
				'woocommerce_image_zoom',
			];

			$options = Bunyad::options()->get_all();
			foreach ($options as $option => $value) {
				if (in_array($option, $keep_options)) {
					continue;
				}

				unset($options[$option]);
			}

			Bunyad::options()
				->set_all($options)
				->update();
		}
	}

	/**
	 * Action callback Post Process: Update Options
	 * 
	 * @param string $demo_id
	 * @param OCDI_WXR_Importer $import
	 * @return void
	 */
	public function update_options($demo_id, $import)
	{
		// Refresh options with the updated values by the importer.
		Bunyad::options()->init();
		Bunyad::options()
			->set('installed_demo', $demo_id);

		/**
		 * Remap several ids, whether full or settings import.
		 */
		$import_data = $import->get_importer_data();
		$mapping     = $import_data['mapping'];

		// Remap custom footer ids.
		$footer_id = Bunyad::options()->footer_custom ?: false;
		if ($footer_id) {

			// Remove if we can't be remapped.
			$new_id = $mapping['post'][$footer_id] ?? '';
			Bunyad::options()->set('footer_custom', $new_id);
		}

		// Remap custom archives ids.
		$archives = [
			'category_loop_custom',
			'archive_loop_custom',
			'cpt_loop_custom',
			'author_loop_custom',
			'search_loop_custom',
		];

		foreach ($archives as $archive) {
			$archive_id = Bunyad::options()->get($archive) ?: false;
			if ($archive_id) {
				$new_id = $mapping['post'][$archive_id] ?? '';
				Bunyad::options()->set($archive, $new_id);
			}	
		}

		Bunyad::options()->update();
	}
	
	/**
	 * Other actions to do after the import is done. Mainly for 'full' import.
	 * 
	 * @param string $demo_id
	 * @param OCDI_WXR_Importer $import
	 * @param Bunyad_Demo_Import $import_main
	 */
	public function post_import($demo_id, $import, $import_main)
	{
		$import_type = $import_main->import_type;

		/**
		 * Everything below is for full imports only.
		 */
		if ($import_type !== 'full') {
			return;
		}

		$menus_data  = include get_theme_file_path('inc/demos/menus-data.php');

		// Import menus.
		if ($menus_data && class_exists('Bunyad_Demo_Import_Menus')) {
			$menus = new Bunyad_Demo_Import_Menus($menus_data);
			$menus->import();
		}

		// Unpublish hello world post.
		// $hello = get_page_by_title('Hello world!', OBJECT, 'post');
		$hello_query = new WP_Query([
			'post_type'              => 'post',
			'title'                  => 'Hello world!',
			'post_status'            => 'all',
			'posts_per_page'         => 1,
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'orderby'                => 'post_date ID',
			'order'                  => 'ASC',
		]);
		
		$hello = !empty($hello_query) ? $hello_query->post : false;
		if ($hello && $hello->ID < 5) {
			wp_update_post([
				'ID'          => $hello->ID,
				'post_status' => 'draft'
			]);
		}

		/**
		 * Process the homepage to remap terms.
		 */
		$home = $this->get_homepage();
		if (is_object($home)) {
			update_option('show_on_front', 'page');
			update_option('page_on_front', $home->ID);

			$this->post_process_elementor($home->ID, $import);
		}
	}

	/**
	 * Modified get_page_by_title() to return the latest Homepage (with ORDER by).
	 * 
	 * Note: WordPress doesn't have a native function that does this.
	 */
	protected function get_homepage() 
	{
		global $wpdb;

		// Nothing dynamic to prepare, hence direct.
		$page = $wpdb->get_var("
			SELECT ID
			FROM $wpdb->posts
			WHERE post_title = 'Homepage'
			AND post_type = 'page'
			ORDER by ID desc
		");

		if ($page) {
			return get_post($page);
		}

		return false;
	}

	/**
	 * Remap Elementor block terms.
	 * 
	 * @param integer  $page_id
	 * @param OCDI_WXR_Importer $import
	 */
	public function post_process_elementor($page_id, $import)
	{
		$import_data = $import->get_importer_data();
		$mapping     = $import_data['mapping'];

		// Get elementor data.
		$content = get_post_meta($page_id, '_elementor_data', true);
		if (!$content) {
			return;
		}

		/**
		 * Replace the term ids in the JSON. This is faster than using recursive loops.
		 * 
		 * @param array $matches
		 */
		$replacer = function($matches) use($mapping) {

			$replace = $matches[0];
			$terms   = json_decode('[' . $matches[2] . ']', true);
			if (!$terms) {
				return $replace;
			}

			$new_terms = [];
			foreach ($terms as $term) {
				if (!empty($mapping['term_id'][$term])) {
					$new_terms[] = $mapping['term_id'][$term];
				}
			}

			if ($new_terms) {
				return sprintf('"%s":%s', $matches[1], json_encode($new_terms));
			}
		
			return $replace;
		};

		$content = preg_replace_callback(
			'/"(filters_terms|filters_tags|terms|cat|tags)":\[([^\]]+)\]/', 
			$replacer, 
			$content
		);

		// Replace single string values, not array values as above.
		$content = preg_replace_callback(
			'/"(cat)":"(\d+)"/',
			function($matches) use ($mapping) {
				$term = $matches[2];
				if (!empty($mapping['term_id'][$term])) {
					return sprintf('"%s":"%s"', $matches[1], $mapping['term_id'][$term]);
				}
				return $matches[0];
			},
			$content
		);

		// Needed as update_post_meta strips.
		$content = wp_slash($content);

		// Update the page.
		update_post_meta($page_id, '_elementor_data', $content);

		// Just update last modified date/time.
		wp_update_post(['ID' => $page_id]);
	}
	
	/**
	 * Customizer information
	 */
	public function customizer_info($wp_customizer)
	{
		/* @var $wp_customizer WP_Customize_Manager */
		$control = $wp_customizer->get_control('bunyad_import_info');
		
		// Move if already installed a demo.
		if (Bunyad::options()->installed_demo) {
			$section = $wp_customizer->get_section('bunyad-select-skin');

			if (is_object($section)) {
				$section->priority = 20;
			}
		}

		// Plugin active
		if (class_exists('Bunyad_Demo_Import')) {
			$control->text = sprintf(
				esc_html__('You can import demo settings or full demo content from %1$s this page %2$s.', 'bunyad-admin'), 
				'<a href="' . esc_url(admin_url('themes.php?page=bunyad-demo-import')) .'" target="_blank">',
				'</a>'
			);
			
			return;
		}
		
		// Prompt for plugin activation
		$control->text = sprintf(
			esc_html__('Please install and activate the required plugin "Bunyad Demo Import" from %1$sthis page%2$s.', 'bunyad-admin'), 
			'<a href="' . esc_url(admin_url('admin.php?page=tgmpa-install-plugins')) .'">',
			'</a>'
		);
	}
}

// init and make available in Bunyad::get('admin_import')
Bunyad::register('admin_import', [
	'class' => 'Bunyad_Theme_Admin_Import',
	'init'  => true
]);