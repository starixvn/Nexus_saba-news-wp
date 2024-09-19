<?php
/**
 * Migrate to version 5.0.0.
 * 
 * @var $this Bunyad_Theme_Admin_Migrations
 */
class Bunyad_Theme_Admin_Migrations_500Update extends Bunyad_Theme_Admin_Migrations_Base
{
	public function begin()
	{
		$this->options = array_replace($this->options, [
			'legacy_mode'           => 1,
			'post_layout_spacious'  => 0,
			'custom_width'          => 1,
			'layout_width'          => 1078,
			'single_featured_ratio' => 'custom',
			'single_featured_ratio_custom' => 2,

			'loop_grid_excerpt_length'  => 15,
			'loop_large_excerpt_length' => 100,
			'loop_list_excerpt_length'  => 20,

			'category_featured_skip'  => 0,
			'single_share_float'      => 0,
		]);

		// Old default is now classic.
		if (empty($this->options['predefined_style'])) {
			$this->options['predefined_style'] = 'classic';

			// Enable breadcrumbs on pagebuilder templates for classic.
			$this->options['breadcrumbs_pagebuilder'] = 1;
		}

		// Set color - even the default has changed.
		$this->options['css_main_color'] = $this->get_main_color();
	
		// Font charset fix.
		if (isset($this->options['font_charset'])) {
			$this->options['font_charset'] = array_keys(array_filter(
				(array) $this->options['font_charset']
			));
		}
		
		// Modern is just layout spacious. modern-b is non-spacious.
		if (isset($this->options['post_layout_template'])) {
			if ($this->options['post_layout_template'] === 'modern') {
				$this->options['post_layout_spacious'] = 1;
			}
			else if ($this->options['post_layout_template'] === 'modern-b') {
				$this->options['post_layout_template'] = 'modern';
			}
		}
		
		if (empty($this->options['show_tags'])) {
			$this->options['single_tags'] = 0;
		}
		
		/**
		 * Rename some options.
		 */
		$this->rename_options([
			'layout_style'           => 'layout_type',
			'sticky_sidebar'         => 'sidebar_sticky',
			'image_logo_retina'      => 'image_logo_2x',
			'image_logo_mobile'      => 'mobile_logo_2x',
			'lightbox_prettyphoto'   => 'enable_lightbox',
			'header_custom_code'     => 'codes_header',
			'schema_article'         => 'single_schema_article',

			// Single
			'social_share'           => 'single_share_bot',
			'post_navigation'        => 'single_navigation',
		
			// Typography
			'css_main_font'              => 'css_font_text',
			'css_heading_font'           => 'css_font_secondary',
			'css_navigation_font'        => 'css_nav_font',
			'css_post_body_font'         => 'css_single_body_typo_family',
			'css_post_body_font_size'    => 'css_single_body_typo_size',
			'css_listing_body_font'      => 'css_excerpts_typo_family',
			'css_listing_body_font_size' => 'css_excerpts_typo_size',
			'css_post_title_font'        => 'css_single_h_typo_family',
		
			'css_post_h1' => 'css_font_post_h1',
			'css_post_h1' => 'css_font_post_h2',
			'css_post_h3' => 'css_font_post_h3',
			'css_post_h4' => 'css_font_post_h4',
			'css_post_h5' => 'css_font_post_h5',
			'css_post_h6' => 'css_font_post_h6',
		
			// Colors
			'css_body_bg_color'       => 'css_site_bg',
			'css_post_text_color'     => 'css_single_body_color',
			'css_listing_text_color'  => 'css_excerpts_color',
			'css_headings_text_color' => 'css_h_color',
			'css_links_color'         => 'css_single_a_color',
		
			// Sidebar - to new block headings.
			'css_sidebar_heading_bg_color' => 'css_bhead_bg_g',
			'css_sidebar_heading_color'    => 'css_bhead_color_g',

			// Classic slider.
			'slider_animation' => 'classic_slider_animation',
			'slider_slide_delay' => 'classic_slider_slide_delay',
			'slider_animation_speed' => 'classic_slider_animation_speed',
			'featured_right_cat' => 'classic_slider_right_cat',
			'featured_right_tag' => 'classic_slider_right_tag',
			'css_slider_bg_color' => 'css_classic_slider_bg_color',
			'css_slider_bg_pattern' => 'css_classic_slider_bg_image'
		]);

		// Do skin-specific options and conversions.
		$do_skin = [$this, "do_{$this->options['predefined_style']}_skin"];
		if (is_callable($do_skin)) {
			call_user_func($do_skin);
		}

		$this->setup_legacy_widgets();

		// Done after skin-specific, as these will be based on options that may have been
		// modified beyond the skin defaults.
		$this->do_typography();
		$this->do_fix_h16();
		$this->do_header();
		$this->do_header_widgets();
		$this->do_navigation();
		$this->do_footer();
		$this->do_listings();
		$this->do_custom_css();

		// Menus need to be remapped.
		$this->do_menu_locations();

		// Misc widgets like about etc.
		$this->do_other_widgets();
		
		if (!empty($this->options['layout_type']) && $this->options['layout_type'] === 'full') {
			unset($this->options['layout_type']);
		}
		
		if (empty($this->options['sidebar_sticky'])) {
			$this->options['sidebar_sticky'] = 0;
		}
		
		if (!empty($this->options['disable_breadcrumbs'])) {
			$this->options['breadcrumbs_enable'] = 0;
		}
		
		// Has changed from a URL to an id as there's only 2x for retina, requiring dimensions.
		if (!empty($this->options['mobile_logo_2x'])) {
			$this->options['mobile_logo_2x'] = attachment_url_to_postid($this->options['mobile_logo_2x']);
		}
		
		$this->unset_if_match([
			'pagination_type' => '',
		
			// Now default is 1.
			'sidebar_sticky' => 1
		]);

		unset(
			$this->options['theme_version']
		);
	}

