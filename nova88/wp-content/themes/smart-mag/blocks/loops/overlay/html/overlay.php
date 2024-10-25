<?php 
/**
 * Block view called by a block object
 * 
 * CLASS: blocks/loops/overlay/overlay.php
 * 
 * @see Bunyad\Blocks\Base\LoopBlock::get_default_props()
 * @see Bunyad\Blocks\Base\LoopBlock::render()
 * 
 * @var Bunyad\Blocks\Loops\Overlay  $block
 */

$props = $block->get_props();

?>
	
	<div <?php Bunyad::markup()->attribs('loop-overlay', $props['wrap_attrs']); ?>>

		<?php while ($query->have_posts()): $query->the_post(); ?>
	
			<?php
				// Uses partials/loop-posts/post.php (or overlay.php if created)
				echo $block->loop_post('overlay');
			?>

		<?php endwhile; ?>

	</div>

	<?php
		// Pagination from partials/pagination.php
		$block->the_pagination();
	?>
	
