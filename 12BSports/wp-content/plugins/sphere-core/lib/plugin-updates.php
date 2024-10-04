<?php
/**
 * Handle plugin updates for self-hosted ThemeSphere plugins.
 * 
 * @copyright 2023 ThemeSphere
 */
class Bunyad_Plugin_Updates
{
	const UPDATE_URL   = 'https://system.theme-sphere.com/plugin-versions.json';
	const PACKAGES_URL = 'https://updates-cdn.theme-sphere.com';

	/**
	 * @var null|array Remote response data, cached for multiple calls.
	 */
	protected $remote_data;

	public function __construct()
	{
		// Check for version difference via the typical update hook.
		add_filter('pre_set_site_transient_update_plugins', [$this, 'check_update'], 99);

		// Note: With lower priority than admin-notices module.
		add_action('admin_notices', [$this, 'autoupdates_dependence_check'], 1);
	}

	/**
	 * Ensure critical plugin updates are enabled when theme autoupdates are enabled.
	 */
	public function autoupdates_dependence_check()
	{
		if (!class_exists('\Bunyad') || !\Bunyad::admin_notices()) {
			return;
		}

		// Check if auto-updates enabled for current theme.
		$themes_auto_updates = (array) get_site_option('auto_update_themes');
		if (!$themes_auto_updates || !in_array(get_template(), $themes_auto_updates)) {
			return;
		}

		$plugins_auto_updates = (array) get_site_option('auto_update_plugins');
		$plugins_file = get_template_directory() . '/inc/admin/theme-plugins.php';
		if (!file_exists($plugins_file)) {
			return;
		}

		$dependent_plugins = include $plugins_file; // Safe formed above.
		$invalid_plugins   = [];
		foreach ($dependent_plugins as $plugin) {
			if (empty($plugin['required'])) {
				continue;
			}
			
			$plugin_slug = "{$plugin['slug']}/{$plugin['slug']}.php";
			if (!in_array($plugin_slug, $plugins_auto_updates)) {
				$invalid_plugins[] = $plugin['name'];
			}
		}
		
		if (!$invalid_plugins) {
			return;
		}
		
		\Bunyad::admin_notices()->add(
			'auto-updates-required-' . \Bunyad::options()->get_config('theme_version'),
			sprintf(
				'<h3>Required Plugins Autoupdates</h3>
				<p>Since you have enabled auto-updates for the theme, to prevent errors, please also enable auto-updates for these required plugins from the Plugins page:</p>
				<p><strong>%s</strong></p>
				<p>Alternative: If you do not want auto-updates for the above plugins, disable auto-updates for the theme. You can still use one-click updates.</p>
				',
				implode(', ', $invalid_plugins)
			),
			['type' => 'error']
		);
	}