	/**
	 * Get new loop template based on previous value.
	 *
	 * @param string $old
	 * @return string
	 */
	public static function get_loop_template($old) 
	{
		$loop_map = [
			'modern'              => 'grid-2',
			'loop'                => 'grid-2',
			'modern-3'            => 'grid-3',
			'loop-3'              => 'grid-3',
			'grid-overlay'        => 'overlay-2',
			'loop-grid-overlay'   => 'overlay-2',
			'grid-overlay-3'      => 'overlay-3',
			'loop-grid-overlay-3' => 'overlay-3',
			'tall-overlay'        => 'overlay-3',
			'loop-tall-overlay'   => 'overlay-3',
			'alt'                 => 'posts-list',
			'loop-alt'            => 'posts-list',
			'loop-classic'        => 'classic',
			'loop-timeline'       => 'timeline'
		];

		if (isset($loop_map[ $old ])) {
			return $loop_map[ $old ];
		}

		return $old;
	}

	/**
	 * Fix system and custom fonts in values.
	 */
	function convert_typography($value) 
	{
		if (strpos($value, 'system:') !== false) {
			$map = [
				'system: Arial, "Helvetica Neue", Helvetica, sans-serif' => 'sans-serif',
				'system: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif' => 'Calibri',
				'system: Georgia, Cambria, "Times New Roman", Times, serif' => 'serif'
			];

			if (isset($map[ $value ])) {
				return $map[ $value ];
			}
		}

		// Remove custom font prefix.
		$value = str_replace('custom: ', '', $value);

		return $value;
	}

	/**
	 * Typography conversion.
	 */
	public function do_typography()
	{
		$typo = [
			'css_font_text',
			'css_font_secondary',
			'css_nav_font',
			'css_single_body_typo_family',
			'css_excerpts_typo_family',
			'css_single_h_typo_family'
		];
		
		foreach ($typo as $opt) {
			if (!isset($this->options[$opt])) {
				continue;
			}
			$this->options[$opt] = $this->convert_typography($this->options[$opt]);
		}
	}

