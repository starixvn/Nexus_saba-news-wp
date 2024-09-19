<?php

namespace Bunyad\Blocks\Loops;

use \Bunyad;
use Bunyad\Blocks\Base\LoopBlock;
use Bunyad\Blocks\Base\CarouselTrait;

use function Bunyad\Util\filter_empty_strings;

/**
 * Grid Block
 */
class Grid extends LoopBlock
{
	use CarouselTrait;
	
	public $id = 'grid';

	/**
	 * Extend base props
	 */
	public static function get_default_props()
	{
		$props = parent::get_default_props();

		return array_replace($props, self::get_carousel_props(), [
			'columns'        => 2,
			'grid_style'     => '',
			'large_style'    => '',
			'media_location' => '',
			'media_embed'    => '',
			'numbers'        => '',
		]);
	}

	public function map_global_props($props)
	{
		$global = [
			'media_ratio'        => Bunyad::options()->loop_grid_media_ratio,
			'media_ratio_custom' => Bunyad::options()->loop_grid_media_ratio_custom,
			'read_more'          => Bunyad::options()->loop_grid_read_more,
			'content_center'     => Bunyad::options()->loop_grid_content_center,
		];

		// Only set this default for listings/internal calls.
		if (empty($props['is_sc_call'])) {
			$global += [
				'excerpts'       => Bunyad::options()->loop_grid_excerpts,
				'excerpt_length' => Bunyad::options()->loop_grid_excerpt_length,
				'style'          => Bunyad::options()->loop_grid_style,
			];
		}
		
		// Extras that are only added if not empty (assumed global otherwise).
		$global_extras = filter_empty_strings([
			'cat_labels'     => Bunyad::options()->loop_grid_cat_labels,
			'cat_labels_pos' => Bunyad::options()->loop_grid_cat_labels_pos,
		]);

		$props = array_replace($global, $global_extras, $props);
		$props = parent::map_global_props($props);
		
		return $props;
	}

	public function init()
	{
		parent::init();

		$this->props['style'] = $this->props['style'] ? $this->props['style'] : 'base';
		$this->props['class'] = array_merge($this->props['class'], [
			'loop loop-grid', 
			"loop-grid-{$this->props['style']}",
			$this->props['separators_cols'] ? 'loop-sep-col' : '',
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function _pre_render()
	{
		parent::_pre_render();

		if ($this->props['numbers']) {

			$type = $this->props['numbers'];

			// 'before title' doesn't work with limit applied due to overflow hidden.
			if ($type === 'a' && $this->props['title_lines']) {
				$type = 'b';
			}

			$this->props['class'][] = 'has-nums has-nums-' . $type;
		}

		// @compat Legacy blocks
		if (Bunyad::registry()->loop_grid) {
			$this->set('columns', Bunyad::registry()->loop_grid);
		}
		
		/**
		 * Setup columns and rows
		 */
		$width = $this->get_relative_width();

		// Columns set to auto, auto-detect.
		if (!$this->props['columns']) {
			$this->props['columns'] = 1;

			if ($width < 60) {
				$this->props['columns'] = 2;
			}
			else if ($width > 70) {
				$this->props['columns'] = 3;
			}
		}

		// Defaults and columns setup.
		$this->setup_columns([

			// 1 or 2. Max 2 on medium by default.
			'medium' => min(2, $this->props['columns']),

			// Only add xs if small columns aren't manually set.
			'xsmall' => !$this->props['columns_small'] ? 1 : '',
		]);

		// Set row gap.
		switch ($this->props['columns']) {

			case 3:
			case 4:
				// If have sidebar, fallback to smaller gap, unless defined.
				if ($width < 67 && !$this->props['column_gap']) {
					$this->props['column_gap'] = 'sm';
				}
				break;			
		}

		$this->setup_carousel();

		/**
		 * Finally, setup wrapper attributes if not already done.
		 */
		$this->props['wrap_attrs'] += [
			'class' => array_merge(
				$this->props['class'], 
				$this->props['class_grid']
			)
		];
	}

	/**
	 * @inheritDoc
	 */
	public function infer_image_sizes() 
	{
		$column_width = $this->get_relative_width() / $this->props['columns'];
		$image = 'bunyad-grid';

		if ($column_width > 40) {
			// Scale by dividing original width of bunyad-grid (31%).
			$this->props['image_props']['scale'] = $column_width / 31;
		}

		$this->props['image'] = $image;
	}

}