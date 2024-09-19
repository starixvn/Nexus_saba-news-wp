<?php
/**
 * Radio Control.
 */
class Bunyad_Customizer_Controls_Radio extends Bunyad_Customizer_Controls_Base 
{
	public $type = 'bunyad-radio';
	protected $images = false;

	public function to_json()
	{
		parent::to_json();
		$this->json['choices'] = $this->choices;
	}

	/**
	 * @inheritDoc
	 */
	public function content_template()
	{
		?>

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
		?>
		<#
			var theValue = <?php echo (string) ($single ? 'data.value' : 'data.value[ device ]'); ?>;
			var inputId = _.uniqueId( 'customize-control-default-input-' );
			var hasImages  = <?php echo intval($this->images); ?>;
		#>

		<# _.each( data.choices, function( val, key ) { #>
			<span class="customize-inside-control-row">
				<#
				var value, text, image;

				if ( _.isObject( val ) ) {
					value = key;
					text  = val.label;
					image = val.image;
					var preview = val.preview;
				} else {
					value = key;
					text  = val;
				}
				#>

				<input
					id="{{ inputId + '-' + value }}"
					type="radio"
					value="{{ value }}"
					name="{{ inputId }}"
					<?php echo (string) ($single ? '{{{ data.link }}}' : 'data-bunyad-cz-key="{{ device }}"'); ?>
					<# if ( theValue === value ) { #> checked <# } #>
				>
				
				<label for="{{ inputId + '-' + value }}">
					<# if (image) { #>
						<img src="{{ image }}" alt="{{ text }}" 
							<# if (val.preview) { #>data-preview="{{ val.preview }}" <# } #> 
						/>
					<# } #>
					<span class="label-text">{{ text }}</span>
				</label>

			</span>
		<# } ); #>

		<?php
	}

	/**
	 * @inheritDoc
	 */
	public function template_devices_single() {
		$this->template_devices_multi(true);
	}
}
