<?php

namespace Sphere\PostViews\Admin;

use Sphere\PostViews\Activator;
use Sphere\PostViews\Api;
use Sphere\PostViews\Helper;
use Sphere\PostViews\Options;
use Sphere\PostViews\Plugin;

/**
 * Admin related functionality
 * 
 * @author ThemeSphere <support@theme-sphere.com>
 */
class Admin
{
	/**
	 * Slug of the plugin screen.
	 *
	 * @var  string
	 */
	protected $screen_hook_suffix;

	/**
	 * Plugin options.
	 */
	protected $options;
	protected $api;

	public function __construct(Options $options, Api $api)
	{
		$this->options = $options;
		$this->api     = $api;
		
		$this->init();
	}

	/**
	 * WordPress public-facing hooks.
	 */
	public function init()
	{
		// Add plugin settings link.
		add_filter('plugin_action_links', [$this, 'add_plugin_settings_link'], 10, 2);

		if ($this->options->spv_admin_columns) {
			add_action('admin_init', [$this, 'register_columns']);
			add_action('admin_enqueue_scripts', [$this, 'register_assets']);
		}
		
		// Add admin screen
		// add_action('admin_menu', [$this, 'add_plugin_admin_menu']);
		// Delete plugin data
		// add_action('wp_ajax_wpp_clear_data', [$this, 'clear_data']);

		// Purge post data on post/page deletion.
		add_action('delete_post', [$this, 'purge_post']);
		
		// Purge old data on the scheduled cron event.
		add_action('sphere_post_views_cache', [$this, 'purge_expired_data']);

		// Hook fired when a new blog is activated on WP Multisite
		add_action('wpmu_new_blog', [$this, 'activate_new_site']);

		// Hook fired when a blog is deleted on WP Multisite.
		add_filter('wpmu_drop_tables', [$this, 'delete_site_data'], 10, 2);

		// Performance message.
		add_action('admin_init', [$this, 'performance_check']);

		add_action('admin_init', function() {
			$metabox = new MetaBox($this->options, $this->api);
			$metabox->init();
		});

		/**
		 * Old data purge schedule check.
		 */
		add_action('admin_init', function() {

			// Setup a job to delete old data if enabled.
			if ($this->options->spv_log_limit) {
				if (!wp_next_scheduled('sphere_post_views_cache')) {
					$midnight = strtotime('midnight') - (get_option('gmt_offset') * HOUR_IN_SECONDS) + DAY_IN_SECONDS;
					wp_schedule_event($midnight, 'daily', 'sphere_post_views_cache');
				}
			} else {
				// Remove the scheduled event if exists
				if ($timestamp = wp_next_scheduled('sphere_post_views_cache')) {
					wp_unschedule_event($timestamp, 'sphere_post_views_cache');
				}
			}
		});
	}

	public function register_assets($hook)
	{
		// Only need the CSS for edit.php for now.
		if (!in_array($hook, ['edit.php'])) {
			return;
		}

		wp_enqueue_style(
			'sphere-post-views-admin',
			Plugin::get_instance()->dir_url . 'assets/css/admin.css',
			[],
			Plugin::VERSION
		);
	}

	/**
	 * Register columns for admin area posts table.
	 */
	public function register_columns()
	{
		$post_types = apply_filters('sphere/post_views/post_types', ['post']);
		foreach ($post_types as $type) {
			add_filter("manage_{$type}_posts_columns", [$this, 'add_columns']);
			add_filter("manage_edit-{$type}_sortable_columns", [$this, 'add_sortable_columns']);

			add_action("manage_{$type}_posts_custom_column", [$this, 'column_views'], 10, 2);
		}
	}

	public function add_columns($columns)
	{
		$post_views = '<span>' . esc_attr__('Views', 'sphere-post-views') . '</span>';

		$insert = false;
		$insert = isset($columns['comments']) ? 'comments' : $insert;
		$insert = isset($columns['date']) ? 'date' : $insert;

		if ($insert && function_exists(('\Bunyad\Util\array_insert'))) {
			\Bunyad\Util\array_insert(
				$columns, 
				$insert,
				['post_views' => $post_views],
				'before'
			);
		}
		else {
			$columns['post_views'] = $post_views;
		}

		return $columns;
	}

	public function add_sortable_columns($columns)
	{
		$columns['post_views'] = 'post_views';
		return $columns;
	}

	public function column_views($column, $id)
	{
		if ($column !== 'post_views') {
			return;
		}

		echo esc_html(
			number_format_i18n((int) get_post()->post_views)
		);
	}

	/**
	 * Checks whether a performance tweak may be necessary.
	 */
	public function performance_check()
	{
		global $wpdb;

		if (!class_exists('\Bunyad') || !\Bunyad::admin_notices()) {
			return;
		}

		if (!current_user_can('manage_options')) {
			return;
		}

		// Check if already using optimizations.
		$optimizations = $this->options->spv_sampling && $this->options->spv_short_endpoint;
		if ($optimizations && wp_using_ext_object_cache()) {
			return;
		}

		/**
		 * Get existing status of the performance check.
		 */
		$views_count = get_transient('spv_performance_check');
		if (false === $views_count) {

			$views_count = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT IFNULL(SUM(pageviews), 0) AS views FROM {$wpdb->prefix}popularpostssummary WHERE view_datetime > DATE_SUB(%s, INTERVAL 1 HOUR);",
					Helper::now()
				)
			);
			
