<?php

namespace Bunyad\Blocks\Base;
use \WP_Query;

/**
 * Dynamic Blocks Query Class
 */
class Query
{
	protected $block;
	protected $data  = [];
	protected $props = [];
	protected $query_args = [];

	/**
	 * @param Bunyad_Blocks_Base $block
	 */
	public function __construct($props = [], $block = null)
	{
		$this->props = $props;
		$this->block = $block;
	}

	/**
	 * Process props and setup the query
	 * 
	 * @return \WP_Query|null
	 */
	public function setup()
	{
		/**
		 * Applying a heading filter. Set the correct term/cat.
		 */
		if ($this->props['filter']) {
			unset(
				$this->props['cat'],
				$this->props['offset'],
				$this->props['orig_offset']
			);

			$taxonomy = 'category';
			if ($this->props['filters'] === 'tag') {
				$taxonomy = 'post_tag';
			}

			// Only for shortcode yet.
			if ($this->props['filters_tax']) {
				$taxonomy = $this->props['filters_tax'];
			}

			$this->props = array_replace($this->props, [
				'query_type' => 'custom',
				'tax_ids'    => [$this->props['filter']],
				'filters'    => false,
				'taxonomy'   => $taxonomy,
			]);
		}

		/**
		 * Setup and execute the query.
		 */
		if (isset($props['query_args']) && is_array($props['query_args'])) {
			$this->query_args = $props['query_args'];
		}

		if (isset($this->props['cat']) && $this->props['cat'] === '__current') {
			$main = get_queried_object();
			if (is_object($main) && !empty($main->term_id)) {
				$this->props['cat'] = $main->term_id;
			}
		}
		
		$this->query_args = array_replace($this->query_args, [
			'posts_per_page' => (!empty($this->props['posts']) ? intval($this->props['posts']) : 4), 
			'post_status'    => 'publish',
			'order'          => ($this->props['sort_order'] == 'asc' ? 'asc' : 'desc'),
		]);

		$this->setup_query_data();

		// Add a filter
		$query_args  = apply_filters(
			'bunyad_block_query_args', 
			$this->query_args, 
			$this->block, 
			$this->props  // redundant but backward compatibility
		);

		$this->data['query'] = new WP_Query($query_args);
	
		// Disable title if empty
		if (empty($this->data['heading']) && is_object($this->block)) {
			$this->block->props['heading_type'] = 'none';
		}
		
		// Process filters
		$this->process_filters();

		return $this->data;
	}

