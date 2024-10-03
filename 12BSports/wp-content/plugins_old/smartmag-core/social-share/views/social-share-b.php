<?php
/**
 * Partial template for social share buttons on single page.
 * 
 * See: inc/social.php for filters and caller.
 * 
 * How to Modify:
 *  - Use the action hooks below. 
 *  - OR, place it in your-child-theme/partials/social-share/social-share-b.php
 */

$props = array_replace([
	'active' => [
		'facebook', 'twitter', 'pinterest', 'linkedin', 'tumblr', 'email'
	],
	'style'   => '',

	// Add extra classes to wrapper.
	'classes' => [],

	'text'   => Bunyad::options()->get_or(
		'single_share_top_text',
		esc_html__('Share', 'bunyad')
	),
	'label'   => Bunyad::options()->single_share_top_label,
	'devices' => [],

	'follow_label'  => '',
	'follow_active' => [],

], $props);

$services = Bunyad::get('smartmag_social')->share_services();

if (!$props['active']) {
	return;
}

$classes = array_merge($props['classes'], [
	'post-share post-share-b',
	'spc-social-colors',
	(Bunyad::amp() && Bunyad::amp()->active() ? ' all' : ''),
]);

// Add BG colors.
if (!in_array($props['style'], ['b3', 'b3-circles', 'b4'])) {
	$social_class = 'spc-social-bg';
}

if ($props['follow_active']) {
	$classes[] = 'has-social-follow';
}

switch ($props['style']) {
	case 'b3-circles':
		array_push($classes, 'post-share-b3', 'post-share-b-circles', 'post-share-b3-circles');
		break;

	case 'circles':
		$classes[] = 'post-share-b-circles';
		break;

	default:
		if ($props['style']) {
			$classes[] = 'post-share-' . $props['style'];
		}

		break;
}

// Devices to show on.
if ($props['devices'] && !in_array('all', $props['devices'])) {
	if (in_array('sm', $props['devices'])) {
		$props['devices'][] = 'xs';
	}
	
	foreach ($props['devices'] as $device) {
		$classes[] = 'show-' . $device;
	}
}

$large_buttons = Bunyad::options()->single_share_top_large ?: 3;
?>

<?php 
// Start wrapper if follow bar is enabled.
if ($props['follow_active']) {
	echo '<div class="post-share post-share-follow-top">';
}
?>
	<div class="<?php echo esc_attr(join(' ', $classes)); ?>">

		<?php if ($props['label']): ?>
			<span class="share-text">
				<i class="icon tsi tsi-share1"></i>
				<?php echo esc_html($props['text']); ?>
			</span>
		<?php endif; ?>
		
		<?php do_action('bunyad_social_share_b_services_start'); ?>

		<?php 
			$i = 0;
			foreach ($props['active'] as $key): 
				$i++;
				$service  = $services[$key];
				$is_large = 

				$classes = [
					'cf service s-' . $key,
					$i <= $large_buttons ? 'service-lg' : 'service-sm'
				];
		?>
		
			<a href="<?php echo esc_url($service['url']); ?>" class="<?php echo esc_attr(join(' ', $classes)); ?>" 
				title="<?php echo esc_attr($service['label_full']); ?>" target="_blank" rel="nofollow noopener">
				<i class="tsi tsi-<?php echo esc_attr($service['icon']); ?>"></i>
				<span class="label"><?php echo esc_html($service['label']); ?></span>
				<?php 
					if ($key === 'link') {
						printf(
							'<span data-message="%s"></span>',
							esc_attr__('Link copied successfully!', 'bunyad')
						);
					}
				?>
			</a>
				
		<?php endforeach; ?>

		<?php do_action('bunyad_social_share_b_services_end'); ?>
		
		<?php if (count($props['active']) > $large_buttons): ?>
			<a href="#" class="show-more" title="<?php esc_attr_e('Show More Social Sharing', 'bunyad'); ?>"><i class="tsi tsi-share"></i></a>
		<?php endif; ?>

		<?php do_action('bunyad_social_share_b_services_after'); ?>
		
	</div>

<?php 
if ($props['follow_active']) {
	Bunyad::core()->partial('partials/single/social-follow-top', [
		'props' => [
			'active' => $props['follow_active'],
			'label'  => $props['follow_label']
		]
	]);

	echo '</div>';
}
