<?php
/**
 * Theme one-click updates and notifications for critical security updates.
 */
class Bunyad_Theme_Updates
{
	const UPDATE_URL   = 'https://system.theme-sphere.com/wp-json/api/v2/update';
	const PACKAGES_URL = 'https://updates-cdn.theme-sphere.com';

	/**
	 * @var string Original theme name. May be different than dir name and core slug.
	 */
	protected $theme;

	/**
	 * @var string Theme slug based on directory name, consistent with WP core.
	 */
	protected $theme_wp;

	protected $transient;
	
	/**
	 * @var array In-memory update info from transient.
	 */
	public $update = [];

	/**
	 * @var null|array Remote response data, cached for multiple calls.
	 */
	protected $remote_data;
	
	public function __construct()
	{
		// Set curent theme name at right hook. Legacy: get_template() can be incorrect.
		add_action('bunyad_core_post_init', function() {
			$this->theme     = Bunyad::options()->get_config('theme_name');
			$this->theme_wp  = get_template();
			$this->transient = '_' . $this->theme . '_update_theme';

			// Early init is fine, it's available here.
			$this->update = get_site_transient($this->transient);
		});

		add_filter('pre_set_site_transient_update_themes', [$this, 'check_update']);
		
		add_action('admin_init', [$this, 'register_notices']);
		add_action('init', [$this, 'fatal_check']);
		
		// As long as not AJAX, notification should either be when in wp-cron or in admin.
		// Note: init hook is available in wp-cron requests but not admin_init.
		if (!wp_doing_ajax()) {
			add_action(
				wp_doing_cron() ? 'init' : 'admin_init',
				[$this, 'notify_critical_site_admin']
			);
		}

		// Debug: 
		// $t = get_site_option('_site_transient_update_themes');
		// $t->last_checked = time() - (13 * HOUR_IN_SECONDS);
		// update_site_option('_site_transient_update_themes', $t);
	}
	
	/**
	 * Compares with current version to see if the update is already done.
	 * 
	 * @param mixed $version
	 * @return boolean
	 */
	protected function is_updated($version = '') 
	{
		$version = $version ?: $this->update['new_version'] ?? '';

		return version_compare(
			Bunyad::options()->get_config('theme_version'),
			$version, 
			'>='
		);
	}

	/**
	 * Investigate transients to check for theme version.
	 */
	public function register_notices()
	{
		// Site transient: Shared with all network.
		if (!$this->update || !empty($this->update['fatal'])) {
			return;
		}

		// Already updated.
		if ($this->is_updated()) {
			delete_site_transient($this->transient);
			return;
		}

		$update_url = wp_nonce_url(
			admin_url('update.php?action=upgrade-theme&amp;theme=' . urlencode($this->theme_wp) ), 
			'upgrade-theme_' . $this->theme_wp
		);

		$update_btn = [
			'class' => 'button-primary ts-update-theme-btn',
			'link'  => $update_url,
			'label' => esc_html__('Update Now', 'bunyad-admin'),
		];
		
		// We have a critical update.
		if (!$this->update['safe']) {
			$message = $this->update['info'] ?? sprintf(
				'<h3>Critical Theme Update</h3>
				<p><strong>WARNING:</strong> Your theme requires a critical security update. Please update your theme to latest version %1$s immediately.</p>
				',
				$this->update['new_version']
			);

			$style   = esc_attr($this->update['notify_style'] ?? 'ts-update-nag-critical');
			$buttons = [];

			if ($this->update['package']) {
				$buttons = [
					'update' => $update_btn
				];
			}
			
			Bunyad::admin_notices()->add(
				'update-critical',
				$message,
				[
					'base_classes' => [$style],
					'classes' => ['update-nag ts-update-nag'],
					'sticky'  => true,
					'buttons' => $buttons
				]
			);

			return;
		}

		// For very minor update, don't nag.
		if (isset($update['no_notice'])) {
			return;
		}

		// Normal update message.
		$message = sprintf(
			'<h3 class="ts-notice-title"><span class="ts-version-badge">%2$s</span>New %1$s Update</h3>
			 <p>Please update %1$s to latest version v%2$s to enjoy the latest features and improvements.</p>
			 <img src="%3$s" />
			',
			wp_get_theme()->get('Name'),
			$this->update['new_version'],
			get_template_directory_uri() . '/screenshot.png'
		);
		
		$buttons = [];

		// Have a one-click update available.
		if (!empty($this->update['can_update'])) {
			$message .= '<p>Before updating, make sure to perform a backup. Once updated, ensure all plugins are updated as well.</p>';
			$buttons = [
				'update' => $update_btn,
				'changelog' => [
					'class' => '',
					'link'  => $this->update['url'],
					'label' => esc_html__('See Changelog', 'bunyad-admin'),
					'target' => '_blank',
					'separator' => true
				],
				'dismiss-link' => true,
			];
		}
		else if (!Bunyad::options()->theme_updates) {
			$message .= '<p><strong>One-Click Updates Disabled:</strong> To enable one-click updates, go to Customize and tick "Enable One-Click Updates" in Misc section.</p>';
		}
		else {
			$message .= sprintf(
				'<p><strong>One-Click Updates Disabled:</strong> To enable one-click updates, please make sure to %s and then visit the Dashboard > Updates page twice.</p>',
				'<a href="' . admin_url('admin.php?page=sphere-dash') . '">Activate the License</a>'
			);
		}

		Bunyad::admin_notices()->add(
			'update-' . $this->update['new_version'],
			$message,
			[
				'classes' => ['ts-notice-update'],
				'buttons' => $buttons,
				'screens_skip' => ['update'],
			]
		);
	}

