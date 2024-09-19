<?php
/**
 * Migrate to version 5.3.0.
 * 
 * @var $this Bunyad_Theme_Admin_Migrations
 */
class Bunyad_Theme_Admin_Migrations_530Update extends Bunyad_Theme_Admin_Migrations_Base
{
	public function begin()
	{
		// Temp copy.
		$this->copy_option('css_share_float_width', 'css_share_float_width_old');
		$this->copy_option('css_share_float_height', 'css_share_float_height_old');

		// Incorrect option name in previous.
		$this->rename_options([
			'css_share_float_height_old' => 'css_share_float_width',
			'css_share_float_width_old'  => 'css_share_float_height'
		]);
	}
}