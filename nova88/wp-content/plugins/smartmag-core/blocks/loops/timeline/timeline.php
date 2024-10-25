<?php

namespace Bunyad\Blocks\Loops;

use \Bunyad;
use Bunyad\Blocks\Base\LoopBlock;

/**
 * Small list Block
 */
class Timeline extends LoopBlock
{
	public $id = 'timeline';

	/**
	 * Extend base props
	 */
	public static function get_default_props()
	{
		$props = parent::get_default_props();

		return array_replace($props, [
			// 'cat_labels'  => false,
			// 'media_pos'   => 'left',
			// 'separators'  => true,
			// 'title_tag'   => 'h4',
			// 'show_post_formats' => false
		]);
	}
}