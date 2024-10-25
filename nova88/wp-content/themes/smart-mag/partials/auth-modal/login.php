<?php
/**
 * Authentication Modal Partial: Login
 */

 // Whether registrations are open.
$registrations = get_option('users_can_register');

// Message changes based on if registrations open.
$message       = esc_html__('Login to your account below.', 'bunyad');
$register_link = '';

if ($registrations) {
	$message       = esc_html__('Login below or {register}.', 'bunyad');
	$register_link = sprintf(
		'<a href="#" class="register-link">%s</a>',
		esc_html__('Register Now', 'bunyad')
	);
}

$message = str_replace('{register}', $register_link, $message);

?>
	<h3 class="heading"><?php esc_html_e('Welcome Back!', 'bunyad'); ?></h3>
	<p class="message text"><?php echo wp_kses_post($message); ?></p>

	<?php get_template_part('partials/auth-modal/social'); ?>

	<form method="post" action="<?php echo site_url('wp-login.php', 'login_post'); ?>" class="login-form">

		<div class="input-group">
			<input type="text" name="log" value="" placeholder="<?php esc_html_e('Username or Email', 'bunyad'); ?>" />
		</div>

		<div class="input-group">
			<input type="password" name="pwd" value="" placeholder="<?php esc_html_e('Password', 'bunyad'); ?>" />
		</div>

		<?php Bunyad::authenticate()->do_login_hooks(); // Calls native 'login_form' hook. ?>
		<?php !function_exists('bbp_user_login_fields') || bbp_user_login_fields(); ?>

		<button type="submit" name="wp-submit" id="user-submit" class="ts-button submit user-submit"><?php esc_html_e('Log In', 'bunyad'); ?></button>

		<div class="footer">
			<div class="remember">
				<input name="rememberme" type="checkbox" id="rememberme" value="forever" />
				<label for="rememberme"><?php esc_html_e('Remember Me', 'bunyad'); ?></label>
			</div>

			<a href="<?php echo wp_lostpassword_url(); ?>" title="<?php esc_attr_e('Lost password?', 'bunyad'); ?>" class="lost-pass">
				<?php esc_html_e('Lost password?', 'bunyad'); ?>
			</a>
		</div>

	</form>