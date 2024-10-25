<?php 
/**
 * Block view called by a block object
 * 
 * CLASS: blocks/loops/news-focus/news-focus.php
 * 
 * @see Bunyad\Blocks\Base\LoopBlock::get_default_props()
 * @see Bunyad\Blocks\Base\LoopBlock::render()
 * 
 * @var Bunyad\Blocks\Lopos\NewsFocus $block
 */

$props = $block->get_props();

$attribs = [
	'class' => $props['class_contain']
];

// Number of posts in grid style.
$grid_posts  = $props['highlights'];

?>
	
	<div <?php Bunyad::markup()->attribs('loop-news-focus', $attribs); ?>>

		<?php
			// Uses: loop-grid
			Bunyad::blocks()->load('Loops\Grid', array_replace($props, [
				'posts'      => $grid_posts,
				'query'      => $query,
				'pagination_render' => false
			]))
			->render(['block_markup' => false]);
		?>

		<?php

		// Uses: loop-small
		Bunyad::blocks()->load('Loops\PostsSmall', 
			[
				'query'        => $query,
				'posts'        => $props['posts'],
				'skip_posts'   => $grid_posts,
				'columns'      => $props['small_cols'],
				'style'        => $props['small_style'],
				'title_lines'  => $props['title_lines'],
				// 'title_tag'    => $props['title_tag'],
				'separators'   => $props['separators'],
				'meta_items_default' => true,
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
