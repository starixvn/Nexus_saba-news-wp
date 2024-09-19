<?php
/**
 * Mega Menu Category B - Display sub cats, a featured and recent posts.
 */
?>

<div class="sub-menu mega-menu mega-menu-b wrap">

	<div class="sub-cats">
		
		<ol class="sub-nav">
			<?php echo $sub_menu; // phpcs:ignore WordPress.Security.EscapeOutput -- Safe markup generated via WordPress default Walker_Nav_Menu ?>
		</ol>

	</div>

	<div class="extend ts-row">
		<section class="col-6 featured">		
			<span class="heading"><?php echo esc_html_x('Featured', 'Categories Mega Menu', 'bunyad'); ?></span>

			<?php 
				$query = new WP_Query(apply_filters(
					'bunyad_mega_menu_query_args', 
					[
						'cat'                 => $item->object_id, 
						'meta_key'            => '_bunyad_featured_post', 
						'meta_value'          => 1, 
						'order'               => 'date', 
						'posts_per_page'      => 1, 
						'ignore_sticky_posts' => 1
					],
					'category-featured'
				));

				// Fallback to latest post.
				if (!$query->have_posts()) {
					$query = new WP_Query([
						'cat'                 => $item->object_id,
						'posts_per_page'      => 1,
						'ignore_sticky_posts' => 1
					]);
				}

				echo Bunyad::blocks()->load(
					'Loops\Grid',
					[
						'query'              => $query,
						'posts'              => 1,
						'columns'            => 1,
						// 'meta_items_default' => false,
						// 'meta_above'         => [],
						// 'meta_below'         => ['date'],
						'heading_type'       => 'none',
						'excerpts'           => false,
						'style'              => 'sm',
						'space_below'        => 'none',
						'pagination'         => false,
						'cat_labels'         => false
					]
				);
			?>

		</section>  

		<section class="col-6 recent-posts">

			<span class="heading"><?php echo esc_html_x('Recent', 'Categories Mega Menu', 'bunyad'); ?></span>
				
			<?php 
				$query = new WP_Query(apply_filters(
					'bunyad_mega_menu_query_args',
					['cat' => $item->object_id, 'posts_per_page' => 3, 'ignore_sticky_posts' => 1],
					'category-recent'
				));

				echo Bunyad::blocks()->load(
					'Loops\PostsSmall',
					[
						'query_type'         => 'custom',
						'posts'              => 3,
						'cat'                => $item->object_id,
						'columns'            => 1,
						// 'meta_items_default' => false,
						// 'meta_above'         => [],
						// 'meta_below'         => ['date'],
						'heading_type'       => 'none',
						'excerpts'           => false,
						'space_below'        => 'none',
						'pagination'         => false,
						'cat_labels'         => false,
						'separators'         => true
					]
				);
			?>
			
		</section>
	</div>

</div>