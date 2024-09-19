<?php
/**
 * Category Template
 * 
 * Sets up the correct loop format to use. Additionally, meta is processed for other
 * layout preferences.
 */

global $bunyad_loop_template;

$loop_args = [];

$category  = get_category(get_query_var('cat'), false);
$cat_meta  = Bunyad::posts()->term_meta(null, $category->term_id);

if (!$cat_meta || empty($cat_meta['template'])) {
	$cat_meta['template'] = Bunyad::archives()->get_default_loop();
}

switch ($cat_meta['template']) {

	// Special query for timeline.
	case 'timeline':
		if (empty($cat_meta['per_page'])) {
			query_posts(array('cat' => $category->term_id, 'posts_per_page' => 30));
		}

		break;
}

// Set pagination.
if (!empty($cat_meta['pagination_type'])) {
	$loop_args['pagination_type'] = $cat_meta['pagination_type'];
}

$args = [
	'loop'      => $cat_meta['template'],
	'loop_args' => $loop_args,
];

// Have a sidebar preference?
if (!empty($cat_meta['sidebar'])) {
	$args['sidebar'] = $cat_meta['sidebar'];
}

Bunyad::core()->partial('archive', $args);