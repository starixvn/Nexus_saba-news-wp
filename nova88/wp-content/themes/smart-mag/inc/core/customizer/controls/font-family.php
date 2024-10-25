<?php
/**
 * Base class for our custom controls
 */
class Bunyad_Customizer_Controls_FontFamily extends Bunyad_Customizer_Controls_Select {
	
	/**
	 * @var string Type of control
	 */
	public $type = 'bunyad-font-family';
	public $choices = [];

	/**
	 * Add global fonts group.
	 */
	public $add_global = true;


	public function to_json()
	{
		parent::to_json();
		$this->json['choices'] = $this->choices;
		$this->json['add_global'] = $this->add_global;
	}

	/**
	 * @inheritDoc
	 */
	public function enqueue()
	{
		wp_enqueue_script(
			'bunyad-customize-selectbox', 
			get_template_directory_uri() . '/inc/core/assets/js/selectize.js',
			array('jquery'),
			Bunyad::options()->get_config('theme_version')
		);

		wp_enqueue_style(
			'bunyad-customize-selectbox', 
			get_template_directory_uri() . '/inc/core/assets/css/selectize.css', 
			[], 
			Bunyad::options()->get_config('theme_version')
		);


		/**
		 * Register google fonts.
		 */

		// enqueue() is run everytime. Make sure we only add it once.
		if (Bunyad::registry()->customizer_fonts_registered) {
			return;
		}

		ob_start();
		include get_theme_file_path('inc/core/customizer/google-fonts.json');
		$google_fonts = ob_get_clean();
		$google_fonts = json_decode($google_fonts, true);

		$global_fonts = [];
		$configs = (array) Bunyad::options()->get_config('customizer');
		if (!empty($configs['font_aliases'])) {
			$global_fonts = [
				[
					'label' => esc_html('Primary Font', 'bunyad-admin'),
					'value' => '_primary',
					'group' => 'global'
				],
				[
					'label' => esc_html('Secondary Font', 'bunyad-admin'),
					'value' => '_secondary',
					'group' => 'global'
				],
				[
					'label' => esc_html('Tertiary Font', 'bunyad-admin'),
					'value' => '_tertiary',
					'group' => 'global'
				],
			];
		}

		$fonts = [
			'google' => $google_fonts,
			'global' => $global_fonts,
			'system' => [
				[
					'label' => esc_html('Sans-Serif Stack', 'bunyad-admin'),
					'value' => 'sans-serif',
					'group' => 'system'
				],
				[
					'label' => esc_html('Serif Stack', 'bunyad-admin'),
					'value' => 'serif',
					'group' => 'system'
				],
				[
					'label' => esc_html('Monospace Stack', 'bunyad-admin'),
					'value' => 'monospace',
					'group' => 'system'
				],
				[
					'label' => esc_html('Helvetica', 'bunyad-admin'),
					'value' => 'helvetica',
					'group' => 'system'
				],
				[
					'label' => esc_html('Georgia', 'bunyad-admin'),
					'value' => 'georgia',
					'group' => 'system'
				],
			]
		];

		$fonts = apply_filters('bunyad_customizer_fonts', $fonts);

		// Add Google Fonts to the JS
		wp_localize_script('bunyad-customizer-controls', 'Bunyad_Fonts_List', $fonts);
		Bunyad::registry()->customizer_fonts_registered = 1;
	}
}