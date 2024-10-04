<?php

namespace Bunyad\Blocks;

use \Bunyad;
use Bunyad\Blocks\Base\Block;

/**
 * Ads and Custom Code block.
 */
class Codes extends Block
{
	public $id = 'codes';

	/**
	 * @inheritdoc
	 */
	public static function get_default_props() 
	{
		$props = [
			'label'    => '',
			'code'     => '',
			'code_amp' => '',
		];

		return $props;
	}

	/**
	 * Render all of the post meta HTML.
	 * 
	 * @return void
	 */
	public function render() 
	{	
		$label = '';
		if ($this->props['label']) {
			$label = sprintf('<div class="label">%s</div>', esc_html($this->props['label']));
		}

		$wrap = sprintf(
			'<div class="a-wrap">%1$s %2$s</div>',
			$label,
			// This code is saved by valid priveleged users.
			Bunyad::amp()->active() ? $this->props['code_amp'] : $this->props['code']
		);

		$wrap = str_replace('<img', '<img loading="lazy"', $wrap);
		echo do_shortcode($wrap);
	}
}