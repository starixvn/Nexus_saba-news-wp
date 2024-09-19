<?php
/**
 * Partial: Date for header.
 */

$props = array_replace([
	'date_format' => null
], $props);

if (!$props['date_format']) {
	$props['date_format'] = 'l, F j';
}

?>

<span class="h-date">
	<?php echo esc_html(date_i18n($props['date_format'])); ?>
</span>