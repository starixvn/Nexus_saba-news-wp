<?php
/**
 * Partial: Pagination used in multi-page content slideshows
 */

$position = (!empty($position) ? ' ' . $position : '');
?>
	<div class="post-pagination-large<?php echo esc_attr($position); ?>" data-type="<?php echo esc_attr(Bunyad::posts()->meta('content_slider')); ?>">
	
		<?php global $page, $numpages; // get multi-page numbers ?>
		<span class="info"><?php 
			printf(
				esc_html__('Showing %s of %s', 'bunyad'), 
				'<strong>' . intval($page) . '</strong>', 
				'<strong>' . intval($numpages) . '</strong>'); 
		?></span>
		
		<?php 
			wp_link_pages([
				'before'           => '<div class="links">', 
				'after'            => '</div>', 
				'link_before'      => '<span class="ts-button">',
				'next_or_number'   => 'next',
				'nextpagelink'     => esc_html__('Next', 'bunyad') . ' <i class="next tsi tsi-chevron-right"></i>',
				'previouspagelink' => '<i class="prev tsi tsi-chevron-left"></i> ' . esc_html__('Prev', 'bunyad'),
				'link_after'       => '</span>'
			]); 
		?>
	</div>
