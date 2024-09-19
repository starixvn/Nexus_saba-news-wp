<?php 
/**
 * Partial template to get post body content on single page
 */

$props = array_replace([
	'spacious_style' => Bunyad::posts()->meta('layout_spacious'),
	'share_float'    => Bunyad::options()->single_share_float,
	'body_type'      => Bunyad::options()->loop_large_classic_body
], (isset($props) ? $props : []));


// Slideshow is disabled in listings.
$has_slideshow = !is_single() ? false : Bunyad::posts()->meta('content_slider');

// post-content classes.
$classes = [
	'post-content cf',
	'entry-content',
	($has_slideshow ? 'post-slideshow' : '')
];

// Spacious or normal style.
if ($props['spacious_style']) {
	$classes[] = Bunyad::core()->get_sidebar() === 'none' ? 'content-spacious-full' : 'content-spacious';
}
else {
	$classes[] = 'content-normal';
}

$wrap_classes = ['post-content-wrap'];
if ($props['share_float']) {
	$wrap_classes[] = 'has-share-float';
}
?>

<div class="<?php echo esc_attr(join(' ', $wrap_classes)); ?>">
	<?php if ($props['share_float']):?>
		<?php if (class_exists('SmartMag_Core')): ?>
			<?php 
				// See plugins/smartmag-core/social-share/views/social-share-float.php
				Bunyad::get('smartmag_social')->render('social-share-float');
			?>
		<?php endif;?>
	<?php endif; ?>

	<div <?php 
		Bunyad::markup()->attribs('post-content', array(
			'class' => $classes,
		)); 
		?>>

		<?php
		// Multi-page content slideshow post?
		if ($has_slideshow):
			get_template_part('partials/single/pagination-large');
		endif;
		
		?>

		<?php do_action('bunyad_post_content_before'); ?>
		
		<?php
		// Excerpt for main content.
		if (is_single() || $props['body_type'] === 'full') {

			/**
			 * A wrapper for the_content() for some of our magic.
			 * 
			 * Note: the_content filter is applied.
			 * 
			 * @see the_content()
			 */
			Bunyad::posts()->the_content(null, false);

		}
		else {

			// Show the excerpt, always add Keep Reading button (more button), and respect <!--more--> (teaser)
			echo Bunyad::posts()->excerpt(
				null, 
				Bunyad::options()->loop_large_excerpt_length,
				[
					'force_more' => true, 
					'use_teaser' => true,
					'more_html'  => '<div><a href="%1$s" class="ts-button read-more-btn">%3$s</a></div>'
				]
			);
		}
		?>

		<?php do_action('bunyad_post_content_after'); ?>
		
		<?php
		// Multi-page content slideshow post - duplicated pagination at bottom
		if ($has_slideshow):
			Bunyad::core()->partial('partials/single/pagination-large', array('position' => 'bottom'));
		endif;
		?>

		
		<?php 
		// Multi-page post - add numbered pagination if not a slideshow.
		if (is_single() && !$has_slideshow):
		
			wp_link_pages(array(
				'before' => '<div class="main-pagination pagination-numbers post-pagination">', 
				'after' => '</div>', 
				'link_before' => '<span>',
				'link_after' => '</span>'
			));

		endif;
		?>

	</div>
</div>
	
<?php if (is_single() && Bunyad::options()->single_tags && has_tag()): ?>
	<div class="the-post-tags"><?php the_tags('', ' '); ?></div>
<?php endif; ?>