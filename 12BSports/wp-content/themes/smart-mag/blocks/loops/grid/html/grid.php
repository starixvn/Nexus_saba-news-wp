<?php 
/**
 * Block view called by a block object
 * 
 * @see Bunyad\Blocks\Base\LoopBlock::get_default_props()
 * @see Bunyad\Blocks\Base\LoopBlock::render()
 * 
 * @var Bunyad\Blocks\Loops\Grid $block
 * 
 * @version 4.0.0
 */

$props = $block->get_props();

?>	
	<div <?php Bunyad::markup()->attribs('loop-grid', $props['wrap_attrs']); ?>>

		<?php while ($query->have_posts()): $query->the_post(); ?>
			<?php

				// Unique Case: Using Large style + Legacy Post.
				if ($props['style'] == 'lg' && $props['large_style'] == 'legacy') {
					get_template_part('content');
					continue;
				}

				// Renders blocks/loop-posts/html/post.php (or grid-post.php), via blocks/loop-posts/grid-post.php render().
				echo $block->loop_post('grid');

			?>
		<?php endwhile; ?>

	</div>

	<?php
		// Pagination from partials/pagination.php
		$block->the_pagination();
	?>
	
