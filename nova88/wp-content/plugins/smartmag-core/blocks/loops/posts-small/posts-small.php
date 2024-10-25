<?php

namespace Bunyad\Blocks\Loops;

use \Bunyad;
use Bunyad\Blocks\Base\LoopBlock;
use Bunyad\Blocks\Base\CarouselTrait;

/**
 * Small list Block
 */
class PostsSmall extends LoopBlock
{
	use CarouselTrait;

	public $id = 'posts-small';

	/**
	 * Extend base props
	 */
	public static function get_default_props()
	{
		$props = parent::get_default_props();

		return array_replace($props, self::get_carousel_props(), [
			'cat_labels'  => false,
			'media_pos'   => 'left',
			'separators'  => true,
			'title_tag'   => 'h4',
			'show_post_formats' => false
		]);
	}

	public function map_global_props($props)
	{	
		$global = [
			'media_ratio'           => Bunyad::options()->loop_small_media_ratio,
			'media_ratio_custom'    => Bunyad::options()->loop_small_media_ratio_custom,
			'media_width'           => Bunyad::options()->loop_small_media_width,
			'reviews'               => Bunyad::options()->loop_small_reviews,
			'excerpts'              => false,
			'excerpt_length'        => 0,
			'read_more'             => '',

			// Setting here or parent method will set it to global.
			'cat_labels'     => false,
		];

		// Only add default global meta if specifically enabled. Otherwise empty meta
		// won't be possible (call props get stripped being default).
		$props['meta_items_default'] = $props['meta_items_default'] ?? true;
		if ($props['meta_items_default']) {
			$global += [
				'meta_above'  => Bunyad::options()->loop_small_meta_above,
				'meta_below'  => Bunyad::options()->loop_small_meta_below,
			];
		}

		// Only set this default for listings/internal calls.
		if (empty($props['is_sc_call'])) {
			$global += [
				'separators'        => Bunyad::options()->loop_small_separators,
				'show_post_formats' => Bunyad::options()->loop_small_post_formats,
			];
		}

		$props = array_replace($global, $props);
		$props = parent::map_global_props($props);

		return $props;
	}

	/**
	 * @inheritDoc
	 */
	public function _pre_render()
	{
		/**
		 * Setup columns and rows
		 */
		// Columns set to auto, auto-detect.
		if (!$this->props['columns']) {
			$this->props['columns'] = 1;
		}

		// Unless specified, deduct column count by 1 for responsive. Max 3 cols for 
		// medium and 2 for small.
		$cols = $this->props['columns'] > 1 ? $this->props['columns'] - 1 : 1;
		$this->setup_columns([
			'medium' => min($cols, 3),
			'small'  => min($cols, 2),

			// Set only if small isn't manually set by user.
			'xsmall' => !$this->props['columns_small'] ? 1 : ''
		]);

		$this->setup_carousel();

		// Separators aren't needed with carousel.
		if ($this->props['carousel']) {
			$this->props['separators'] = false;
		}

		/**
		 * Finally, setup wrapper attributes if not already done.
		 */
		$this->props['wrap_attrs'] += [
			'class' => array_merge(
				[
					'loop loop-small', 
					'loop-small-' . $this->props['style'],
					$this->props['separators'] ? 'loop-sep loop-small-sep' : '',
					$this->props['separators_cols'] ? 'loop-sep-col' : '',
				],
				$this->props['class'],
				$this->props['class_grid']
			)
		];
	}

	public function infer_image_sizes()
	{
		if (!empty($this->props['media_width'])) {
			// 28% is original expected width.
			$this->props['image_props']['scale'] = intval($this->props['media_width']) / 28;
		}
	}

}