	/**
	 * Notify site admin on a critical security update.
	 */
	public function notify_critical_site_admin()
	{
		$update = $this->update;
		if (!$update || !empty($update['safe']) || empty($update['new_version']) || !empty($update['notify_none'])) {
			return;
		}

		$transient_notified = "_{$this->theme}_critical_notify_{$update['new_version']}";

		// Already updated.
		if ($this->is_updated()) {
			delete_site_transient($transient_notified);
			return;
		}

		// Notification done.
		if (get_site_transient($transient_notified)) {
			return;
		}

		if ($update && empty($update['safe'])) {
			
			$subject = sprintf(
				'[%1$s] URGENT: Theme update required for security',
				wp_specialchars_decode(get_option('blogname'), ENT_QUOTES)
			);

			$message[] = sprintf(
				'Your WordPress site requires an urgent theme update for "%1$s" to maintain security.',
				wp_get_theme()->get('Name')
			);

			$message[] = "\n" . sprintf(
				'Please login to your site admin area to learn more: %s',
				admin_url()
			);

			if (!empty($update['notify_info'])) {
				$message[] = "\n" . wp_strip_all_tags($update['notify_info']);
			}

			$message = implode("\n", $message);

			// Notify site admin about a critical security update.
			wp_mail(get_site_option('admin_email'), $subject, $message);

			// Update status.
			set_site_transient($transient_notified, 1, DAY_IN_SECONDS * 5);
		}
	}

	/**
	 * Fatal error on update.
	 */
	public function fatal_check()
	{
		$update = $this->update;
		if ($update && !empty($update['fatal'])) {

			// Already updated.
			if ($this->is_updated()) {
				delete_site_transient($this->transient);
				return;
			}

			$update['fatal'] = is_admin() ? $update['fatal_admin'] : $update['fatal'];
			wp_die(wp_kses_post($update['fatal']), '', ['response' => 503]);
		}
	}
	
