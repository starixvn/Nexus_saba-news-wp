<?php
/**
 * Color Control modified to include the base
 * 
 * @see WP_Customize_Color_Control
 */
class Bunyad_Customizer_Controls_Color extends WP_Customize_Color_Control
{	
	use Bunyad_Customizer_Controls_BaseTrait;

	public $type = 'bunyad-color';

	/**
	 * @inheritDoc
	 */
	public function to_json() 
	{
		$this->base_json();

		// Recall for correct order 
		// parent::to_json();	
	}

	public function content_template() 
	{
		?>
		<# 
		var isChecked = true;
		var defaultValue = '#RRGGBB', defaultValueAttr = '',
			isHueSlider = data.mode === 'hue';
		if ( data.defaultValue && _.isString( data.defaultValue ) && ! isHueSlider ) {
			if ( '#' !== data.defaultValue.substring( 0, 1 ) ) {
				defaultValue = '#' + data.defaultValue;
			} else {
				defaultValue = data.defaultValue;
			}
			defaultValueAttr = ' data-default-color=' + defaultValue; // Quotes added automatically.

		} #>

		<div class="control-wrap">
			<# if ( data.label ) { #>
				<span class="customize-control-title">{{{ data.label }}}</span>
			<# } #>
			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>
			<div class="customize-control-content">
				<label>
					<span class="screen-reader-text">{{{ data.label }}}</span>
					<# if ( isHueSlider ) { #>
						<input class="color-picker-hue" type="text" data-type="hue" />
					<# } else { #>
						<input class="color-picker-hex" type="text" maxlength="7" placeholder="{{ defaultValue }}" {{ defaultValueAttr }} />
					<# } #>
				</label>
				<div class="use-main-color">
					<label>
						<input type="checkbox" value="1" />
						<?php echo esc_html('Use main color', 'bunyad-admin'); ?>
					</label>
				</div>
			</div>
		</div>
		<?php
	}
}
