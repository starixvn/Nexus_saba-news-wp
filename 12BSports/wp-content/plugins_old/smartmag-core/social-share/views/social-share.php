<?php
/**
 * Partial template for social share buttons on single page.
 * 
 * See: inc/social.php for filters and caller.
 * 
 * How to Modify:
 *  - Use the action hooks below. 
 *  - OR, place it in your-child-theme/partials/social-share/social-share.php
 */

$props = array_replace([
	'active' => [
		'facebook', 'twitter', 'pinterest', 'linkedin', 'tumblr', 'email'
	]
], $props);

$services = Bunyad::get('smartmag_social')->share_services();

if (!$props['active']) {
	return;
}

?>

<?php if ((is_single() || Bunyad::options()->social_icons_classic) && Bunyad::options()->single_share_bot): ?>
	
	<div class="post-share-bot">
		<span class="info"><?php esc_html_e('Share.', 'bunyad'); ?></span>
		
		<span class="share-links spc-social spc-social-colors spc-social-bg">

			<?php do_action('bunyad_social_share_services_start'); ?>

			<?php 
				foreach ($props['active'] as $key): 
					$service = $services[$key];
			?>

				<a href="<?php echo esc_url($service['url']); ?>" class="service s-<?php echo esc_attr($key); ?> <?php echo esc_attr($service['icon']); ?>" 
					title="<?php echo esc_attr($service['label_full']); ?>" target="_blank" rel="nofollow noopener">
					<span class="visuallyhidden"><?php echo esc_html($service['label']); ?></span>

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

			<?php do_action('bunyad_social_share_services_end'); ?>

		</span>
	</div>
	
<?php endif; ?>
