<?php
/**
 * Fired when the plugin is uninstalled.
 * 
 * Based on code of Hector Cabrera <me@cabrerahector.com>.
 */

// If uninstall is not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

// Run uninstall for each blog in the network
if (
	function_exists('is_multisite')
	&& is_multisite()
) {
	global $wpdb;

	$original_blog_id = get_current_blog_id();
	$blogs_ids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");

	foreach ($blogs_ids as $blog_id) {
		switch_to_blog($blog_id);
		// delete tables and options
		spv_uninstall();
	}

	// Switch back to current blog
	switch_to_blog($original_blog_id);
} else {
	// delete tables and options
	spv_uninstall();
}

function spv_uninstall()
{
	global $wpdb;

	// Delete plugins' options
	delete_option('sphere_post_views_version');

	// Delete DB tables
	$prefix = $wpdb->prefix . "popularposts";
	$wpdb->query("DROP TABLE IF EXISTS {$prefix}data;");
	$wpdb->query("DROP TABLE IF EXISTS {$prefix}summary;");
}
