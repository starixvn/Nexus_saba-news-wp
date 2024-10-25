<?php 
/**
 * Block view called by a block object
 * 
 * CLASS: blocks/loops/posts-small/posts-small.php
 * 
 * @see Bunyad\Blocks\Base\LoopBlock::get_default_props()
 * @see Bunyad\Blocks\Base\LoopBlock::render()
 * 
 * @var Bunyad\Blocks\Loops\PostsSmall $block
 */

$props = $block->get_props();

?>

	<div <?php Bunyad::markup()->attribs('loop-small', $props['wrap_attrs']); ?>>

		<?php while ($query->have_posts()): $query->the_post(); ?>
			<?php
				// Renders blocks/loop-posts/html/post.php (or small-post.php), via blocks/loop-posts/small-post.php render().
				echo $block->loop_post('small');
			?>
	
		<?php endwhile; ?>

	</div>

	<?php
		// Pagination from partials/pagination.php
		$block->the_pagination();
	?>
	