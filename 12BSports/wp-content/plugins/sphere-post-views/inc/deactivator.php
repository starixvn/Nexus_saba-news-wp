<?php

namespace Sphere\PostViews;

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 * 
 * @author Hector Cabrera <me@cabrerahector.com>
 * @author ThemeSphere <support@theme-sphere.com>
 */
class Deactivator
{
	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @global object $wpdb
	 * @param bool $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */
	public static function deactivate($network_wide)
	{
		global $wpdb;

		if (function_exists('is_multisite') && is_multisite()) {
			// Run deactivation for each blog in the network
			if ($network_wide) {
				$original_blog_id = get_current_blog_id();
				$blogs_ids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");

				foreach ($blogs_ids as $blog_id) {
					switch_to_blog($blog_id);
					self::plugin_deactivate();
				}

				// Switch back to current blog
				switch_to_blog($original_blog_id);

				return;
			}
		}

		self::plugin_deactivate();
	}

	/**
	 * On plugin deactivation, disables the shortcode and removes the scheduled task.
	 */
	private static function plugin_deactivate()
	{
		wp_clear_scheduled_hook('sphere_post_views_cache');
	}
}
