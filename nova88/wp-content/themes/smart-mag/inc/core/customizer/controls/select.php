<?php
/**
 * Base class for our custom controls.
 */
class Bunyad_Customizer_Controls_Select extends Bunyad_Customizer_Controls_Base {
	
	/**
	 * @var string Type of control.
	 */
	public $type = 'bunyad-select';
	public $choices = [];

	/**
	 * @var array Present array of choices.
	 */
	public $presets = [];
	public $preset;

	public function __construct($manager, $id, $args = array())
	{
		parent::__construct($manager, $id, $args);

		// Note: w- prefix added to numeric values to preserve order (JS objects have numeric first).
		// Key/value will be used from the array.
		$this->presets = [
			'font_weights' => [
				''        => esc_html('Default', 'bunyad-admin'),
				'normal'  => 'Normal',
				'bold'    => 'Bold',
				'w-200'  => [200, '200 Extra-Light'],
				'w-300'  => [300, '300 Light'],
				'w-500'  => [500, '500 Medium'],
				'w-600'  => [600, '600 Semi-bold'],
				'w-800'  => [800, '800 Extra Bold'],
				'w-900'  => [900, '900 Black'],
			],
			'font_style' => [
				''        => esc_html('Default', 'bunyad-admin'),
				'normal'  => esc_html('Normal', 'bunyad-admin'),
				'italic'  => esc_html('Italic', 'bunyad-admin'),
			],
			'font_transform' => [
				''           => esc_html('Default', 'bunyad-admin'),
				'initial'    => esc_html('Normal', 'bunyad-admin'),
				'uppercase'  => esc_html('Uppercase', 'bunyad-admin'),
				'lowercase'  => esc_html('Lowercase', 'bunyad-admin'),
				'capitalize' => esc_html('Capitalize', 'bunyad-admin'),
			]
		];
	}

	public function to_json()
	{
		parent::to_json();

		// Custom added values are possible in selectize. They have to be added to the list.
		$value = $this->json['value'];
		if (is_string($value) && !isset($this->choices[$value])) {
			$this->choices[$value] = $value;
		}

		$this->json['choices'] = $this->choices;
		$this->json['preset']  = $this->preset;
	}

	/**
	 * @inheritDoc
	 */
	public function content_template()
	{
		?>

		<?php if ($this->type == 'bunyad-select'): // Ignore for sub-classes ?>
		<#
			var presets = <?php echo json_encode($this->presets); ?>;

			if (data.preset && presets[ data.preset ]) {
				data.choices = presets[ data.preset ];
			}
		#>
		<?php endif; ?>

		<?php $this->template_before(); ?>
		
		<?php $this->template_heading(); ?>

		<div class="customize-control-content">

			<?php $this->template_devices(); ?>

		</div>

		<?php $this->template_after(); ?>

		<?php
	}

	/**
	 * @inheritDoc
	 */
	public function template_devices_multi($single = false) 
	{
		$value_check = $single ? 'data.value' : 'data.value[ device ]';

		?>

		<#
			var theValue = <?php echo (string) ($single ? 'data.value' : 'data.value[ device ]'); ?>;
			if (typeof theValue !== 'string') {
				theValue = '';
			}

			data.id = <?php echo (string) ($single ? 'data.id' : 'data.id + device'); ?>;
		#>

		<select id="{{ data.id }}" <?php echo (string) ($single ? '{{{ data.link }}}' : 'data-bunyad-cz-key="{{ device }}"'); ?> data-selected="{{ theValue }}">
		
			<# 
			_.each( data.choices, function( label, key ) {

				// Key/Label pair as an array - usually to preserve order as JS puts 
				// numeric keys first in objects.
				if (_.isArray(label)) {
					key   = label[0];
					label = label[1];
				}

			#>
				<option <# if ( <?php echo esc_js($value_check); ?> == key ) { #> selected="selected" <# } #> value="{{ key }}">{{{ label }}}</option>
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