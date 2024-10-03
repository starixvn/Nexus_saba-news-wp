<?php

namespace Sphere\PostViews\Query;

/**
 * Setup the query interception and modifications.
 * 
 * @author ThemeSphere
 */
class Setup
{
	public function __construct()
	{
		// Runs before pre_query.
		add_action('pre_get_posts', [$this, 'enable_admin_table_query'], 1);
		
		add_action('pre_get_posts', [$this, 'pre_query'], 1);
		add_filter('query_vars', [$this, 'query_vars']);
	}

	/**
	 * Extend query with post_views orderby parameter.
	 *
	 * @param \WP_Query $query
	 * @return void
	 */
	public function pre_query($query)
	{
		$orderby = isset($query->query_vars['orderby']) && $query->query_vars['orderby'] === 'post_views';
		
		if (!$orderby && !isset($query->query_vars['views_query'])) {
			return;
		}

		$spv_query = new Query($query, [
			'orderby' => $orderby,
		]);

		// Store the object in the query to reference later, if needed.
		$query->spv_query = $spv_query;

		// Setup the query.
		add_filter('posts_clauses', [$spv_query, 'modify_query'], 9, 2);
	}

	/**
	 * Enable for admin columns query.
	 * 
	 * @param \WP_Query $query
	 */
	public function enable_admin_table_query($query)
	{
		if (is_admin() && did_action('load-edit.php')) {
			$post_types = apply_filters('sphere/post_views/post_types', ['post']);

			// wp-admin/admin.php setups for edit.php
			$current = $GLOBALS['typenow'];

			if (in_array($current, $post_types) && !$query->get('views_query')) {
				$query->query_vars['views_query'] = [];
			}
		}
	}

	/**
	 * Register views_query var for making it accessible publicly.
	 *
	 * @param array $query_vars
	 * @return array
	 */
	public function query_vars($query_vars)
	{
		$query_vars[] = 'views_query';
		return $query_vars;
	}
}