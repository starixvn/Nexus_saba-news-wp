<?php
/**
 * Authentication Modal Partial: Login
 */
?>
	<h3 class="heading"><?php esc_html_e('Register Now!', 'bunyad'); ?></h3>

	<p class="message text">
		<?php 
		printf(
			esc_html__('Already registered? %1$sLogin%2$s.', 'bunyad'),
			'<a href="#" class="login-link">',
			'</a>'
		);
		?>
	</p>

	<?php get_template_part('partials/auth-modal/social'); ?>

	<form method="post" action="<?php echo esc_url(site_url('wp-login.php?action=register', 'login_post')); ?>" class="register-form">

		<div class="input-group">
			<input type="text" name="user_login" value="" placeholder="<?php esc_html_e('Your Username', 'bunyad'); ?>" />
		</div>

		<div class="input-group">
			<input type="text" name="user_email" value="" placeholder="<?php esc_html_e('Your Email', 'bunyad'); ?>" />
		</div>

		<?php Bunyad::authenticate()->do_register_hooks(); // Calls native 'register_form' hook. ?>

		<button type="submit" name="wp-submit" class="ts-button submit user-submit"><?php esc_html_e('Register', 'bunyad'); ?></button>

		<div class="footer">
			<p><?php esc_html_e('A password will be e-mailed to you.', 'bunyad'); ?></p>
		</div>

	</form>