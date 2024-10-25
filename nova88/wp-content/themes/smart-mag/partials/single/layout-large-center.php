<?php
/**
 * Post Layout - Large Centered
 */
$props = array_replace(
	[
		'layout'          => 'large',
		'centered'        => true,
		'header_outer'    => true,
		'social_top_style' => Bunyad::options()->single_share_top_style ?: 'b2',
	],
	isset($props) ? $props : []
);

Bunyad::core()->partial('partials/single/layout-modern', $props);