			set_transient('spv_performance_check', (int) $views_count, DAY_IN_SECONDS);
		}

		// Not a high traffic site.
		if ($views_count <= 300) {
			return;
		}

		\Bunyad::admin_notices()->add(
			'spv-performance',
			sprintf(
				'<h4>Performance for Sphere Post Views</h4>
				<p>It seems like you have a high traffic site. Consider adding object cache and other performance tweaks: %s</p>',
				'<a href="https://theme-sphere.com/docs/smart-mag/#sphere-post-views-performance" target="_blank"> &raquo; Read About Performance</a>'
			),
			[
				'buttons' => [
					'dismiss' => true,
					'remind'  => true,
				],
				'dismiss_expiry' => 3 * MONTH_IN_SECONDS
			]
		);
	}

	/**
	 * Fired when a new blog is activated on WP Multisite.
	 *
	 * @param int $blog_id New blog ID
	 */
	public function activate_new_site($blog_id)
	{
		if (!did_action('wpmu_new_blog')) {
			return;
		}

		// run activation for the new blog
		switch_to_blog($blog_id);
		Activator::track_new_site();
		// switch back to current blog
		restore_current_blog();
	}

	/**
	 * Fired when a blog is deleted on WP Multisite.
	 *
	 * @param  array $tables
	 * @param  int   $blog_id
	 * @return array
	 */
	public function delete_site_data($tables, $blog_id)
	{
		global $wpdb;

		$tables[] = $wpdb->prefix . 'popularpostsdata';
		$tables[] = $wpdb->prefix . 'popularpostssummary';

		return $tables;
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 */
	public function add_plugin_admin_menu()
	{
		// $this->screen_hook_suffix = add_submenu_page(
		// 	'edit.php',
		// 	'Post Views Count',
		// 	'Post Views Count',
		// 	'edit_published_posts',
		// 	'sphere_post_views_stats',
		// 	[$this, 'display_stats']
		// );
	}

	/**
	 * Registers Settings link on plugin description.
	 *
	 * @param array  $links
	 * @param string $file
	 * @return array
	 */
	public function add_plugin_settings_link($links, $file)
	{
		$plugin = plugin_basename(Plugin::get_instance()->plugin_file);

		if (!class_exists('\Bunyad') || !\Bunyad::theme()) {
			return $links;
		}

		if (is_plugin_active($plugin) && $plugin == $file) {
			array_unshift(
				$links,
				'<a href="' . admin_url('customize.php?autofocus[section]=sphere-post-views') . '">' . esc_html__('Settings') . '</a>'
			);
		}

		return $links;
	}

	/**
	 * Truncates data and cache on demand.
	 *
	 * @global object $wpdb
	 */
	public function clear_data()
	{
		$token = isset($_POST['token']) ? $_POST['token'] : null;
		$clear = isset($_POST['clear']) ? $_POST['clear'] : null;

		if (
			current_user_can('manage_options')
			&& wp_verify_nonce($token, 'wpp_nonce_reset_data')
			&& $clear
		) {
			global $wpdb;

			// set table name
			$prefix = $wpdb->prefix . "popularposts";

			if ($clear == 'cache') {
				if ($wpdb->get_var("SHOW TABLES LIKE '{$prefix}summary'")) {
					$wpdb->query("TRUNCATE TABLE {$prefix}summary;");
					echo 1;
				} else {
					echo 2;
				}
			} elseif ($clear == 'all') {
				if ($wpdb->get_var("SHOW TABLES LIKE '{$prefix}data'") && $wpdb->get_var("SHOW TABLES LIKE '{$prefix}summary'")) {
					$wpdb->query("TRUNCATE TABLE {$prefix}data;");
					$wpdb->query("TRUNCATE TABLE {$prefix}summary;");
					echo 1;
				} else {
					echo 2;
				}
			} else {
				echo 3;
			}
		} else {
			echo 4;
		}

		wp_die();
	}

	/**
	 * Purges post from data/summary tables.
	 *
	 * @global object $wpdb
	 */
	public function purge_post($post_ID)
	{
		global $wpdb;

		if ($wpdb->get_var($wpdb->prepare("SELECT postid FROM {$wpdb->prefix}popularpostsdata WHERE postid = %d", $post_ID))) {

			// Delete from data table
			$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}popularpostsdata WHERE postid = %d;", $post_ID));
			
			// Delete from summary table
			$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}popularpostssummary WHERE postid = %d;", $post_ID));
		}
	}

	/**
	 * Purges old post data from summary table.
	 *
	 * @global object $wpdb
	 */
	public function purge_expired_data()
	{
		global $wpdb;
		
		$wpdb->query($wpdb->prepare(
			"DELETE FROM {$wpdb->prefix}popularpostssummary WHERE view_date < DATE_SUB(%s, INTERVAL %d DAY)",
			Helper::curdate(),
			intval($this->options->spv_log_expiry)
		));
	}
}
