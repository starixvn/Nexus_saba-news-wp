<?php
/**
 * Base class for our custom controls
 * 
 * @todo Make it fully compatible with per device / responsive controls.
 */
class Bunyad_Customizer_Controls_Selectize extends Bunyad_Customizer_Controls_Select {
	
	/**
	 * @var string Type of control
	 */
	public $type     = 'bunyad-selectize';
	public $choices  = [];
	public $multiple = true;
	public $sortable = false;

	public function to_json()
	{
		parent::to_json();
		$this->json['choices']  = $this->choices;
		$this->json['multiple'] = $this->multiple;
		$this->json['sortable'] = $this->sortable;
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
	}

	/**
	 * Template for multiple devices, or a deviceless field.
	 * 
	 * Note: Selecting defaults etc. isn't really necessary as can be done in JS in the control.
	 *       But add it anyways for code compatibility with select.
	 */
	public function template_devices_multi($single = false) 
	{
		?>

		<#
			var isSingle   = <?php echo $single ? 'true' : 'false'; ?>;
			var theValue   = isSingle ? data.value : data.value[ device ];
			var currentVal = Array.isArray(theValue) ? theValue : [theValue];
			var jsonCurrent = JSON.stringify(currentVal);
			
			data.id = isSingle ? data.id : data.id + device;
			
		#>

		<select 
			<# if ( data.multiple ) { #> multiple="multiple" <# } #> 
			id="{{ data.id }}" 
			<?php echo (string) ($single ? '{{{ data.link }}}' : 'data-bunyad-cz-key="{{ device }}"'); ?>
			data-selected="{{ jsonCurrent }}">
		
			<# 
			_.each( data.choices, function( label, key ) {

				// Key/Label pair as an array - usually to preserve order as JS puts 
				// numeric keys first in objects.
				if (_.isArray(label)) {
					key   = label[0];
					label = label[1];
				}

				var selected = currentVal.indexOf(key) !== -1;

			#>
				<option <# if ( selected ) { #> selected="selected" <# } #> value="{{ key }}">
					{{{ label }}}
				</option>
			<# } ); #>

		</select>
		
		<?php
	}

	/**
	 * @inheritDoc
	 */
	public function template_devices_single() {
		$this->template_devices_multi(true);
	}
}