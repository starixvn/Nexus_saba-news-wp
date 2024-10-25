<?php

namespace Sphere\PostViews;
use \Bunyad;

/**
 * Add theme compatibility for our ThemeSphere themes.
 */
class ThemeCompat
{
	public function init()
	{
		add_filter('bunyad_block_query_args', [$this, 'add_to_custom_queries'], 10, 2);
		add_action('pre_get_posts', [$this, 'add_to_main_query']);
	}

	/**
	 * Enable post views on main query, mainly for archives.
	 *
	 * @param \WP_Query $query
	 * @return void
	 */
	public function add_to_main_query($query)
	{
		if (!$query->is_main_query() || isset($query->query_vars['views_query'])) {
			return;
		}

		// Views not enabled in global post meta, bail.
		if (
			!in_array('views', Bunyad::options()->post_meta_above)
			&& !in_array('views', Bunyad::options()->post_meta_below)
		) {
			return;
		}

		$query->set('views_query', []);
	}

	/**
	 * Add posts_views for Bunyad blocks queries, when enabled in meta.
	 *
	 * @param array $args
	 * @param Bunyad\Blocks\Base\LoopBlock $block
	 * @return array
	 */
	public function add_to_custom_queries($args, $block)
	{
		if (isset($args['views_query']) || !is_object($block) || empty($block->props)) {
			return $args;
		}

		// Enable views query if post views enabled in meta.
		if (
			in_array('views', $block->props['meta_above']) 
			|| in_array('views', $block->props['meta_below'])
		) {
			$args['views_query'] = [];
		}

		return $args;
	}
}