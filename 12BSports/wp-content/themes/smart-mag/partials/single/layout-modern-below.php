<?php 
/**
 * Partial Template for Single Post "Modern Below Layout" - called from single.php
 */

// See layout-modern.php for full props.
$props = array_replace(
	[
		'header_below' => true,
	],
	isset($props) ? $props : []
);

Bunyad::core()->partial('partials/single/layout-modern', $props);