<?php
/**
 * Partial: Hamburger menu icon for header.
 */
$props = array_replace([
	'style' => 'a'
], $props);

?>

<button class="offcanvas-toggle has-icon" type="button" aria-label="<?php esc_attr_e('Menu', 'bunyad'); ?>">
	<span class="hamburger-icon hamburger-icon-<?php echo esc_attr($props['style']); ?>">
		<span class="inner"></span>
	</span>
</button>