	/**
	 * Convert header options.
	 */
	public function do_header()
	{
		$header_opts = [
			'header_width'           => 'full-wrap',

			// 'header_items_top_left'  => ['date', 'nav-small'],
			// 'header_items_top_right' => ['social-icons'],
			'header_items_top_left'  => [],
			'header_items_top_right' => [],
			
			'header_scheme_mid'      => 'light',
			'header_items_mid_left'  => ['logo'],
			// 'header_items_mid_right' => ['text'],
			'header_items_mid_right' => [],
			
			// 'header_width_bot'       => 'full-wrap',
			'header_items_bot_left'   => ['nav-menu'],
			'header_items_bot_center' => [],
			// 'header_items_bot_right'  => ['search'],
			'header_items_bot_right'  => [],
		];

		$old_style = empty($this->options['header_style']) ? 'default' : $this->options['header_style'];

		switch ($old_style) {
			case 'default':
			case 'centered':
				$header_opts = array_replace($header_opts, [
					'header_layout'          => 'smart-legacy',
					'header_social_style'    => 'c',
					'header_search_type'     => 'form',
					'header_scheme_top'      => 'light',
					'header_items_top_left'  => ['ticker'],
					// 'header_items_top_right' => ['social-icons', 'search'],
					'header_width_bot'       => 'contain',
					'header_items_bot_right' => [],
					'header_nav_hov_style'   => 'b'
				]);

				if ($old_style === 'centered') {
					$header_opts = array_replace($header_opts, [
						'header_items_mid_left'   => [],
						'header_items_mid_center' => ['logo'],
						'header_items_mid_right'  => [],
					]);
				}

				break;

			case 'dark':
				$header_opts = array_replace($header_opts, [
					// Top and bot are dark by default.
					'header_scheme_mid'            => 'dark',

					// Line below and disable hover lines.
					'css_nav_hov_b_weight'         => '0',
					'css_header_border_bottom_bot' => '3',
					'css_header_c_border_bot_bot'  => $this->get_main_color(),
				]);

				// Breadcrumbs were different for this style.
				$this->options['breadcrumbs_style']     = 'b';
				$this->options['breadcrumbs_add_label'] = 1;
				break;
		}
		
		/**
		 * Begin topbar conversion.
		 */
		// Remove topbar.
		if (!empty($this->options['disable_topbar'])) {
			$header_opts = array_replace($header_opts, [
				'header_items_top_left'   => [],
				'header_items_top_center' => [],
				'header_items_top_right'  => [],
			]);
		}

		// Topbar scheme.
		if (empty($this->options['topbar_style'])) {
			$header_opts['header_scheme_top'] = 'light';
		}
		else {
			$header_opts['header_scheme_top'] = 'dark';
		}

		// Disable ticker.
		if (!empty($this->options['disable_topbar_ticker'])) {
			$header_opts['header_items_top_left'] = array_diff(
				$header_opts['header_items_top_left'],
				['ticker']
			);
		}

		// Topbar date.
		if (!empty($this->options['topbar_date'])) {
			array_push($header_opts['header_items_top_left'], 'date');
		}

		// Search form in topbar.
		if (!isset($this->options['topbar_search']) || $this->options['topbar_search']) {
			array_push($header_opts['header_items_top_right'], 'search');
		}

		$this->rename_options([
			'topbar_ticker_text'  => 'header_ticker_heading',
			'topbar_live_search'  => 'header_search_live',
			'live_search_number'  => 'header_search_live_posts',
			'css_menu_bg_color'   => 'css_header_bg_bot',
			'css_menu_text_color' => ['css_nav_color_light', 'css_nav_color_dark'],
			'css_topbar_bg_color' => 'css_header_bg_top',
			'css_header_bg_color' => 'css_header_bg_mid',
			'css_header_bg_pattern' => 'css_header_bg_image_mid',
			'css_mega_menu_subnav'  => ['css_mega_menu_sub_bg_light', 'css_mega_menu_sub_bg_dark'],
		]);

		$this->options = array_replace($this->options, $header_opts);
	}

