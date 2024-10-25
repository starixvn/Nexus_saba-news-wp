<?php
/**
 * Block view called by a block object
 * 
 * @see Bunyad\Blocks\Loops\FeatGrid::get_default_props()
 * @see Bunyad\Blocks\Base\LoopBlock::render()
 * 
 * @var Bunyad\Blocks\Loops\FeatGrid $block
 * 
 * @version 4.0.0
 */

$props = $block->get_props();

?>
	
<section <?php Bunyad::markup()->attribs('feat-grid-wrap', ['class' => $props['grid_wrap_classes']]); ?>>

	<div <?php Bunyad::markup()->attribs('slider-slides', $props['slide_attrs']); ?>>
	
	<?php 
		// Loop through to display all the required slides.
		$count = 1;
		$i = 0;

		while ($count <= $props['slides']): 
			$count++; 
	?>

		<?php if (!$props['equal_items']): // Items wrap is only added to non-equal item grids. ?>
			<div class="items-wrap slide-wrap">
		<?php endif; ?>
			
			<?php 
			while ($query->have_posts()): 
				$query->the_post(); 
				$i++;

				$post_props = $block->get_post_props($i);
			?>
				
				<div class="<?php echo esc_attr(join(' ', $post_props['item_wrap_class'])); ?>">

					<?php 
						$block->loop_post(
							'feat-grid',
							$post_props
						)->render(); 
					?>

				</div>
							
			<?php 
				// Items per slide.
				if (($i % $props['per_slide']) === 0) {

					// Reset counters for non-equals.
					if (!$props['equal_items']) {
						$i = 0;
					}

					// Run it once before ending the loop so loop_end action can run.
					$query->have_posts();
					break;
				}

			endwhile;
			?>
				
		<?php if (!$props['equal_items']): ?>
			</div>
		<?php endif; ?>

	<?php endwhile; ?>
	</div>

</section>
