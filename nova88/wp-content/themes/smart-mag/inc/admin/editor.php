<?php
/**
 * Features and modifications for the block editor.
 */
class Bunyad_Theme_Admin_Editor
{
	public function __construct() 
	{
		// Only to be used for logged in users
		if (!current_user_can('edit_pages') && !current_user_can('edit_posts')) {
			return;
		}

		// Custom line-height and units for 5.5+
		add_theme_support('custom-line-height');
		add_theme_support('custom-units');

		add_theme_support('align-wide');

		add_theme_support('custom-spacing');
		
		// Hook at after_setup_theme for add_theme_support()
		add_action('after_setup_theme', array($this, 'setup'));
	}

	/**
	 * Setup at init hook
	 */
	public function setup()
	{
		/**
		 * Gutenberg
		 */
		if (Bunyad::options()->guten_styles) {
			add_action('enqueue_block_editor_assets', array($this, 'add_new_editor_style'));
			
			add_filter(
				// block_editor_settings_all for WP 5.8+
				class_exists('WP_Block_Editor_Context') ? 'block_editor_settings_all' : 'block_editor_settings',
				[$this, 'guten_styles'],
				11
			);
		}

		// For WordPress 5.9+ solid background is removed.
		if (version_compare($GLOBALS['wp_version'], '5.9.0', '>=')) {
			register_block_style(
				'core/pullquote',
				[
					'name'         => 'solid-bg',
					'label'        => esc_html__('Solid Background', 'bunyad-admin'),
					'inline_style' => '',
				]
			);

			// Quote, mainly in WP 6.0+
			register_block_style(
				'core/quote',
				[
					'name'         => 'large',
					'label'        => esc_html__('Large', 'bunyad-admin'),
					'inline_style' => '',
				]
			);
		}

		register_block_style(
			'core/pullquote',
			[
				'name'         => 'modern',
				'label'        => esc_html__('Modern Quote', 'bunyad-admin'),
				'inline_style' => '',
			]
		);

		// Guten editor font sizes
		add_theme_support('editor-font-sizes', array(
			array(
				'name' => esc_html__('small', 'bunyad-admin'),
				'shortName' => esc_html__('S', 'bunyad-admin'),
				'size' => 14,
				'slug' => 'small'
			),
			array(
				'name' => esc_html__('regular', 'bunyad-admin'),
				'shortName' => esc_html__('M', 'bunyad-admin'),
				'size' => 17,
				'slug' => 'regular'
			),
			array(
				'name' => esc_html__('large', 'bunyad-admin'),
				'shortName' => esc_html__('L', 'bunyad-admin'),
				'size' => 22,
				'slug' => 'large'
			),
			array(
				'name' => esc_html__('larger', 'bunyad-admin'),
				'shortName' => esc_html__('XL', 'bunyad-admin'),
				'size' => 28,
				'slug' => 'larger'
			)
		));

		add_theme_support('editor-color-palette', [
			[
				'name'  => 'Black',
				'slug'  =>  'black',
				'color' => '#000000',
			],
			[
				'name'  => 'White',
				'slug'  =>  'white',
				'color' => '#ffffff',
			],
			[
				'name'  => 'Very Light Gray',
				'slug'  =>  'very-light-gray',
				'color' => '#f7f7f7',
			],
			[
				'name'  => 'Dark Gray',
				'slug'  =>  'dark-gray',
				'color' => '#777777',
			],
			[
				'name'  => 'Pale Pink',
				'slug'  =>  'pale-pink',
				'color' => '#f78da7',
			],
			[
				'name'  => 'Vivid Red',
				'slug'  =>  'vivid-red',
				'color' => '#cf2e2e',
			],
			[
				'name'  => 'Luminous vivid orange',
				'slug'  =>  'luminous-vivid-orange',
				'color' => '#ff6900',
			],
			[
				'name'  => 'Luminous vivid amber',
				'slug'  =>  'luminous-vivid-amber',
				'color' => '#fcb900',
			],
			[
				'name'  => 'Light green cyan',
				'slug'  =>  'light-green-cyan',
				'color' => '#7bdcb5',
			],
			[
				'name'  => 'Vivid green cyan',
				'slug'  =>  'vivid-green-cyan',
				'color' => '#00d084',
			],
			[
				'name'  => 'Pale cyan blue',
				'slug'  =>  'pale-cyan-blue',
				'color' => '#8ed1fc',
			],
			[
				'name'  => 'Vivid cyan blue',
				'slug'  =>  'vivid-cyan-blue',
				'color' => '#0693e3',
			],
			[
				'name'  => 'Vivid purple',
				'slug'  =>  'vivid-purple',
				'color' => '#9b51e0',
			],
		]);

		$this->add_classic_style();
	}