	/**
	 * Convert social icons shortcode to global options.
	 */
	public function _convert_social($content, $strip = false) 
	{
		// $pattern = get_shortcode_regex();
		// social_icon won't be available without the plugin, so custom regex.
		$pattern = '(.?)\[(social_icon)\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)';
		$the_content = preg_replace('#\[/?social\]#', '', $content);
		preg_match_all("/$pattern/s", $the_content, $matches);

		if (empty($matches[3])) {
			return ['services' => []];
		}

		if (!isset($this->options['social_profiles'])) {
			$this->options['social_profiles'] = [];
		}

		$services = [];
		foreach ((array) $matches[3] as $match) {
			$social = shortcode_parse_atts($match);
			if (!$social['link']) {
				continue;
			}

			if ($social['link'] !== '#') {
				$this->options['social_profiles'][ $social['type'] ] = $social['link'];
			}

			$services[] = $social['type'];
		}

		if ($strip) {
			$content = str_replace($matches[0], '', $the_content);
		}

		return [
			'services' => $services,
			'content'  => $content
		];
	}

	/**
	 * Temporary setup old widgets if available to allow conversion of old widgets.
	 */
	public function setup_legacy_widgets()
	{
		if (!class_exists('Bunyad_Widgets', false)) {
			return;
		}

		add_filter('bunyad-active-widgets', function($widgets) {
			return ['about', 'latest-posts', 'popular-posts', 'tabbed-recent', 'flickr', 'ads', 'latest-reviews', 'bbp-login', 'tabber', 'blocks', 'social'];
		});

		$widgets = new Bunyad_Widgets;
		$widgets->setup();

		if (did_action('widgets_init')) {
			global $wp_widget_factory;

			$method = [$wp_widget_factory, '_register_widgets'];
			if (is_callable($method)) {
				call_user_func($method);
			}
		}
	}

	/**
	 * Convert widgets from old header widget areas to new header options.
	 */
	public function do_header_widgets() 
	{	
		/**
		 * Top Bar widgets.
		 */
		$widgets = $this->get_widgets_data('top-bar');
		
		foreach ($widgets as $data) {
			
			// Topbar nav links.
			if ($data['object']['classname'] === 'widget_nav_menu') {
				$this->options['header_items_top_left'][] = 'nav-small';
				$this->options['header_nav_small_menu'] = $data['options']['nav_menu'];
			}

			// Custom HTML / Text with social shortcode.
			if (in_array($data['object']['classname'], ['widget_custom_html', 'widget_text'])) {
				$content = isset($data['options']['content']) ? $data['options']['content'] : $data['options']['text'];
			
				if (strpos($content, '[social]') !== false) {
					$parsed   = $this->_convert_social($content);
					$services = $parsed['services'];

					if ($services) {
						array_unshift($this->options['header_items_top_right'], 'social-icons');
						$this->options['header_social_services'] = $services;
					}
				}
			}
		}

		/**
		 * Header right widgets.
		 */
		$widgets = $this->get_widgets_data('header-right');
		$texts   = ['text', 'text2', 'text3', 'text4'];
		foreach ($widgets as $id => $data) {

			// Bunyad ad widget.
			if (strpos($id, 'bunyad_ads_widget') !== false) {
				$text = array_shift($texts);
				$this->options['header_items_mid_right'][] = $text;
				$this->options["header_{$text}"] = $data['options']['code'];
			}

			// Text/HTML.
			if (in_array($data['object']['classname'], ['widget_custom_html', 'widget_text'])) {
				$content = isset($data['options']['content']) ? $data['options']['content'] : $data['options']['text'];

				$text = array_shift($texts);
				$this->options['header_items_mid_right'][] = $text;
				$this->options["header_{$text}"] = $content;
			}
		}
	}

	/**
	 * Remap menu locations.
	 */
	public function do_menu_locations()
	{
		$locations = get_nav_menu_locations();

		if (isset($locations['main'])) {
			$locations['smartmag-main'] = $locations['main'];
		}

		if (isset($locations['main-mobile'])) {
			$locations['smartmag-mobile'] = $locations['main-mobile'];
		}

		set_theme_mod('nav_menu_locations', $locations);
	}

