<?php

namespace Bunyad\Blocks\Loops;

use \Bunyad;
use Bunyad\Blocks\Base\LoopBlock;

/**
 * News Focus Block
 */
class FocusGrid extends LoopBlock
{
	public $id = 'focus-grid';

	public function init()
	{
		parent::init();
		$this->props += [
			'small_cols' => 2
		];
	}

	/**
	 * Extend base props
	 */
	public static function get_default_props()
	{
		$props = parent::get_default_props();

		return array_merge($props, [
			'posts'      => 5,
			'highlights' => 1,
			'column_gap' => 'sm',
		]);
	}

	public function get_block_wrap_attribs()
	{
		$attribs = parent::get_block_wrap_attribs();
		$attribs['data-is-mixed'] = 1;

		return $attribs;
	}

	public function _pre_render()
	{
		// Default to 3 columns.
		if (!$this->props['columns']) {
			$this->props['columns'] = 3;
		}

		$total_columns = $this->props['columns'];

		// For Main Grid Post: This affects the main grid only.
		$this->props['columns'] = 1;
		$this->setup_columns([
			'medium'  => 1,
			'xsmall'  => 1
		]);

		$this->props['small_cols'] = max(1, $total_columns - 1);
	}

	/**
	 * @inheritDoc
	 */
	public function infer_image_sizes() 
	{
		$this->props['image'] = 'bunyad-grid';
	}
}