<?php 
/**
 * Partial Template for Single Post "Large Layout" - called from single.php
 */


// See layout-modern.php for full props.
$props = array_replace(
	[
		'layout'           => 'large-b',
		'classes'          => ['s-head-large', 's-head-has-sep'],
		'header_outer'     => true,
		'social_top_style' => Bunyad::options()->single_large_b_share_top_style,
		'social_top_location' => Bunyad::options()->single_large_b_share_top_location
	],
	isset($props) ? $props : []
);

$props['post_classes'][] = 's-post-large';

Bunyad::core()->partial('partials/single/layout-modern', $props);