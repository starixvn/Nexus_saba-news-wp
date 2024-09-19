<?php
/**
 * Theme functionalities related to search.
 */
class Bunyad_Theme_Search
{
	public function __construct()
	{
		// Perform the after_setup_theme.
		add_action('after_setup_theme', [$this, 'init'], 12);
	}

	public function init()
	{
		// Add support for live search.
		add_action('wp_ajax_bunyad_live_search', [$this, 'live_search']);
		add_action('wp_ajax_nopriv_bunyad_live_search', [$this, 'live_search']);

		// Fix WPML search - WPML doesn't select the archive page but uses page_on_front instead
		add_filter('template_include', [$this, 'fix_wpml_search'], 11);

		// Limit search to posts?
		if (Bunyad::options()->search_posts_only) {
			add_filter('pre_get_posts', [$this, 'limit_search']);
		}
	}

	/**
	 * Action callback: AJAX handler for live search results
	 */
	public function live_search()
	{
		get_template_part('partials/live-search');

		// terminate ajax request
		wp_die();
	}

	/**
	 * Filter callback: WPML doesn't select correct template for archives, modify at template_include
	 * 
	 * @param string $template
	 * @return string
	 */
	public function fix_wpml_search($template = '')
	{
		if (!defined('ICL_LANGUAGE_CODE') || !function_exists('icl_get_default_language')) {
			return $template;
		}
		
		if (is_search() /* OR is_archive() */) {
			return get_query_template('index');
		}
		
		return $template;
	}

	/**
	 * Filter callback: Fix search by limiting to posts
	 * 
	 * @param object $query
	 */
	public function limit_search($query)
	{
		if (!$query->is_search || !$query->is_main_query()) {
			return $query;
		}

		// Ignore if on bbpress and woocommerce.
		// is_woocommerce() cause 404 due to using get_queried_object()
		if (
			is_admin() 
			|| (function_exists('is_bbpress') && is_bbpress()) 
			|| (function_exists('is_shop') && is_shop())
		) {
			return $query;
		}
		
		// limit it to posts
		$query->set('post_type', 'post');
		
		return $query;
	}
}

// init and make available in Bunyad::get('search')
Bunyad::register('search', [
	'class' => 'Bunyad_Theme_Search',
	'init'  => true
]);