<?php
/**
 * Modified WP_Customize_Upload_Control class to allow handling of images that are not
 * available in the local library.
 * 
 * Use Case: Imported options where images set may not be in local library.
 */

/**
 * Customize Upload Control Class.
 *
 * @since 3.4.0
 *
 * @see WP_Customize_Media_Control
 * @see WP_Customize_Upload_Control
 * @see WP_Customize_Image_Control
 */
class Bunyad_Customizer_Controls_Media extends WP_Customize_Media_Control 
{	
	use Bunyad_Customizer_Controls_BaseTrait;
	
	public function to_json() 
	{
		$this->base_json();
	}
}
