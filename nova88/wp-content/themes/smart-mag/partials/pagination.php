<?php
/**
 * Partial: Common pagination for loops
 */

$props = wp_parse_args($props, [
	'query'            => $wp_query,
	'pagination'       => true,
	'pagination_type'  => Bunyad::options()->pagination_type,
	'load_more_style'  => Bunyad::options()->load_more_style,

	// Only for load-more/infinite type. May be disabled for SEO.
	'pagination_links' => true,
]);

// Pagination disabled?
if (!$props['pagination']) {
	return;
}

// Temp set for WP core functions.
$wp_query = $props['query'];
$pagination_type = $props['pagination_type'];

?>

<?php if (!$pagination_type || $pagination_type == 'numbers-ajax' || $pagination_type == 'numbers'): ?>

	<nav class="main-pagination pagination-numbers" data-type="<?php echo esc_attr($pagination_type); ?>">
		<?php echo Bunyad::posts()->paginate([], $props['query']); ?>
	</nav>

<?php elseif (in_array($pagination_type, ['load-more', 'infinite'])): ?>

	<?php
		/**
		 * Fix paged for static front page and for next_posts() function
		 */
		global $paged;

		if (!$props['page']) {
			$paged = max(1, ($wp_query->get('paged') ? $wp_query->get('paged') : $wp_query->get('page')));
		}
		else {
			$paged = $props['page'];
		}

		$next_url = '#';
		$max_page = $wp_query->max_num_pages;

		if ($props['pagination_links'] && !wp_doing_ajax()) {
			$next_url = get_next_posts_page_link($max_page);
		}

		// Load more style.
		if (!$props['load_more_style']) {
			$props['load_more_style'] = 'a';
		}

		$classes = [
			'ts-button', 
			'load-button',
			'load-button-' . $props['load_more_style'],
		];

		switch ($props['load_more_style']) {
			case 'a':
				$classes[] = 'ts-button-alt';
				break;

			case 'b':
				$classes[] = 'ts-button-b';
				break;	
		}
		
	?>

	<?php if ($paged < $max_page): ?>
	
	<div class="main-pagination pagination-more" data-type="<?php echo esc_attr($pagination_type); ?>">
		<a <?php Bunyad::markup()->attribs('load-more', [
				'href'      => esc_url($next_url),
				'class'     => $classes,
				'data-page' => intval($paged)
			]);?>>
				<?php esc_html_e('Load More', 'bunyad'); ?> 
				<i class="icon tsi tsi-repeat"></i>
		</a>
	</div>	
	
	<?php endif; ?>

<?php endif; ?>

<?php wp_reset_query(); // $wp_query was changed above. ?>