	public function do_other_widgets()
	{
		// Rename primary sidebar.
		$sidebars = get_option('sidebars_widgets', []);
		if (isset($sidebars['primary-sidebar'])) {
			$sidebars['smartmag-primary'] = $sidebars['primary-sidebar'];
			unset($sidebars['primary-sidebar']);
			
			update_option('sidebars_widgets', $sidebars);
		}

		$widgets = array_merge(
			$this->get_widgets_data('header-right'),
			$this->get_widgets_data('smartmag-primary'),
			$this->get_widgets_data('main-footer')
		);

		// Widget data is stored as one option holding multiple instances with key.
		$update_widget = function($option_data, $options) {
			list($key, $option_name) = $option_data;

			$current_data = (array) get_option($option_name);
			$current_data[$key] = $options;

			update_option($option_name, $current_data);
		};

		foreach ($widgets as $id => $data) {
			if (strpos($id, 'bunyad_about_widget') !== false) {
				$content = $data['options']['text'];

				if (strpos($content, '[social]') !== false) {
					$parsed   = $this->_convert_social($content, true);
					$services = $parsed['services'];
					$data['options']['text'] = $parsed['content'];

					if ($services) {
						$data['options']['social'] = $services;
					}

					$update_widget($data['option'], $data['options']);
				}
			}
		}
	}

	/**
	 * Convert navigation styling etc. to new options.
	 */
	public function do_navigation()
	{
		$nav_style = empty($this->options['nav_style']) ? 'dark' : $this->options['nav_style'];
		$nav_style = preg_replace('/(nav-|)(\w+)(-b|$)/', '\\2', $nav_style);
		$nav_options = [
			'css_menu_drop_bg'        => 'css_drop_bg',
			'css_menu_hover_bg_color' => 'css_drop_hov_bg',
			'css_menu_borders_color'  => 'css_drop_sep',
			'css_menu_text_color'     => 'css_nav_color'
		];
		
		foreach ($nav_options as $old => $new) {
			$this->rename_option($old, $new . "_{$nav_style}");
		}
		
		if ($nav_style !== 'dark') {
			$this->options['header_scheme_bot'] = 'light';
		}

		// By default it's full-wrap (old nav-full).
		if (empty($this->options['nav_layout'])) {
			$this->options['header_width_bot'] = 'contain';
		}

		// Centered nav.
		if (!empty($this->options['nav_align'])) {
			$this->options['header_items_bot_left']  = [];
			$this->options['header_items_bot_right'] = [];
			$this->options['header_items_bot_center'] = ['nav-menu'];
		}

		// Search icon in navigation.
		if (!empty($this->options['nav_search'])) {
			array_push(
				$this->options['header_items_bot_right'],
				'search'
			);

			unset($this->options['header_search_type']);
		}

		// Enable sticky navigation in header.
		if (!empty($this->options['sticky_nav'])) {
			$this->options = array_replace($this->options, [
				'header_sticky'      => 'bot',
				'header_sticky_type' => $this->options['sticky_nav'] === 'smart' ? 'smart' : 'fixed'
			]);
		}
	}

	/**
	 * Migrate footer options and widgets.
	 */
	public function do_footer()
	{
		$this->options = array_replace($this->options, [
			'footer_head_style' => 'h',
			'footer_layout'     => 'classic',
			'footer_upper_cols' => '3'
		]);

		if (!empty($this->options['footer_columns'])) {
			$this->options['footer_upper_cols'] = 'custom';
		}
		
		if (!empty($this->options['disable_footer'])) {
			$this->options['footer_upper'] = 0;
		}
		
		if (!empty($this->options['disable_lower_footer'])) {
			$this->options['footer_lower'] = 0;
		}

		$this->rename_options([
			'footer_custom_code'        => 'codes_footer',
			'footer_columns'            => 'footer_upper_cols_custom',
			'css_footer_bg_color'       => 'css_footer_upper_bg',
			'css_footer_headings_color' => 'css_footer_head_color',
			'css_footer_text_color'     => 'css_footer_upper_text',
			'css_footer_links_color'    => 'css_footer_upper_links',
		]);

		if (!empty($this->options['footer_upper_cols_custom'])) {
			$this->options['footer_upper_cols'] = 'custom';
		}

		/**
		 * Footer widgets.
		 */
		$widgets = $this->get_widgets_data('lower-footer');
		foreach ($widgets as $id => $data) {

			// Text widget - for copyright.
			if (in_array($data['object']['classname'], ['widget_custom_html', 'widget_text'])) {
				$content = isset($data['options']['content']) ? $data['options']['content'] : $data['options']['text'];

				if (!isset($this->options['footer_copyright'])) {
					$this->options['footer_copyright'] = $content;
				}
			}

			// Footer links.
			if ($data['object']['classname'] === 'widget_nav_menu') {
				$menu_id = $data['options']['nav_menu'];

				$locations = get_theme_mod('nav_menu_locations');
				$locations['smartmag-footer-links'] = $menu_id;
				set_theme_mod('nav_menu_locations', $locations);
			}
		}

	}

