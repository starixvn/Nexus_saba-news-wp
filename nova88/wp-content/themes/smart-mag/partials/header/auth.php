<?php 
/**
 * Partial: Header Auth Links.
 */
$props = array_replace([
	'style'   => '',
	'icon'    => true,
	'text'    => '',
	'logout'  => true,
], $props);

$classes = [
	'auth-link',
	$props['icon'] ? 'has-icon' : '',
];

Bunyad::authenticate()->enable();

$login_link = Bunyad::amp()->active() ? wp_login_url() : '#auth-modal';
?>

<?php if (!is_user_logged_in()): ?>

	<a href="<?php echo esc_url($login_link); ?>" class="<?php echo esc_attr(join(' ', $classes)); ?>">
		<?php if ($props['icon']): ?>
			<i class="icon tsi tsi-user-circle-o"></i>
		<?php endif; ?>

		<?php if ($props['text']): ?>
			<span class="label"><?php echo wp_kses_post($props['text']); ?></span>
		<?php endif; ?>
	</a>

<?php elseif ($props['logout']): ?>

	<a href="<?php echo wp_logout_url(); // Already encoded ?>" class="<?php echo esc_attr(join(' ', $classes)); ?>">
		<?php if ($props['icon']): ?>
			<i class="icon tsi tsi-user-circle-o"></i>
		<?php endif; ?>
		<span class="label"><?php esc_html_e('Logout', 'bunyad'); ?></span>
	</a>

<?php endif; ?>