	/**
	 * Add editor styles for gutenberg
	 */
	public function add_new_editor_style()
	{
		wp_enqueue_style(
			'smartmag-editor-styles', 
			get_theme_file_uri('css/admin/guten-editor.css'), 
			false, 
			Bunyad::options()->get_config('theme_version')
		);

		// Skin styles
		$skin = Bunyad::get('theme')->get_style();
		if (!empty($skin['css'])) {
			$style = get_theme_file_uri('css/admin/editor/' . $skin['css'] . '.css');
			
			wp_enqueue_style(
				'smartmag-editor-skin', 
				$style,
				false, 
				Bunyad::options()->get_config('theme_version')
			);
		}

		// Overwrite Core theme styles with empty styles - we provide these
		wp_deregister_style('wp-block-library-theme');
		wp_register_style('wp-block-library-theme', '');

		// Add Google Fonts
		wp_enqueue_style('smartmag-editor-gfonts', Bunyad::get('theme')->get_fonts_enqueue());

		// Add local fonts
		// Bunyad::get('theme')->skin_local_fonts($skin, 'smartmag-editor-skin');

		// Add Typekit Kit
		if (Bunyad::options()->typekit_id) {
			wp_enqueue_style('smartmag-editor-typekit', esc_url('https://use.typekit.net/' . Bunyad::options()->typekit_id . '.css'), [], null);
		}
		
	}

	/**
	 * Filter gutenberg settings.
	 */
	public function guten_styles($setting)
	{
		if (!empty($setting['styles'])) {

			// This is the default editor-styles.css file which isn't needed as we provide the necessary.
			if (!empty($setting['styles'][0]['css'])) {
				unset($setting['styles'][0]);
			}

			if (
				!empty($setting['styles'][1]['css']) 
				&& strstr($setting['styles'][1]['css'], 'Noto Serif')
			) {
				unset($setting['styles'][1]);
			}

			// Re-order keys to fix bug with WP 5.9 as json_encode requires a list in correct numeric order.
			$setting['styles'] = array_values($setting['styles']);
		}
		else {
			$setting['styles'] = [];
		}

		/**
		 * Add dynamic CSS to the gutenberg style renderer.
		 */
		require_once get_theme_file_path('inc/custom-css.php');
		
		if (Bunyad::get('custom_css')->has_custom_css()) {
			require_once get_theme_file_path('inc/core/customizer/css-generator.php');
			
			$render = new Bunyad_Customizer_Css_Generator;
			$render->default_fonts = [
				'primary'   => 'Public Sans',
				'secondary' => 'Public Sans',
			];
			
			$render->args = ['bunyad_custom_css' => 1];

			$css = $render->render();
			$replace = '.block-editor-block-list__layout:where(:not(blockquote))';
			$css = str_replace(
				['.entry-content', '.post-content'], 
				// Replace the front-end wrappers with the backend wrapper.
				// Ideally: [':root', ':root'],
				[$replace, $replace],
				$css
			);

			array_push($setting['styles'], ['css' => $css]);

			// Setup Google Fonts enqueue.
			$google_fonts = $render->get_google_fonts_url();
			if ($google_fonts) {
				wp_enqueue_style('smartmag-gfonts-custom', $google_fonts, array(), null);
			}
		}

		return $setting;
	}

	/**
	 * Add styling for classic editor.
	 */
	public function add_classic_style()
	{
		if (!is_admin()) {
			return;
		}
		
		// Add editor styles
		$styles = [get_stylesheet_uri()];
		$skin   = Bunyad::get('theme')->get_style();
		
		// Add skin css second
		if (isset($skin['css'])) {
			array_push($styles, get_template_directory_uri() . '/css/' . $skin['css'] . '.css');
		}
		
		$styles = array_merge($styles, [
			get_template_directory_uri() . '/css/admin/editor-style.css',
			Bunyad::get('theme')->get_fonts_enqueue()
		]);

		if (!empty($skin['local_fonts'])) {
			foreach ((array) $skin['local_fonts'] as $font) {
				$styles[] = get_theme_file_uri('css/fonts/' . $font . '.css');
			}
		}

		add_editor_style($styles);
	}

}

// init and make available in Bunyad::get('admin_editor')
Bunyad::register('admin_editor', array(
	'class' => 'Bunyad_Theme_Admin_Editor',
	'init' => true
));