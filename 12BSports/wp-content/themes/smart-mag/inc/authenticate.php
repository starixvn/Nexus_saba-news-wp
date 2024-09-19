<?php
/**
 * Features related to authentication modal and widgets.
 */
class Bunyad_Theme_Authenticate
{
	protected $enabled = false;

	/**
	 * Checks if social logins available.
	 *
	 * @return boolean
	 */
	public function has_social()
	{
		if (function_exists('wsl_render_auth_widget')) {
			return true;
		}

		return false;
	}

	/**
	 * Enable auth modal. Adds necessary markup.
	 *
	 * @return void
	 */
	public function enable()
	{
		if ($this->enabled) {
			return true;
		}

		wp_enqueue_script('micro-modal');

		$this->enabled = true;
		add_action('wp_footer', function() {

			// Logout link maybe shown, but no auth modal needed for logged in.
			if (is_user_logged_in()) {
				return;
			}
			
			get_template_part('partials/auth-modal/auth-modal');
		});
	}

	/**
	 * Do the native login form hooks.
	 */
	public function do_login_hooks()
	{
		// For WSL plugin, temporary remove the login form hook as we handle it manually.
		if (function_exists('wsl_render_auth_widget')) {
			remove_action('login_form', 'wsl_render_auth_widget_in_wp_login_form');
			do_action('login_form');

			// Restore the hook.
			add_action('login_form', 'wsl_render_auth_widget_in_wp_login_form');
			return;
		}

		do_action('login_form');
	}

	/**
	 * Do the native register form hooks.
	 */
	public function do_register_hooks()
	{
		// For WSL plugin, temporary remove the register form hook as we handle it manually.
		if (function_exists('wsl_render_auth_widget')) {
			remove_action('register_form', 'wsl_render_auth_widget_in_wp_register_form');
			do_action('register_form');

			// Restore the hook.
			add_action('register_form', 'wsl_render_auth_widget_in_wp_register_form');
			return;
		}

		do_action('register_form');
	}

	/**
	 * Display "WordPress Social Login" plugin services, decorated for custom use.
	 *
	 * @return void
	 */
	public function the_wsl_services()
	{
		global $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;
		$providers = $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;

		if (!is_array($providers)) {
			return;
		}

		$services = Bunyad::get('social')->get_services();
		foreach ($providers as $provider) {

			// Check if enabled in WSL settings.
			$provider_id = isset($provider['provider_id']) ? $provider['provider_id']  : '';
			if (!get_option('wsl_settings_' . $provider_id . '_enabled')) {
				continue;
			}

			$authenticate_url = add_query_arg(
				[
					'action'   => 'wordpress_social_authenticate',
					'provider' => $provider_id,
					'mode'     => 'login',
				],
				site_url('wp-login.php', 'login_post')
			);

			$provider_id = strtolower($provider_id);

			// Unsupported yet.
			if (!isset($services[$provider_id])) {
				continue;
			}

			$service = $services[$provider_id];
			$label = sprintf(esc_attr__('Login with %s', 'bunyad'), $service['label']);	
					
			?>
			<a href="<?php echo esc_url($authenticate_url); ?>" class="service s-<?php echo esc_attr($provider_id); ?>" rel="nofollow noopener" title="<?php echo esc_attr($label); ?>">
				<i class="icon <?php echo esc_attr($service['icon']); ?>"></i>
				<span class="visuallyhidden"><?php echo esc_html($service['label']); ?></span>
			</a>
			<?php
		}
	}
}

// init and make available in Bunyad::get('authenticate')
Bunyad::register('authenticate', array(
	'class' => 'Bunyad_Theme_Authenticate',
	'init' => true
));