	/**
	 * Processs and setup the query args and block data.
	 *
	 * @return array
	 */
	public function setup_query_data()
	{
		$props = $this->props;

		/**
		 * Setup internal variables and some legacy aliases.
		 */
		// Main category / taxonomy term object.
		$term      = '';
		$main_term = '';

		$link      = '';
		$title     = $props['heading'];

		/**
		 * Initialize the query args.
		 */
		$query_args = array_replace($this->query_args, [
			'posts_per_page' => !empty($props['posts'])  ? intval($props['posts']) : '',
			'offset'         => !empty($props['offset']) ? $props['offset'] : ''
		]);

		$query_args = array_filter($query_args);
		
		if (isset($props['sticky_posts']) && !$props['sticky_posts']) {
			$query_args['ignore_sticky_posts'] = 1;
		}

		if (!empty($props['skip_posts'])) {
			$offset = !empty($query_args['offset']) ? $query_args['offset'] : '';
			$query_args['offset'] = intval($offset) + intval($props['skip_posts']);
		}
		
		// Add pagination if available.
		if (!empty($props['page'])) {
			$query_args['paged'] = $props['page'];
		}

		/**
		 * Sortng criteria and order.
		 */		
		if (!empty($props['sort_order'])) {
			$query_args['order'] = $props['sort_order'] == 'asc' ? 'asc' : 'desc';
		}

		$sort_by = !empty($props['sort_by']) ? $props['sort_by'] : '';
		switch ($sort_by) {
			case 'modified':
				$query_args['orderby'] = 'modified';
				break;
				
			case 'random':
				$query_args['orderby'] = 'rand';
				break;
	
			case 'comments':
				$query_args['orderby'] = 'comment_count';
				break;
				
			case 'alphabetical':
				$query_args['orderby'] = 'title';
				break;
				
			case 'rating':
				$query_args = array_replace(
					$query_args, 
					[
						'meta_key' => '_bunyad_review_overall', 
						'orderby' => 'meta_value_num'
					]
				);
				break;

			case 'jetpack_views':
				$jetpack_args = $this->get_jetpack_args();
				$query_args   = array_replace($query_args, $jetpack_args);
				break;

			case 'views':
			case 'views-7days':
			case 'views-days':
				$query_args = array_replace($query_args, $this->get_post_views_args());
				break;
		}

		/**
		 * Limit by custom taxonomy?
		 * 
		 * Note: Only makes sense if there are tax_ids provided too as you can't get all 
		 * posts from a taxonomy. That's same as getting all posts, a la posts from all post_tags.
		 * But we have added a fallback since v7.0.1
		 * 
		 * Important: Also used by filters.
		 */
		if (!empty($props['taxonomy'])) {
	
			$_taxonomy = $props['taxonomy'];
			$terms = !empty($props['tax_ids']) ? (array) $props['tax_ids'] : (array) $props['terms'];

			// Backward compat: Add main cat to terms list, if any.
			if (!empty($props['cat'])) {
				array_push($terms, $props['cat']);
			}

			// Get all the terms that belong to this taxonomy if no taxonomy ids specified.
			if (empty($terms)) {
				$_terms = get_terms([
					'taxonomy'   => $_taxonomy, 
					'hide_empty' => false
				]);
				
				if ($_terms) {
					$terms = wp_list_pluck($_terms, 'term_id');
				}
			}

			if (!empty($terms)) {
				$query_args['tax_query'] = [[
					'taxonomy' => $_taxonomy,
					'field'    => 'term_id',
					'terms'    => $terms
				]];

				// Get and configure the main term.
				$term = get_term_by('id', (!empty($props['cat']) ? $props['cat'] : current($terms)), $_taxonomy);
				if (empty($title)) {
					$title = $term->name; 
				}

				$link = get_term_link($term, $_taxonomy);
			}
		}
		else {
			// Terms / cats may have slug strings instead of ids.
			if (!empty($props['terms'])) {
				$terms      = $props['terms'];
				$slug_terms = $this->get_slug_ids($props['terms']);

				if ($slug_terms) {
					$terms = array_merge($props['terms'], $slug_terms);
				}
			}
			else {
				$terms = [];
			}
		
			/**
			 * Got main category/term? Use it for filter, link, and title
			 */
			if (!empty($props['cat'])) {
				
				// Might be an id or a slug
				$term = $category = is_numeric($props['cat']) ? get_category($props['cat']) : get_category_by_slug($props['cat']);
				
				// Category is always the priority main term
				$main_term = $term;
				
				if (!empty($category)) {
					array_push($terms, $category->term_id);
						
					if (empty($title)) {
						$title = $category->cat_name;
					}
				
					if (empty($link)) {
						$link = get_category_link($category);
					}
				}
			}
			
			/**
			 * Filtering by tag(s)?
			 */
			if (!empty($props['tags'])) {

				$tag_ids = $props['tags'];

				// Get ids from slugs, if any.
				$slug_terms = $this->get_slug_ids($props['tags'], 'post_tag');
				if ($slug_terms) {
					$tag_ids = array_merge($tag_ids, $slug_terms);
				}

				if ($tag_ids) {
					$query_args['tag__in'] = $tag_ids;
				}

				// Legacy: Get the first tag for main term, assuming it's a slug.
				$tax_tag = current($props['tags']);
				if (!is_numeric($tax_tag)) {
					$term = get_term_by('slug', $tax_tag, 'post_tag');

					if ($term) {
						// Use the first tag as main term if a category isn't already the main term
						if (!$main_term) {
							$main_term = $term;
						}
						
						if (empty($title)) {
							$title = $term->slug; 
						}
						
						if (empty($link)) {
							$link = get_term_link($term, 'post_tag');
						}
					}
				}
			}
			
			/**
			 * Multiple categories/terms filter
			 */
			if (count($terms)) {
				$query_args['cat'] = join(',', $terms);
				
				// No category as main and no tag either? Pick first category from multi cats.
				if (!$main_term) {
					$main_term = current($terms);
				}
			}
		}

		/**
		 * By specific post IDs?
		 */
		if (!empty($props['post_ids'])) {
			
			$ids = array_map('intval', $props['post_ids']);
			$query_args['post__in'] = $ids;
		}

		/**
		 * Post Formats?
		 */
		if (!empty($props['post_formats'])) {
			
			if (!isset($query_args['tax_query'])) {
				$query_args['tax_query'] = [];
			}

			// Add post format prefix
			$formats = array_map(function($val) {
				return 'post-format-' . trim($val);
			}, $props['post_formats']);
			
			$query_args['tax_query'][] = [
				'taxonomy' => 'post_format',
				'field'    => 'slug',
				'terms'    => (array) $formats,
			];
		}

		/**
		 * Exclude posts IDs.
		 */
		if (!empty($props['exclude_ids'])) {
			$ids = array_map('intval', (array) $props['exclude_ids']);
			$query_args['post__not_in'] = $ids;
		}

		/**
		 * Exclude tags by ids or slugs.
		 */
		if (!empty($props['exclude_tags'])) {

			$tag_ids = $props['exclude_tags'];

			// Get ids from slugs, if any.
			$slug_terms = $this->get_slug_ids($tag_ids, 'post_tag');
			if ($slug_terms) {
				$tag_ids = array_merge($tag_ids, $slug_terms);
			}

			if ($tag_ids) {
				$query_args['tag__not_in'] = $tag_ids;
			}
		}

		/**
		 * Exclude terms (mainly categories) by ids or slugs.
		 */
		if (!empty($props['exclude_terms'])) {
			$exclude_term_ids = $props['exclude_terms'];
			
			// Get ids from slugs, if any.
			$slug_terms = $this->get_slug_ids($exclude_term_ids);
			if ($slug_terms) {
				$tag_ids = array_merge($exclude_term_ids, $slug_terms);
			}

			if ($exclude_term_ids) {
				$taxonomy = $props['taxonomy'] ?: 'category';
				if (!isset($query_args['tax_query'])) {
					$query_args['tax_query'] = [];
				}

				$query_args['tax_query'][] = [
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $exclude_term_ids,
					'operator' => 'NOT IN',
				];
			}
		}
		
		/**
		 * Review Posts Only.
		 */
		if (!empty($props['reviews_only'])) {
			$query_args['meta_key'] = '_bunyad_review_overall';
		}
		
		/**
		 * Custom Post Types?
		 * 
		 * Legacy: Supports multiple post types
		 */
		if (!empty($props['post_type'])) {
			$query_args['post_type'] = array_map('trim', explode(',', $props['post_type']));
		}

		/**
		 * Enable post views additions for Sphere Post Views.
		 */
		if (!empty($this->props['post_views'])) {
			$query_args['views_query'] = $query_args['views_query'] ?? [];
		}

		// Setup accessible variables
		$this->data = array_replace($this->data, [
			'term_link'       => $link,
			'heading'         => $title,
			'query_args'      => $query_args,
			'term'    	      => $main_term,
			'display_filters' => [],
		]);

		$this->query_args = $query_args;
		return $query_args;
	}

