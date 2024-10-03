<?php

namespace Bunyad\Blocks\Loops;

use \Bunyad;
use Bunyad\Blocks\Base\LoopBlock;

/**
 * News Focus Block
 */
class NewsFocus extends LoopBlock
{
	public $id = 'news-focus';

	/**
	 * Extend base props
	 */
	public static function get_default_props()
	{
		$props = parent::get_default_props();

		return array_merge($props, array(
			'posts'       => 5,
			'highlights'  => 1,
			'small_style' => 'a',
		));
	}

	public function init()
	{
		parent::init();

		$this->props += [
			'class_contain'  => [
				'grid cols-not-eq loops-mixed'
			]
		];
	}

	public function get_block_wrap_attribs()
	{
		$attribs = parent::get_block_wrap_attribs();
		$attribs['data-is-mixed'] = 1;

		return $attribs;
	}

	public function _pre_render()
	{
		/**
		 * Setup columns and rows
		 */
		$width = $this->get_relative_width();

		// Columns set to auto, auto-detect.
		if (!$this->props['columns']) {
			$this->props['columns'] = 2;

			if ($width < 60) {
				$this->props['columns'] = 2;
			}
			else if ($width > 70) {
				$this->props['columns'] = 3;
			}
		}

		$total_columns = $this->props['columns'];

		// Set row gap.
		switch ($total_columns) {

			case 4:
				// $this->props['class_contain'][] = 'grid-5-7';
				array_push(
					$this->props['class_contain'], 'grid-4-8', 'sm:grid-1'
				);
				break;			

			default:
				array_push(
					$this->props['class_contain'], 'grid-2', 'md:grid-2', 'sm:grid-1'
				);
				break;

		}

		// For Main Grid Post: This affects the main grid only.
		$this->props['columns'] = 1;
		$this->setup_columns([
			'medium'  => 1,
			'xsmall'  => 1
		]);

		// Since news-focus always use 1 column grid, but in reality it's 2 columns, adjust.
		if ($this->props['container_width']) {
			$this->props['container_width'] *= 0.5;
		}

		// Determine small columns.
		$this->props['small_cols'] = max(1, $total_columns - 1);

	}

	public function infer_image_sizes()
	{
		// $this->props['image'] = 'bunyad-grid';
	}
}