	/**
	 * Filter callback: Checks for theme update.
	 * 
	 * @param object $transient
	 * @return object
	 */
	public function check_update($transient)
	{
		// Safeguard against WP.org theme with the same slug, that may have an update.
		// Envato Market plugin will add it at a later hook, if needed.
		if (isset($transient->response[$this->theme_wp])) {
			unset($transient->response[$this->theme_wp]);
		}

		if (empty($transient->checked)) {
			return $transient;
		}

		$data = (array) $this->get_remote_update_data();
		if (!$data) {
			return $transient;
		}

		// One-click updates aren't enabled, nothing more to do.
		if (!Bunyad::options()->theme_updates) {
			$this->record_update($data);
			return $transient;
		}

		/**
		 * We have a new update with a valid URL.
		 * 
		 * Allow from only single known source: URL has to be valid and start at char 0.
		 */
		$can_update    = false;
		$valid_package = isset($data['package']) && strpos($data['package'], self::PACKAGES_URL) === 0;
		if (!empty($data['new_version']) && $valid_package) {

			// Shouldn't happen, but if version missing or we already have latest installed, bail.
			if (empty($transient->checked[$this->theme_wp]) || $this->is_updated($data['new_version'])) {
				$this->record_update($data);
				return $transient;
			}

			// Basic data required to prevent warnings/notices.
			$theme_data = [
				'theme' => $this->theme_wp,
				'url'   => '',
			];

			$theme_data = array_replace(
				$theme_data,
				array_intersect_key(
					$data,
					array_flip([
						'new_version',
						'url',
						'package',
						'requires', 
						'requires_php'
					])
				)
			);

			// Array is valid, not object unlike for plugins.
			$transient->response[$this->theme_wp] = $theme_data;

			if (isset($transient->no_update)) {
				unset($transient->no_update[$this->theme_wp]);
			}

			$can_update = true;
		}
		
		// Add no_update data for auto-updates compatibility - core requires it.
		if (!$can_update) {
			if (!isset($transient->no_update)) {
				$transient->no_update = [];
			}

			// Array is valid, not object.
			$transient->no_update[$this->theme_wp] = [
				'theme' => $this->theme_wp
			];
		}

		$this->record_update($data, $can_update);

		return $transient;
	}
	
	/**
	 * Record the relevant update data in a transient.
	 * 
	 * @return void
	 */
	protected function record_update($data, $can_update = false)
	{
		// Invalid data - doesn't match expected API response.
		if (!$data || !isset($data['safe'])) {
			return;
		}

		$have_update = !empty($data['new_version']);
		$data['can_update'] = $can_update;

		// Only record if it can be updated or is a fatal or critical update.
		if ($have_update || !$data['safe'] || !empty($data['fatal'])) {
			set_site_transient($this->transient, $data);
		}
		else {
			delete_site_transient($this->transient);
		}
	}

	/**
	 * Get remote data about theme updates.
	 * 
	 * A secure HTTPS request is sent with data in POST to ensure version number isn't 
	 * exposed to MITM.
	 *
	 * @return boolean|array
	 */
	protected function get_remote_update_data()
	{
		/**
		 * Make a remote request if we haven't already done so. Cache remote data and
		 * check for it, as sometimes WP may update 'update_themes' transient twice, 
		 * hencing calling this method twice.
		 */
		if (!$this->remote_data) {
			$args = [
				'body' => [
					'theme' => $this->theme,
					'ver'   => Bunyad::options()->get_config('theme_version'),

					// Checks for skin-specific critical updates too.
					'skin'  => $this->get_active_skin(),
				]
			];

			// If checking for the legacy version instead.
			if (Bunyad::options()->legacy_mode) {
				$args['body']['legacy'] = 1;
			}
			
			$api_key = Bunyad::core()->get_license();
			if (!empty($api_key)) {
				$args['headers'] = ['X-API-KEY' => $api_key];
			}

			$this->remote_data = wp_remote_post(self::UPDATE_URL, $args);

			// Revoked or is invalid.
			if (wp_remote_retrieve_header($this->remote_data, 'X-API-KEY-INVALID')) {
				delete_option($this->theme . '_license');
			}
		}
		
		if (200 !== wp_remote_retrieve_response_code($this->remote_data)) {
			return false;
		}

		return (array) json_decode($this->remote_data['body'], true);
	}

	/**
	 * Currently active skin.
	 * 
	 * @return string
	 */
	protected function get_active_skin()
	{
		$skin = Bunyad::options()->predefined_style;
		if (!$skin) {
			$skin = Bunyad::options()->installed_demo;
		} 

		return $skin ? $skin : 'default';
	}
}

// init and make available in Bunyad::get('theme_updates')
Bunyad::register('theme_updates', [
	'class' => 'Bunyad_Theme_Updates',
	'init'  => true
]);