	/**
	 * Fix h1 - h6 to add device keys.
	 */
	public function do_fix_h16() 
	{
		// css_font_post_h1-h6 has changed. No more font_size and supports devices now.
		foreach (range(1, 6) as $key) {
			$opt = "css_font_post_h{$key}";
			if (!isset($this->options[$opt]) || is_array($this->options[$opt])) {
				continue;
			}

			$this->options[$opt] = ['main' => $this->options[$opt]];
		}
	}

	public function do_listings()
	{
		/**
		 * Meta conversion.
		 */
		$pos = $this->options['predefined_style'] !== 'classic' ? 'below' : 'above';
		if (isset($this->options['meta_listing'])) {
			$this->options["post_meta_{$pos}"] = array_keys(array_filter(
				(array) $this->options['meta_listing']
			));
		}

		if (isset($this->options['meta_listing_widgets'])) {
			$this->options["loop_small_meta_{$pos}"] = array_keys(array_filter(
				(array) $this->options['meta_listing_widgets']
			));	
		}

		/**
		 * Archive templates conversion.
		 */
		$map = [
			'default_cat_template'  => 'category_loop',
			'author_loop_template'  => 'author_loop',
			'archive_loop_template' => 'archive_loop',
		];
		
		foreach ($map as $opt => $new) {
			if (empty($this->options[ $opt ])) {
				continue;
			}
		
			$this->options[ $new ] = $this->get_loop_template($this->options[ $opt ]);
		}
		
		/**
		 * Read more.
		 * It has changed defaults (previously default enabled for list and large).
		 */
		if (!isset($this->options['read_more']) || $this->options['read_more']) {
			if (!isset($this->options['read_more_alt']) || $this->options['read_more_alt']) {
				$this->options['loop_list_read_more'] = 'btn-b';
			}
			
			$this->options['loop_large_read_more'] = 'btn-b';
			unset(
				$this->options['read_more'], 
				$this->options['read_more_alt']
			);
		}
		
		// Category labels position.
		$this->options['cat_labels_pos'] = 'top-left';

		// Reviews.
		if (isset($this->options['review_show']) && !$this->options['review_show']) {
			$this->options['loops_reviews'] = 'none';
		}
		else {
			$this->options['loops_reviews'] = str_replace(
				'bar', 
				'bars', 
				!empty($this->options['review_style']) ? $this->options['review_style'] : 'bar'
			);
		}

		if (isset($this->options['review_show_widgets']) && !$this->options['review_show_widgets']) {
			$this->options['loop_small_reviews'] = 'none';
		}

		$this->rename_options([
			'excerpt_length_modern'  => 'loop_grid_excerpt_length',
			'show_excerpts_classic'  => 'loop_large_excerpts',
			'excerpt_length_classic' => 'loop_large_excerpt_length',
			'excerpt_length_alt'     => 'loop_list_excerpt_length',
		]);
	}

	/**
	 * Migrate old CSS to Additional CSS.
	 */
	public function do_custom_css()
	{
		// Migrate Custom CSS to native "Additional CSS".
		if (isset($this->options['css_custom'])) {
			$css      = $this->options['css_custom'];
			$existing = wp_get_custom_css();

			if (!empty($existing)) {
				$css = $existing . $css;
			}

			$update = wp_update_custom_css_post($css);
			if (!is_wp_error($update)) {
				unset($this->options['css_custom']);
			}
		}

		unset($this->options['css_custom_output']);
	}

	public function do_dark_skin()
	{
		$this->do_classic_skin();

		$this->options = array_replace($this->options, [
			'color_scheme' => 'dark',
			'predefined_style' => 'classic'
		]);
	}

