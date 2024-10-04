<?php

namespace Bunyad\Blocks\Loops;

use Bunyad;
use Bunyad\Blocks\Base\LoopBlock;
use function Bunyad\Util\filter_empty_strings;

/**
 * Posts List Block
 */
class PostsList extends LoopBlock
{
	public $id = 'posts-list';

	/**
	 * Extend base props
	 */
	public static function get_default_props()
	{
		$props = parent::get_default_props();

		return array_merge($props, [
			'media_pos'   => 'left',
			'media_vpos'  => '',
			'grid_on_sm'  => false,
			'separators'  => true,
			'content_vcenter' => false,
		]);
	}

	public function map_global_props($props)
	{
		$global = [
			'media_ratio'        => Bunyad::options()->loop_list_media_ratio,
			'media_ratio_custom' => Bunyad::options()->loop_list_media_ratio_custom,
			'media_width'        => Bunyad::options()->loop_list_media_width,
			'read_more'          => Bunyad::options()->loop_list_read_more,
		];

		// Only add these globals for listings.
		if (empty($props['is_sc_call'])) {
			$global += [
				'excerpts'       => Bunyad::options()->loop_list_excerpts,
				'excerpt_length' => Bunyad::options()->loop_list_excerpt_length,
				'read_more'      => Bunyad::options()->loop_list_read_more,
				'separators'     => Bunyad::options()->loop_list_separators,
				'grid_on_sm'     => Bunyad::options()->loop_list_grid_on_sm,
				'style'          => Bunyad::options()->loop_list_style,
			];
		}

		// Extras that are only added if not empty (assumed global otherwise).
		$global_extras = filter_empty_strings([
			'cat_labels'     => Bunyad::options()->loop_list_cat_labels,
			'cat_labels_pos' => Bunyad::options()->loop_list_cat_labels_pos,
		]);

		$props = array_replace($global, $global_extras, $props);
		$props = parent::map_global_props($props);

		return $props;
	}

	/**
	 * @inheritDoc
	 */
	public function init()
	{
		parent::init();
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

		$this->setup_columns([
			// For 2 col variation, use 2 on medium too.
			'medium' => $this->props['columns'] > 1 ? 2 : 1,
			'small'  => 1,
		]);

		/**
		 * Finally, setup wrapper attributes if not already done.
		 */
		$this->props['wrap_attrs'] += [
			'class' => array_merge(
				[
					'loop loop-list', 
					$this->props['separators'] ? 'loop-sep loop-list-sep' : '',
					$this->props['separators_cols'] ? 'loop-sep-col' : '',
					$this->props['style'] ? "loop-list-{$this->props['style']}" : '',
				],
				$this->props['class'],
				$this->props['class_grid']
			)
		];

		// Infinite load - backward compat?
		if (Bunyad::options()->pagination_type == 'infinite') {
			$this->props['wrap_attrs']['data-infinite'] = Bunyad::markup()->unique_id('listing-'); 
		}
	}

	/**
	 * @inheritDoc
	 */
	public function infer_image_sizes() 
	{
		// If an image is forced, don't bother setting it
		if ($this->props['image']) {
			// return;
		}		

		$image        = 'bunyad-list';
		$column_width = $this->get_relative_width() / $this->props['columns'];
		$scale        = 1;

		// Use a large image if within wide container. So for full width, 375px image will have 
		// sizes attr set to 375px * (100/66)
		if ($column_width > 66) {
			$scale = $column_width / 66;
		}

		if (!empty($this->props['media_width'])) {

			// 38% is original expected width.
			$media_scale = intval($this->props['media_width']) / 38;

			// There may be column scale as well. Add that on top of it.
			// Example for full-width column:
			//  (58% / 38%) * ((375px * 100%/66%))
			$scale = $media_scale * $scale;
		}

		if ($scale !== 1) {
			$this->props['image_props']['scale'] = $scale;
		}

		$this->props['image'] = $image;
	}

}