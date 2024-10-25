<?php
/**
 * Partial: Displays the legacy featured slider and category featured area.
 */

// LEGACY MODE: Using revolution slider? output and return.
if (is_singular() && Bunyad::posts()->meta('featured_slider') == 'rev-slider' && function_exists('putRevSlider')) {
	
	echo '<div class="main-featured"><div class="wrap cf">'
		. do_shortcode('[rev_slider ' . esc_attr(Bunyad::posts()->meta('slider_rev')) .']')
		. '</div></div>';
	
	return;
}

// Featured posts query args.
$args = [
	'meta_key'   => '_bunyad_featured_post', 
	'meta_value' => 1, 
	'order'      => 'date', 
	'ignore_sticky_posts' => 1
];

$feat_args = [
	'type'   => '',
	'posts'  => 5,
	'width'  => 'container',
	'slider' => false
];

/**
 * Category slider.
 */
if (is_category()) {
	
	$configs = [
		'slider'         => Bunyad::options()->category_slider,
		'slider_type'    => Bunyad::options()->category_slider_type,
		'slider_width'   => Bunyad::options()->category_slider_width,
		'slider_number'  => Bunyad::options()->category_slider_number,
		'slider_slides'  => Bunyad::options()->category_slider_slides
	];

	$cat  = get_query_var('cat');
	$meta = Bunyad::posts()->term_meta(null, $cat);

	if (!empty($meta['slider'])) {
		$configs = array_replace($configs, $meta);
	}

	// Slider not enabled.
	if (empty($configs['slider']) || $configs['slider'] === 'none') {
		return;
	}
		
	$args['cat'] = $cat;
	
	// Latest Posts.
	if ($configs['slider'] === 'latest') {
		unset($args['meta_key'], $args['meta_value']);
	}
	
	$feat_args = array_replace($feat_args, [
		'type'   => $configs['slider_type'] ? $configs['slider_type'] : '',
		'posts'  => $configs['slider_number'] ? $configs['slider_number'] : 5,
		'width'  => $configs['slider_width'] ? $configs['slider_width'] : 'container',
		'slider' => $configs['slider_slides']
	]);
	
	if (!empty($configs['slider_tags'])) {
		$args['tag_slug__in'] = explode(',', $configs['slider_tags']);
	}
}
else {
	/**
	 * Page slider in legacy mode.
	 */
	
	// Normal slider on a page
	$feat_args['type']  = Bunyad::posts()->meta('slider_type');
	$feat_args['posts'] = Bunyad::posts()->meta('slider_number') ? (int) Bunyad::posts()->meta('slider_number') : 5;

	// This page meta from legacy isn't converted.
	switch ($feat_args['type']) {
		case 'grid':
			$feat_args['type'] = 'grid-a';
			break;

		case 'grid-b':
			$feat_args['type'] = 'grid-d';
			break;

		default:
			$feat_args['type'] = 'classic';
			break;
	}
	
	// Limited by tag.
	if (Bunyad::posts()->meta('slider_tags')) {
		$args['tag_slug__in'] = explode(',', Bunyad::posts()->meta('slider_tags'));
	}
	
	// Manual post ids.
	if (Bunyad::posts()->meta('slider_posts')) {
		$args['post__in'] = explode(',', Bunyad::posts()->meta('slider_posts'));
	}
	
	// Use latest posts.
	if (Bunyad::posts()->meta('featured_slider') == 'default-latest') {
		unset($args['meta_key'], $args['meta_value']);
	}
}

/**
 * Slider Type - set relevant variables.
 */
$args['posts_per_page'] = (
	$feat_args['type'] !== 'classic' 
		? $feat_args['posts'] 
		: $feat_args['posts'] + 3
);

/**
 * Main slider posts query and apply filters for args and query
 */
$query = apply_filters('bunyad_featured_area_query', new WP_Query($args));

if (!$query->have_posts()) {
	return;
}

// Skip feed posts for category.
if (is_category()) {
	Bunyad::archives()->cat_featured_posts_skip($query);
}

if (strpos($feat_args['type'], 'grid-') !== false) {

	$classes = [
		'main-featured'
	];
	$inner_class = '';

	if ($feat_args['width'] == 'container') {
		$classes[] = 'is-container ts-contain';
		$inner_class = 'wrap';
	}

?>
	<div class="<?php echo esc_attr(join(' ', $classes)); ?>">
		<div class="<?php echo esc_attr($inner_class); ?>">
		<?php

		Bunyad::blocks()->load(
			'Loops\FeatGrid',
			[
				'grid_type'   => $feat_args['type'],
				'query_type'  => '',
				'query'       => $query,
				'posts'       => $feat_args['posts'],
				'space_below' => 'none',
				'grid_width'  => $feat_args['width'],
				'has_slider'  => $feat_args['slider'],
			]
		)
		->render();

		?>
		</div>
	</div>
	
<?php
}
else if ($feat_args['type'] === 'classic') {
	Bunyad::core()->partial('partials/slider/classic', [
		'query'  => $query,
		'posts'  => $feat_args['posts']
	]);
}