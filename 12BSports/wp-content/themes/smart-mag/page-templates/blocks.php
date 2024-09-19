<?php
/**
 * Template Name: Pagebuilder
 * 
 * Same as page.php but with different wrapper/content classes.
 */
$props = [
	'content_class' => 'page-content',
	'breadcrumbs'   => Bunyad::options()->breadcrumbs_pagebuilder
];

// Breadcrumbs are controlled via global Customizer settings for pages, or per page settings 
// under page options. 
// Note: Homepage never displays breadcrumbs.
if (Bunyad::posts()->meta('hide_breadcrumbs')) {
	$props['breadcrumbs'] = false;
}

Bunyad::core()->partial('page', $props);