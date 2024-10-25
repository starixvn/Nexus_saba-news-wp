<?php 
/**
 * Partial Template for Single Post classic layout - called from single.php
 */

$props = array_replace([
	'post_classes' => [],
], $props);

?>
	<div class="ts-row">
		<div class="col-8 main-content">		
			<div <?php Bunyad::markup()->attribs('the-post-wrap', ['class' => $post_classes]); ?>>

				<?php get_template_part('content', 'single'); ?>
					
				<div class="comments">
					<?php comments_template('', true); ?>
				</div>
	
			</div>
		</div>
		
		<?php Bunyad::core()->theme_sidebar(); ?>
	</div>