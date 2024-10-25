<?php 
/**
 * Block view called by a block object
 * 
 * CLASS: blocks/loops/highlights/highlights.php
 * 
 * @see Bunyad\Blocks\Base\LoopBlock::get_default_props()
 * @see Bunyad\Blocks\Base\LoopBlock::render()
 * 
 * @var Bunyad\Blocks\Loops\Highlights $block
 * 
 * @version 4.0.0
 */

$props = $block->get_props();

// Number of posts in grid style.
$grid_posts  = $props['columns'];

$small_meta_props = ['meta_items_default' => true];
if ($props['small_style'] === 'b') {
	$small_meta_props = [
		'meta_items_default' => false,
		'meta_above' => [],
		'meta_below' => []
	];
}

?>
	
	<div class="loops-mixed">
		<?php
		// Uses: loop-grid
		Bunyad::blocks()->load('Loops\Grid', array_replace($props, [
			'posts'      => $grid_posts,
			'query'      => $query,
			'pagination' => false,
		]))
		->render(['block_markup' => false]);

		// Uses: loop-small
		Bunyad::blocks()->load('Loops\PostsSmall', $small_meta_props + [
			'query'          => $query,
			'pagination'     => false,
			'posts'          => $props['posts'],
			'skip_posts'     => $grid_posts,
			'columns'        => $props['columns'],
			'columns_medium' => $props['columns_medium'],
			'columns_small'  => $props['columns_small'],
			'title_lines'    => $props['title_lines'],
			// 'title_tag'      => $props['title_tag'],
			'style'          => $props['small_style'],
			'separators'     => $props['separators'],
		])
		->render(['block_markup' => false]);

		?>
	</div>


	<?php
		// Pagination from partials/pagination.php
		$block->the_pagination();
	?>
