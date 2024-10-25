<?php
/**
 * Partial: Scheme switcher used in header
 */
$props = array_replace([
	'style' => ''
], $props);

// AMP not supported.
if (Bunyad::amp()->active()) {
	return;
}

?>

<div class="scheme-switcher has-icon-only">
	<a href="#" class="toggle is-icon toggle-dark" title="<?php echo esc_attr__('Switch to Dark Design - easier on eyes.', 'bunyad'); ?>">
		<i class="icon tsi tsi-moon"></i>
	</a>
	<a href="#" class="toggle is-icon toggle-light" title="<?php echo esc_attr__('Switch to Light Design.', 'bunyad'); ?>">
		<i class="icon tsi tsi-bright"></i>
	</a>
</div>