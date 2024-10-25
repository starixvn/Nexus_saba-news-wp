<?php 
/**
 * Partial: Header Social Icons.
 */
$props = array_replace([
	'style' => 'alt',
	'text'  => '',
	'link'  => '',
	'class' => '',
	'target' => '',
], $props);

if (!$props['text']) {
	$props['text'] = 'Button';
}

$classes = [
	'ts-button',
	'ts-button-' . $props['style'],
	$props['class'] ? $props['class'] : ''
];

$attribs = [
	'href'  => esc_url($props['link']),
	'class' => $classes
];

if ($props['target']) {
	$attribs['target'] = '_blank';
	$attribs['rel']    = 'noopener';
}

?>

	<a <?php Bunyad::markup()->attribs('header-button', $attribs); ?>>
		<?php echo wp_kses_post($props['text']); ?>
	</a>
