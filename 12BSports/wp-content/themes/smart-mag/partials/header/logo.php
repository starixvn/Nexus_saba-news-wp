<?php
/**
 * Partial: Logo 
 */

$props = array_replace([
	'block' => null,
	'type'  => '',
], $props);

/** @var \Bunyad\Blocks\Header $block */
$block = $props['block'];

// attributes for the logo link
$attribs = [
	'href'  => home_url('/'),
	'title' => get_bloginfo('name', 'display'),
	'rel'   => 'home',
	'class' => [
		'logo-link ts-logo',
	],
];

// Block object required for helpers.
if (!is_object($block)) {
	return;
}

$has_image_logo = Bunyad::options()->image_logo;
$attribs['class'][] = $has_image_logo ? 'logo-is-image' : 'text-logo';

$inner_tag = (is_front_page() && Bunyad::options()->header_logo_home_h1 ? 'h1' : 'span');
if ($props['type'] === 'mobile') {
	$inner_tag = 'span';
}

?>
	<a <?php Bunyad::markup()->attribs('main-logo', $attribs); ?>>
		<<?php echo esc_html($inner_tag); ?>>
			<?php if ($has_image_logo): ?>

				<?php if ($props['type'] === 'mobile' && Bunyad::options()->mobile_logo_2x): ?>
					<?php 
						$block->the_mobile_logo();
					?>
				<?php else: ?>

					<?php 
						/**
						 * Render dark and normal logos, in that order.
						 */	
						$block->the_logo('dark');
						$block->the_logo();
					?>

				<?php endif; ?>
					 
			<?php else: ?>

				<?php 
					// Pre-sanitized in either case.
					$text_logo = Bunyad::options()->text_logo ? Bunyad::options()->text_logo : get_bloginfo('name', 'display');
					echo do_shortcode($text_logo);
				?>

			<?php endif; ?>
		</<?php echo esc_html($inner_tag); ?>>
	</a>