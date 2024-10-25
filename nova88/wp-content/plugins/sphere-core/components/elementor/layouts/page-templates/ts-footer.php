<?php
/**
 * Footer Page template that's full width with header.
 * 
 * Note: Only used for template preview, not for real footer rendering on frontend.
 */

if (class_exists('Bunyad') && Bunyad::core()) {
	Bunyad::core()->set_sidebar('none');
}

get_header();
?>

<div class="main ts-contain cf">
	<h3>Content Here</h3>
	<p>Sample body content here.</p>
</div>

<?php get_footer(); ?>