	/**
	 * Check for plugin updates via the remote server.
	 * 
	 * Note: This may be called twice via core or more via 3rd parties.
	 *
	 * @param object $transient
	 */
	public function check_update($transient)
	{
		// Return if already have run the update check, or it's not a TS theme.
		$not_ts_theme = !class_exists('\Bunyad') || !Bunyad::core();
		if (empty($transient->last_checked) || $not_ts_theme) {
			return $transient;
		}

		$releases = $this->get_releases_data();
		if (!$releases) {
			return $transient;
		}
		
		// For auto-updates compatibility.
		if (!isset($transient->no_update)) {
			$transient->no_update = [];
		}

		// Needed for getting version of installed local plugin.
		$local_plugins = get_plugins();

		foreach ($releases as $plugin_slug => $data) {

			// A plugin name contains slug and filename.
			$plugin = $data['plugin'] ?? $plugin_slug . "/{$plugin_slug}.php";

			// Store existing data of any other updater, temporarily.
			$other_updater = $transient->response[$plugin] ?? false;

			// Safeguard against WP.org plugin with the same slug, that may have an update.
			unset($transient->response[$plugin]);

			if (empty($data['releases']) || empty($local_plugins[$plugin]['Version'])) {
				continue;
			}

			$local_plugin_version = $local_plugins[$plugin]['Version'];
			$plugin_info = array_intersect_key(
				$data,
				array_flip([
					'url', 
					'icons',
					'banners',
				])
			);

			$new_version = $this->get_latest_release(
				$local_plugin_version,
				$data['releases']
			);

			/**
			 * Check if we have a newer version from another updated, likely TGMPA packaged.
			 */
			if ($new_version && $other_updater) {
				if (
					!empty($other_updater->new_version)
					&& version_compare($new_version['version'], $other_updater->new_version, '<')
					&& strpos($other_updater->package, get_stylesheet_directory()) !== false
				) {
					$transient->response[$plugin] = $other_updater;
					continue;
				}
			}

			/**
			 * We have a new update with a valid URL.
			 * 
			 * Prevent system compromise: URL has to be valid and start at char 0.
			 */
			if ($new_version && strpos($new_version['package'], self::PACKAGES_URL) === 0) {
				$update_info = $plugin_info + array_intersect_key(
					$new_version,
					array_flip([
						'package',
						'requires', 
						'requires_php',
						'tested',
					])
				);
				
				// Add extra data if not already present.
				$update_info += [
					'slug'        => $plugin_slug,
					'plugin'      => $plugin,
					'new_version' => $new_version['version'],
				];

				$transient->response[$plugin] = (object) $update_info;

				if (!isset($transient->checked)) {
					$transient->checked = [];
				}
				
				$transient->checked[$plugin] = $local_plugin_version;

				if (isset($transient->no_update)) {
					unset($transient->no_update[$plugin]);
				}
			}
			else {
				// Added for auto-update compatibility.
				$transient->no_update[$plugin] = (object) array_replace(
					$plugin_info,
					[
						'slug'        => $plugin_slug,
						'plugin'      => $plugin,
						'new_version' => $local_plugin_version,
					]
				);
			}
		}

		return $transient;
	}

	/**
	 * Get latest compatible release.
	 *
	 * @param string $current
	 * @param array $releases
	 * @return bool|array
	 */
	protected function get_latest_release($current, array $releases = [])
	{
		// Sort in ascending order by version.
		usort($releases, function($a, $b) {
			return version_compare($b['version'], $a['version']);
		});

		$latest = false;
		$theme  = Bunyad::options()->get_config('theme_name');

		if (!$theme) {
			return false;
		}

		foreach ($releases as $release) {

			// First in loop is the latest if no theme compat check.
			if (empty($release['theme_compat']) || !isset($release['theme_compat'][$theme])) {
				$latest = $release;
				break;
			}

			$theme_compatible = version_compare(
				Bunyad::options()->get_config('theme_version'),
				$release['theme_compat'][$theme],
				'>='
			);

			if ($theme_compatible) {
				$latest = $release;
				break;
			}
		}

		// Current version is already newer or at least release.
		if ($latest && version_compare($current, $latest['version'], '>=')) {
			return false;
		}

		return $latest;
	}

	public function get_releases_data()
	{
		/**
		 * Make a remote request if we haven't already done so. Cache remote data and
		 * check for it, as sometimes WP may update 'update_plugins' transient twice, 
		 * hencing calling this method twice.
		 */
		if (!$this->remote_data) {
			$request_args = [];

			// if (class_exists('\Bunyad') && is_callable([Bunyad::core(), 'get_license'])) {
			// 	$api_key = Bunyad::core()->get_license();
			// 	if (!empty($api_key)) {
			// 		$request_args['headers'] = ['X-API-KEY' => $api_key];
			// 	}
			// }

			$this->remote_data = wp_safe_remote_get(
				self::UPDATE_URL,
				$request_args
			);
		}

		if (200 !== wp_remote_retrieve_response_code($this->remote_data)) {
			return false;
		}

		return (array) json_decode($this->remote_data['body'], true);
	}
}