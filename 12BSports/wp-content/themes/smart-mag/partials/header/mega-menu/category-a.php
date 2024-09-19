<?php 
/**
 * Mega Menu Category - Display recent posts from a parent category.
 */

// Walker to create the left part of mega menu
$sub_walker    = new Walker_Nav_Menu;
$have_sub_menu = !empty($sub_items) ? true : false;

if (!isset($sub_items)) {
	$sub_items = array();
}

?>

<div class="sub-menu mega-menu mega-menu-a wrap">

	<?php if ($have_sub_menu): ?>
	
	<div class="column sub-cats">
		
		<ol class="sub-nav">
			<?php foreach ($sub_items as $nav_item): ?>
				
				<?php 
					
					ob_start();
					
					// Simulate a simpler walk - $escaped_output is passed-by-ref
					$escaped_output = '';
					$sub_walker->start_el($escaped_output, $nav_item, 0, $args);
					$sub_walker->end_el($escaped_output, $nav_item, $args);
					
					ob_end_clean();
					
					echo $escaped_output; // phpcs:ignore WordPress.Security.EscapeOutput -- Safe markup generated via WordPress default Walker_Nav_Menu::start_el()
				?>				
			<?php endforeach; ?>
			
			<li class="menu-item view-all menu-cat-<?php echo esc_attr($item->object_id); ?>"><a href="<?php echo esc_url($item->url); ?>"><?php 
				esc_html_e('View All', 'bunyad'); ?></a></li>
		</ol>
	
	</div>
	

	<?php endif; ?>
	
	<?php
		// Add main item (view all) as default
		array_push($sub_items, $item);
		$columns = $have_sub_menu ? 4 : 5;
	?>

	<section class="column recent-posts" data-columns="<?php echo intval($columns); ?>">
		<?php foreach ($sub_items as $item): ?>
			<div class="posts" data-id="<?php echo esc_attr($item->object_id); ?>">

				<?php

				echo Bunyad::blocks()->load(
					'Loops\Grid',
					[
						'query_type'         => 'custom',
						'posts'              => $columns,
						'cat'                => $item->object_id,
						'columns'            => $columns,
						'meta_items_default' => false,
						'meta_above'         => [],
						'meta_below'         => ['date'],
						'heading_type'       => 'none',
						'excerpts'           => false,
						'style'              => 'sm',
						'space_below'        => 'none',
						'pagination'         => false,
						'cat_labels'         => false
					]
				);
				?>
			
			</div> <!-- .posts -->
		
		<?php endforeach; ?>
	</section>

</div>