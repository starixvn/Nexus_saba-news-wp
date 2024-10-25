<?php 
/**
 * Partial Template for Single Post "Large Layout Image" - called from single.php
 */

 // See layout-modern.php for full props.
$props = array_replace(
	[
		'layout'           => 'large-image',
		'classes'          => ['s-head-large'],
		'featured_in_head' => true,
		'relative_width'   => 100,
		'social_top_style' => Bunyad::options()->single_share_top_style ?: 'b2',
		'header_outer'     => true,
	],
	isset($props) ? $props : []
);

Bunyad::core()->partial('partials/single/layout-modern', $props);