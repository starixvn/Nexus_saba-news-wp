<?php 
/**
 * Partial Template for next/previous post navigation on single page
 */
?>

<?php if (is_single() && Bunyad::options()->single_navigation): ?>

	<section class="navigate-posts">
	
		<?php 
			previous_post_link(
				'<div class="previous">
					<span class="main-color title"><i class="tsi tsi-chevron-left"></i> ' . esc_html__('Previous Article', 'bunyad') .'</span><span class="link">%link</span>
				</div>'
			); 
		?>

		<?php 
			next_post_link(
				'<div class="next">
					<span class="main-color title">'. esc_html__('Next Article', 'bunyad') .' <i class="tsi tsi-chevron-right"></i></span><span class="link">%link</span>
				</div>'
			); 
		?>
		
	</section>

<?php endif; ?>