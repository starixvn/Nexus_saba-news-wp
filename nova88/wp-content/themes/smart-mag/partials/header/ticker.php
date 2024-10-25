<?php
/**
 * Partial: News Ticker for header.
 */

$props = array_replace([
	'posts'   => 8,
	'tags'    => '',
	'heading' => '',
	'delay'   => 8,
], $props);

$query_args = [
	'orderby'             => 'date', 
	'order'               => 'desc', 
	'posts_per_page'      => $props['posts'],
	'ignore_sticky_posts' => true,
];

if ($props['tags']) {
	$query_args['tag_slug__in'] = array_map('trim', explode(',', $props['tags']));
}

$query = new WP_Query(apply_filters('bunyad_ticker_query_args', $query_args));

if (!$query->have_posts()) {
	return;
}

if ($props['heading'] === '') {
	$props['heading'] = esc_html__('Trending', 'bunyad');
}

?>

<div class="trending-ticker" data-delay="<?php echo esc_attr($props['delay']); ?>">
	<span class="heading"><?php 
		echo wp_kses_post($props['heading']);
	?></span>

	<ul>
		<?php while($query->have_posts()): $query->the_post(); ?>
		
			<li><a href="<?php the_permalink(); ?>" class="post-link"><?php the_title(); ?></a></li>
		
		<?php endwhile; ?>
		
		<?php wp_reset_postdata(); ?>
	</ul>
</div>
