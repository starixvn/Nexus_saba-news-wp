<?php

namespace Bunyad\Blocks\Loops;

use \Bunyad;
use Bunyad\Blocks\Base\LoopBlock;

/**
 * Highlights Block
 */
class Highlights extends LoopBlock
{
	public $id = 'highlights';

	/**
	 * Extend base props
	 */
	public static function get_default_props()
	{
		$props = parent::get_default_props();

		return array_replace($props, [
			'small_style' => 'a'
		]);
	}

	public function get_block_wrap_attribs()
	{
		$attribs = parent::get_block_wrap_attribs();
		$attribs['data-is-mixed'] = 1;

		return $attribs;
	}

	/**
	 * @inheritDoc
	 */
	public function _pre_render()
	{
		// Auto-detect columns.
		if (!$this->props['columns']) {
			$this->props['columns'] = $this->get_relative_width() >= 66 ? 2 : 1;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function infer_image_sizes()
	{
	}
}