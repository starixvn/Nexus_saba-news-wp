<?php
/**
 * Partial template for social follow buttons.
 */
$props = array_replace([
	'active'  => [],
	'label'   => '',
], $props);

if (!$props['active']) {
	return;
}

$classes = [
	'social-follow-compact',
	'spc-social-colors',
];

$services = Bunyad::social()->get_services();
$links    = Bunyad::options()->social_profiles;

?>

<div class="<?php echo esc_attr(join(' ', $classes)); ?>">
	<span class="label"><?php echo esc_html($props['label']); ?></span>

	<?php 
	Bunyad::blocks()->load('SocialIcons', [
		'style'        => 'custom',
		'services'     => $props['active'],
		'links_class'  => 'f-service',
		'icons_type'   => 'svg_og',
		'brand_colors' => 'color'
	])->render();

	?>
</div>
