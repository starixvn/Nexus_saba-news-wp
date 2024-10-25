<?php 
/**
 * Block view called by a block object
 * 
 * @see Bunyad\Blocks\Base\LoopBlock::get_default_props()
 * @see Bunyad\Blocks\Base\LoopBlock::render()
 * 
 * @var Bunyad\Blocks\Base\LoopBlock $block
 * 
 * @version 4.0.0
 */

$props = $block->get_props();
$grid_posts = $highlights = 1;

?>
	
	<div class="grid grid-2 sm:grid-1 cols-not-eq loops-mixed">

		<?php

		// Uses: loop-grid
		Bunyad::blocks()->load('Loops\Grid', array_replace($props, [
			'posts'      => $grid_posts,
			'query'      => $query,
			'pagination_render' => false
		]))
		->render(['block_markup' => false]);

		// Grid loop with small style.
		Bunyad::blocks()->load('Loops\Grid',
			[
				'posts'      => $props['posts'],
				'skip_posts' => $grid_posts,
				'query'      => $query,
				'columns'    => $props['small_cols'],
				'columns_small' => 2,
				'style'      => 'sm',
				'excerpts'   => false,
				'cat_labels' => false,
				'title_lines'    => $props['title_lines'],
				'title_tag'      => $props['title_tag'],
				'meta_items_default' => false,
				'meta_above'  => [],
				'meta_below'  => ['date'],
				'pagination'        => $props['pagination'],
				'pagination_type'   => $props['pagination_type'],
				'pagination_render' => false
			]
		)
		->render(['block_markup' => false]);

		?>
	</div>

	<?php
		// Pagination from partials/pagination.php
		$block->the_pagination();
	?>
