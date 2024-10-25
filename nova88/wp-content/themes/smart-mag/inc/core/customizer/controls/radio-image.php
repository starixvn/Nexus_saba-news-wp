<?php
/**
 * Radio Control.
 */
class Bunyad_Customizer_Controls_RadioImage extends Bunyad_Customizer_Controls_Radio
{
	public $type = 'bunyad-radio-image';
	protected $images = true;

	/**
	 * @inheritDoc
	 */
	public function enqueue()
	{
		wp_enqueue_script(
			'bunyad-customize-tooltip', 
			get_template_directory_uri() . '/inc/core/customizer/js/dist/jquery.tooltips.js',
			['jquery'],
			Bunyad::options()->get_config('theme_version')
		);
	}
}
