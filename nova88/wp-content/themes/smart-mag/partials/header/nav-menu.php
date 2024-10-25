<?php
/**
 * Partial: Header Navigation.
 */
$props = array_replace([
	// 'main' or 'small'
	'style'       => 'main',
	'hover_style' => 'a',
	'location'    => 'smartmag-main',
	'menu'        => ''
], $props);

// One of these is required.
if (!$props['menu'] && !$props['location']) {
	return;
}

// Setup data variables to enable or disable sticky nav functionality.
$attribs = ['class' => [
	'navigation', 
	'navigation-' . $props['style'],
	'nav-hov-' . $props['hover_style'],
	(Bunyad::options()->nav_search ? 'has-search' : '')
]];

// Wrapper attributes required for sticky nav mainly - to contain nav and search.
$wrap_attribs = ['class' => [
	'navigation-wrap cf',
]];

?>
	<div class="nav-wrap">
		<nav <?php Bunyad::markup()->attribs('navigation', $attribs); ?>>
			<?php
				wp_nav_menu([
					'menu'           => $props['menu'],
					'container'      => false,
					'theme_location' => $props['location'],
					'fallback_cb'    => '', 
					'walker'         => (class_exists('Bunyad_Menus') ? 'Bunyad_MenuWalker' : '')
				]);
			?>
		</nav>
	</div>
