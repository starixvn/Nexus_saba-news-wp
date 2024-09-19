<?php
/**
 * Migrate to version 9.0.0
 * 
 * @var $this Bunyad_Theme_Admin_Migrations
 */
class Bunyad_Theme_Admin_Migrations_900Update extends Bunyad_Theme_Admin_Migrations_Base
{
	public function begin()
	{
		$top_style = $this->options['single_share_top_style'] ?? '';
		if ($top_style === 'b3' && !empty($this->options['css_single_share_top_height'])) {
			$this->options['css_single_share_top_height'] = intval($this->options['css_single_share_top_height']) + 2;
		}
	}
}