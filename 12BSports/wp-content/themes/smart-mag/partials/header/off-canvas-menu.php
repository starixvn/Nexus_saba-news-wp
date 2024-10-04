<?php
/**
 * Partial: Off-canvas Menu for Header.
 */
$props = array_replace([
	'block'          => null,
	'scheme'         => '',
	'mobile_widgets' => 1,
	'desktop_menu'   => 0,
	'social'         => [],
], $props);

/** @var \Bunyad\Blocks\Header $block */
$block = $props['block'];

// Block is required.
if (!is_object($block)) {
	return;
}

$widget_area = 'smartmag-off-canvas';
$classes     = [
	'mobile-menu-container off-canvas',
	($props['scheme'] === 'dark' ? 's-dark' : ''),
	!$props['mobile_widgets'] ? 'hide-widgets-sm' : '',
	!$props['desktop_menu']   ? 'hide-menu-lg' : '',

];

$mobile_menu = 'smartmag-mobile';

// Fallback to main menu for AMP if mobile is missing
if (!has_nav_menu('smartmag-mobile') && Bunyad::amp()->active()) {
	$mobile_menu = 'smartmag-main';
}

?>

<div class="off-canvas-backdrop"></div>
<div <?php Bunyad::markup()->attribs('off-canvas', ['class' => $classes]); ?> id="off-canvas">

	<div class="off-canvas-head">
		<a href="#" class="close">
			<span class="visuallyhidden"><?php esc_html_e('Close Menu', 'bunyad'); ?></span>
			<i class="tsi tsi-times"></i>
		</a>

		<div class="ts-logo">
			<?php
				$block->the_mobile_logo();
			?>
		</div>
	</div>

	<div class="off-canvas-content">

		<?php if (has_nav_menu($mobile_menu)): // Have a mobile menu. ?>

			<?php 
				wp_nav_menu([
					'container'      => '',
					'menu_class'     => 'mobile-menu',
					'theme_location' => $mobile_menu,
					'walker'         => Bunyad::amp()->active() ? new Bunyad_Theme_Amp_MenuWalker : ''
				]); 
			?>

		<?php else: ?>
			<ul class="mobile-menu"></ul>
		<?php endif;?>

		<?php if (is_active_sidebar($widget_area)): ?>
			<div class="off-canvas-widgets">
				<?php dynamic_sidebar($widget_area); ?>
			</div>
		<?php endif; ?>

		<?php 
		if ($props['social']) {
			Bunyad::core()->partial('partials/header/social-icons', [
				'style'    => 'b',
				'services' => $props['social'],
			]); 
		}
		?>

	</div>

</div>