	/**
	 * Get term IDs given term slugs of a taxonomy.
	 * 
	 * Note: Numeric slugs will be ignored.
	 * 
	 * @param array $slugs
	 * @param string $taxonomy
	 * @return array
	 */
	protected function get_slug_ids(array $slugs, $taxonomy = 'category')
	{
		$terms      = [];
		$slug_terms = [];

		// Ignore numeric slugs.
		foreach ((array) $slugs as $term) {
			if (!is_numeric($term)) {
				array_push($slug_terms, $term);
			}
		}
		
		// If we have slug terms, get their ids and add.
		if ($slug_terms) {
			$results = get_terms($taxonomy, [
				'slug'         => $slug_terms, 
				'hide_empty'   => false, 
				'hierarchical' => false
			]);

			if ($results) {
				$terms = wp_list_pluck($results, 'term_id');
			}
		}

		return $terms;
	}

	/**
	 * Get Sphere Post Views sort arguments to add to the query.
	 *
	 * @return array
	 */
	public function get_post_views_args()
	{
		// Fallback to comments if plugin not active.
		if (!class_exists('\Sphere\PostViews\Plugin')) {
			return [
				'orderby' => 'comment_count'
			];
		}

		$sort = $this->props['sort_by'];
		$query_args = [
			'orderby' => 'post_views'
		];

		if ($sort !== 'views') {
			switch ($sort) {
				case 'views-7days':
					$days = 7;
					break;

				default:
					$days = $this->props['sort_days'] <= 0 ? 30 : $this->props['sort_days'];
					break;
			}

			$query_args['views_query'] = [
				'range'         => true,
				'time_unit'     => 'day',
				'time_quantity' => $days,
				'add_totals'    => false,
			];
		}

		return $query_args;
	}

