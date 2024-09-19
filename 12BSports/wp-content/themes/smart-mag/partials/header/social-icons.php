<?php 
/**
 * Partial: Header Social Icons.
 */
$props = array_replace([
	'style' => 'a',
	'services' => [
		'facebook',
		'twitter',
	]
], $props);

if (empty($props['services'])) {
	return;
}

Bunyad::blocks()->load('SocialIcons', [
	'style'    => $props['style'],
	'services' => $props['services'],
	'class'    => 'smart-head-social',
])->render();
