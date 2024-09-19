<?php
/**
 * Partial template to show results for live search
 */

$number = intval(Bunyad::options()->header_search_live_posts);

// setup the search query
$query = new WP_Query([
	's'              => sanitize_text_field($_GET['query']),
	'posts_per_page' => $number,
	'post_status'    => 'publish',
	
	// Limit to posts or all.
	'post_type'      => (Bunyad::options()->search_posts_only ? 'post' : 'any'), 
]);

?>

	<?php if (!$query->have_posts()): ?> 

		<span class="no-results">
			<?php esc_html_e('Sorry, no results!', 'bunyad'); ?>
		</span>

	<?php 
			return;
		endif;

		echo Bunyad::blocks()->load(
			'Loops\PostsSmall',
			[
				'query'              => $query,
				'posts'              => $number,
				'columns'            => 1,
				'meta_items_default' => false,
				'meta_above'         => [],
				'meta_below'         => ['date'],
				'heading_type'       => 'none',
				'excerpts'           => false,
				'space_below'        => 'none',
				'pagination'         => false,
				'cat_labels'         => false,
				'separators'         => true
			]
		);

	?>
	
	<div class="view-all">
		<a href="<?php echo esc_url(get_search_link($_GET['query'])); ?>">
			<?php esc_html_e('See All Results', 'bunyad'); ?></a>
	</div>