	/**
	 * Get query args to fetch posts based on Jetpack Views Counters sort.
	 * 
	 * @uses \stats_get_csv()
	 * @return array Query args to add.
	 */
	public function get_jetpack_args()
	{
		if (!function_exists('\stats_get_csv')) {
			return [];
		}

		/**
		 * Get posts by views from Jetpack stat module (wordpress.com stats)
		 */
		$post_views = \stats_get_csv('postviews', [
			'days'  => absint($this->props['sort_days']),
			'limit' => 100
		]);

		$post_ids   = array_filter(wp_list_pluck((array) $post_views, 'post_id'));
		$query_args = [];

		// No posts found to be sorted by views.
		if (!$post_ids || !count($post_ids)) {

			// Fallback to comment count.
			$query_args['orderby'] = 'comment_count';
		}
		else {
		
			// Use specific posts to get if available.
			$query_args += [
				'offset'   => 0,
				'post__in' => $post_ids, 
				'orderby'  => 'post__in'
			];
		}

		return $query_args;
	}

	/**
	 * Process block filters to be used later in the heading.
	 */
	public function process_filters()
	{
		$props = $this->props;

		if (empty($props['filters']) || !is_object($this->block)) {
			return;
		}

		$display_filters = array();

		/**
		 * Process display filters - supports ids or slugs
		 */
		
		$filters_terms = $props['filters_terms'];
		if (!empty($props['filters_tags'])) {
			$filters_terms = $props['filters_tags'];
		}
		
		// Which taxonomy? Default to category
		$tax = 'category';

		if ($props['filters'] == 'tag') {
			$tax = 'post_tag';	
		}
		// Not implemented in Elementor yet. Shortcode only.
		else if ($props['filters'] == 'taxonomy' && !empty($props['filters_tax'])) {
			$tax = $props['filters_tax'];
		}
		
		// Auto-select 3 sub-cats for category if terms are missing
		if ($tax == 'category' && empty($filters_terms) && is_object($this->data['term'])) {

			$filters_terms = wp_list_pluck(
				get_categories(array(
					'child_of' => $this->data['term']->term_id, 
					'number'   => 3, 
					'hierarchical' => false
				)),
				'term_id'
			);
		}
		
		// Still no filter terms? 
		if (empty($filters_terms)) {
			return;
		}
		
		foreach ($filters_terms as $id) {
			
			// Supports slugs
			if (!is_numeric($id)) {
				$term = get_term_by('slug', $id, $tax);
			}
			else {
				$term = get_term($id);
			}
			
			if (!is_object($term)) {
				continue;
			}
			
			$link = get_term_link($term);
			$display_filters[] = '<li><a href="'. esc_url($link)  .'" data-id="' . esc_attr($term->term_id) . '">'. esc_html($term->name) .'</a></li>';
		}

		return ($this->data['display_filters'] = $display_filters);
	}

	public function get_data()
	{
		return $this->data;
	}
}