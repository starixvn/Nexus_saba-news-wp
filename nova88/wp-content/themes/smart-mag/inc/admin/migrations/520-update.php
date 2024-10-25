<?php
/**
 * Migrate to version 5.2.0.
 * 
 * @var $this Bunyad_Theme_Admin_Migrations
 */
class Bunyad_Theme_Admin_Migrations_520Update extends Bunyad_Theme_Admin_Migrations_Base
{
	public function begin()
	{
		// Incorrect option name in previous.
		$this->rename_options([
			'css_header_hamburger_width'     => 'css_header_hamburger_height',
			'css_header_mob_hamburger_width' => 'css_header_mob_hamburger_height'
		]);
	}
}