<?php 
/**
 * Timeline block view called by the block class
 * 
 * CLASS: blocks/loops/timeline/timeline.php
 * 
 * @see Bunyad\Blocks\Base\LoopBlock::get_default_props()
 * @see Bunyad\Blocks\Base\LoopBlock::render()
 * 
 * @var Bunyad\Blocks\Base\LoopBlock $block
 */

$props = $block->get_props();

$months = array();
while ($query->have_posts()) {
	$query->the_post();
	
	$month = get_the_date('F, Y');
	$months[$month][] = $post;
}

wp_reset_postdata();

$attribs = array('class' => array(
	'loop loop-timeline'
));

?>
	
	<div <?php Bunyad::markup()->attribs('loop-timeline', $attribs); ?>>

	<?php foreach ($months as $month => $the_posts): ?>

		<div class="month" data-month="<?php echo esc_attr($month); ?>">
			<span class="heading"><?php echo esc_html($month); ?></span>
			
			<div class="posts">

			<?php foreach ($the_posts as $post): setup_postdata($post);	?>
			
				<article class="l-post">
				
					<time datetime="<?php echo get_the_date(DATE_W3C); ?>"><?php echo get_the_date('M d'); ?> </time>
					
					<a href="<?php the_permalink() ?>" class="post-title"><?php the_title(); ?></a>			
				
				</article>
				
			<?php endforeach; wp_reset_postdata(); ?>
			
			</div> <!-- .posts -->
			
		</div>

	<?php endforeach; ?>
			

	</div>


	<?php

		// Pagination from partials/pagination.php
		$block->the_pagination();
	?>
