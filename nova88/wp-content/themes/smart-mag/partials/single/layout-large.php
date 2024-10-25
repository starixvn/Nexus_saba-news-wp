<?php 
/**
 * Partial Template for Single Post "Large Layout" - called from single.php
 */

$has_large_bot = Bunyad::options()->single_share_top ? true : false;
if (Bunyad::options()->single_share_top_location === 'meta-right') {
	$has_large_bot = false;
}

// See layout-modern.php for full props.
$props = array_replace(
	[
		'layout'           => 'large',
		'social_top_style' => Bunyad::options()->single_share_top_style ?: 'b2',
		'has_large_bot'    => $has_large_bot,
		'header_outer'     => true,
	],
	isset($props) ? $props : []
);

Bunyad::core()->partial('partials/single/layout-modern', $props);