	public function do_light_skin()
	{
		$this->do_classic_skin();

		$this->options = array_replace($this->options, [
			'header_scheme_bot' => 'light',
			'css_header_bg_bot' => '#f2f2f2',
			'css_header_bg_sd_bot' => '#222',
			'css_bhead_color_g' => '#111',
			'css_bhead_color_sd_g' => '#fff',
			'css_bhead_bg_g'    => '#f2f2f2',
			'css_bhead_bg_sd_g' => '#222',
			'footer_scheme'     => 'light',

			'predefined_style'  => 'classic',
		]);
	}

	public function do_thezine_skin()
	{
		$presets = include get_template_directory() . '/admin/options/header/presets-data.php';
		$this->options = array_replace(
			$this->options, 
			$presets['zine'],
			$this->_zine_shared_data()
		);
	}

	private function _zine_shared_data()
	{
		return [
			'css_header_mob_border_top_mid' => '3',

			'loops_reviews'                => 'bars',
			'loop_grid_media_ratio'        => 'custom',
			'loop_grid_media_ratio_custom' => '1.68',
			
			'loop_list_media_ratio'        => 'custom',
			'loop_list_media_ratio_custom' => '1.575',
			'css_loop_list_media_max_width'=> [
				'main' => 44,
				'medium' => '',
				'small'  => '',
				'limit'  => 0
			],
			'loop_list_media_width'        => 44,
			'css_loop_small_media_max_width' => [
				'main' => 104,
				'medium' => '',
				'small'  => '',
				'limit'  => 0
			],
			'loop_small_media_ratio'       => '3-2',
			'cat_labels_pos'               => 'top-left',
			'feat_grids_meta_below'        => ['date'],
		];
	}

	public function get_main_color()
	{
		if (!empty($this->options['css_main_color'])) {
			return $this->options['css_main_color'];
		}
		
		$color = '';
		switch ($this->options['predefined_style']) {
			case 'thezine':
				$color = '#2ab391';
				break;

			case 'tech':
				$color = '#2196f3';
				break;

			case 'dark':
				$color = '#a02020';
				break;

			// Classic, trendy, light.
			default:
				$color = '#e54e53';
				break;
		}

		return $color;
	}

	public function do_trendy_skin()
	{
		$presets = include get_template_directory() . '/admin/options/header/presets-data.php';
		$this->options = array_replace(
			$this->options,
			$presets['trendy'],
			$this->_zine_shared_data(),
			[
				'post_meta_below'  => ['author', 'date'],
				'block_head_style' => 'd',
			]
		);
	}

	public function do_tech_skin()
	{
		$this->options = array_replace($this->options, [
			'css_header_height_mid' => '146',
			'css_font_text' => 'Open Sans',
			'css_font_secondary' => 'Open Sans',
			'css_font_post_titles_family' => 'Roboto',

			'loop_grid_media_ratio' => 'custom',
			'loop_grid_media_ratio_custom' => '1.588'
		]);
	}

	public function do_classic_skin()
	{
		$this->options = array_replace($this->options, [
			'css_header_mob_border_top_mid' => '3',
			'cat_labels_pos'   => 'top-left',
			'post_meta_above'  => ['date', 'comments'],
			'post_meta_below'  => [],
			'block_head_style' => 'a2',
			'css_loop_small_media_max_width' => [
				'main' => 75,
				'medium' => '',
				'small'  => '',
				'limit'  => 0
			],
			'loop_small_media_ratio' => 'custom',
			'loop_small_media_ratio_custom' => '1.149',
			'loop_small_meta_above' => ['date'],
			'loop_small_meta_below' => [],

			'loop_grid_media_ratio' => 'custom',
			'loop_grid_media_ratio_custom' => '1.88',

			'loop_list_media_ratio'        => 'custom',
			'loop_list_media_ratio_custom' => '1.88',
			'css_loop_list_media_max_width'=> [
				'main' => 40,
				'medium' => '',
				'small'  => '',
				'limit'  => 0
			],
			'loop_list_media_width'        => 40,

			'post_layout_template'  => 'classic',
			'related_posts_meta_below' => [],
			'single_section_head_style' => 'a2',
			'breadcrumbs_style' => 'b',
			'breadcrumbs_width' => 'wrap',
			'breadcrumbs_add_label' => 1,
		]);
	}
}