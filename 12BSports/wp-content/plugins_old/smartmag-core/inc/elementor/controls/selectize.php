<?php
namespace Bunyad\Elementor\Controls;

/**
 * Selectize Control.
 */
class Selectize extends \Elementor\Base_Data_Control {

	/**
	 * Control type.
	 */
	public function get_type() 
	{
		return 'bunyad-selectize';
	}

	/**
	 * Register assets.
	 */
	public function enqueue() 
	{
		// Enqueue selectize only when theme is active.
		if (\Bunyad::get('theme')) {
			wp_enqueue_script(
				'bunyad-customize-selectbox', 
				get_template_directory_uri() . '/inc/core/assets/js/selectize.js',
				['jquery'],
				\Bunyad::options()->get_config('theme_version')
			);

			wp_enqueue_style(
				'bunyad-customize-selectbox', 
				get_template_directory_uri() . '/inc/core/assets/css/selectize.css', 
				[], 
				\Bunyad::options()->get_config('theme_version')
			);
		}
	}

	/**
	 * Default settings.
	 */
	protected function get_default_settings() {
		return [
			'label_block' => true,
			'options'     => [],
			'selectize_options' => [],
		];
	}

	/**
	 * Render selecitze output in the editor.
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<# if ( data.label ) {#>
				<label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<# } #>
			<div class="elementor-control-input-wrapper elementor-control-unit-5">
				<# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
				<select id="<?php echo $control_uid; ?>" class="bunyad-el-selectize" type="select2" {{ multiple }} data-setting="{{ data.name }}">
					<# _.each( data.options, function( option_title, option_value ) {
						var value = data.controlValue;
						if ( typeof value == 'string' ) {
							var selected = ( option_value === value ) ? 'selected' : '';
						} else if ( null !== value ) {
							var value = _.values( value );
							var selected = ( -1 !== value.indexOf( option_value ) ) ? 'selected' : '';
						}
						#>
					<option {{ selected }} value="{{ option_value }}">{{{ option_title }}}</option>
					<# } ); #>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}

}