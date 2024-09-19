<?php
/**
 * Functionality related to archives.
 */
class Bunyad_Theme_Archives
{
	protected $skip_archive_posts;

	public function __construct()
	{
		// Add custom category per_page limits, if any
		add_filter('pre_get_posts', array($this, '_add_category_limits'));
	}

	/**
	 * Get heading for the current archive.
	 * 
	 * @uses get_the_archive_title
	 * @return string|boolean The heading or false.
	 */
	public function get_heading()
	{
		global $wp_query;

		if (is_home() || is_front_page()) {
			return false;
		}

		$heading = get_the_archive_title();
		
		if (is_category() || is_tag() || is_tax()) {
			if (!Bunyad::options()->archive_title) {
				return false;
			}

			$heading = sprintf(
				Bunyad::options()->archive_title_format ?: '%s',
				'<span>' . esc_html(single_term_title('', false)) . '</span>'
			);
		}
		else if (is_search()) {
			$heading = sprintf(
				esc_html__('Search Results: %s (%d)', 'bunyad'),
				'<span>' . esc_html(get_search_query()), 
				intval($wp_query->found_posts) . '</span>'
			); 
		}

		return $heading;
	}

	/**
	 * Gets the default loop for current archive.
	 */
	public function get_default_loop()
	{
		$loop = Bunyad::options()->archive_loop;

		if (is_search()) {
			$loop = Bunyad::options()->search_loop;
		}
		else if (is_author()) {
			$loop = Bunyad::options()->author_loop;
		}
		else if (is_category()) {
			$loop = Bunyad::options()->category_loop;
		}

		if (!$loop || $loop === 'custom') {
			$loop = 'grid-2';
		}

		return $loop;
	}

	/**
	 * Process props to add in some magic.
	 */
	public function process_props($props)
	{
		// Overlay auto-change media ratio.
		if (
			in_array($props['loop'], ['overlay-3', 'overlay-4'])
			 && !Bunyad::options()->loop_overlay_media_ratio
		) {
			$props['loop_args'] += ['media_ratio' => '3-4'];
		}

		// Skip archive posts.
		if ($this->skip_archive_posts) {
			$props['loop_args']['exclude_ids'] = $this->skip_archive_posts;
		}
		
		return $props;
	}

	/**
	 * Record featured area posts to later skip from the main query via process_props.
	 *
	 * @param  \WP_Query $query
	 * @return void
	 */
	public function cat_featured_posts_skip($query) 
	{
		// Categories featured posts skip in feed.
		if (!Bunyad::options()->category_featured_skip) {
			return;
		}

		$this->skip_archive_posts = wp_list_pluck($query->posts, 'ID');
	}

	/**
	 * Filter callback: Add custom per page limits where set for individual category
	 * 
	 * @param \WP_Query $query
	 */
	public function _add_category_limits($query)
	{
		// bail out if incorrect query
		if (is_admin() || !$query->is_category() || !$query->is_main_query()) {
			return $query;
		}

		$category = $this->get_query_cat($query);

		if ($category) {
			// Category meta.
			$cat_meta = Bunyad::posts()->term_meta(null, $category->term_id);
			
			// Set user-specified per page.
			if (!empty($cat_meta['per_page'])) {
				$query->set('posts_per_page', intval($cat_meta['per_page']));
			}
		}
		
		return $query;
	}

	/**
	 * Get the category from query.
	 * 
	 * @param \WP_Query $query
	 * @return object|false
	 */
	protected function get_query_cat($query)
	{
		// For id based permalinks, first should work.
		if ($query->get('cat')) {
			$category = get_category($query->get('cat'));
		}
		else {
			$category = get_category_by_slug($query->get('category_name'));	
		}

		return $category;
	}
}

// init and make available in Bunyad::get('archives')
Bunyad::register('archives', array(
	'class' => 'Bunyad_Theme_Archives',
	'init' => true
));