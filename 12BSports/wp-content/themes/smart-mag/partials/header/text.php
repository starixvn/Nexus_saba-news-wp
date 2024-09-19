<?php
/**
 * Partial: Text/HTML content for header.
 */
$props = array_replace([
	'text'  => '',
	'class' => '',
], $props);
?>

<div class="h-text <?php echo esc_attr($props['class']); ?>">
	<?php
		// Generally safe text only inserted by a user role with 'manage_options' or 'customize'.
		echo do_shortcode(
			apply_filters('bunyad_render_custom_html', $props['text'], 'header_text')
		);
	?>
</div>