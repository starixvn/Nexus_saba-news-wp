<?php

namespace Sphere\PostViews;

/**
 * The internal helper API of the plugin.
 */
class Api
{
	private $options;
	private $translate;
	private $cache = [];

	public function __construct(Options $options, Translate $translate)
	{
		$this->options = $options;
		$this->translate = $translate;
	}

	/**
	 * Get post views count for a specific post.
	 * 
	 * @return int
	 */
	public function get_views($post_id)
	{
		global $wpdb;

		// Get translated post id, if needed.
		$post_id = $this->translate->get_object_id(
			$post_id,
			get_post_type($post_id)
		);

		// We don't want to use wp cache which maybe persistent, just memory cache.
		$cache_key = 'views_' . $post_id;
		$views     = $this->cache[$cache_key] ?? false;

		if ($views === false) {

			$query = $wpdb->prepare(
				"SELECT pageviews FROM {$wpdb->prefix}popularpostsdata WHERE postid = %d",
				intval($post_id)
			);

			$result = intval($wpdb->get_var($query));
			$this->cache[$cache_key] = $result;

			$views = $result;
		}

		return apply_filters('sphere/post_views/get_views', $views);
	}

	/**
	 * Set view count for a specific post.
	 *
	 * @param int $post_id
	 * @param int $views
	 * @return int|bool
	 */
	public function update_views($post_id, $views)
	{
		global $wpdb;

		$views   = intval($views);
		$now     = Helper::now();
		$table   = "{$wpdb->prefix}popularposts";

		$result = $wpdb->query($wpdb->prepare(
			"INSERT INTO {$table}data
			(postid, day, last_viewed, pageviews) VALUES (%d, %s, %s, %d)
			ON DUPLICATE KEY UPDATE pageviews = %d, last_viewed = %s",
			$post_id,
			$now,
			$now,
			$views,
			$views,
			$now
		));

		return $result;
	}
}
