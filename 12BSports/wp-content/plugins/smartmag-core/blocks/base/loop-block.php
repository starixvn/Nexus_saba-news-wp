<?php

namespace Bunyad\Blocks\Base;

use \Bunyad;
use \WP_Query;

/**
 * Base Blocks Class for Loops type blocks - should be extended for other blocks
 * 
 * Note: This is not an abstract class and may be used standalone. More of template method.
 */
class LoopBlock extends Block
{
	/**
	 * @var string Block type
	 */
	public $type = 'loop';

	/**
	 * @var boolean Is the block processed yet
	 */
	public $processed = false;

	/**
	 * @var array Internal data, extended by data retrieved from Query::setup()
	 */
	public $data = [
		'unique_id' => null
	];

	/**
	 * @var \WP_Query
	 */
	public $query;

	/**
	 * @var string ID for the view file. Defaults to $this->id.
	 */
	public $view_id;

	/**
	 * @var integer Number of posts rendered so far.
	 */
	protected $rendered_posts = 0;

	/**
	 * @param array $props
	 */
	public function __construct($props = [])
	{
		parent::__construct($props);
		
		// Resolve aliases
		$this->props = $this->resolve_aliases($this->props);

		// Setup enqueues if any
		add_action('wp_enqueue_scripts', [$this, 'register_assets']);
	}

	/**
	 * Get props related to query.
	 *
	 * @return array
	 */
	public static function get_query_props()
	{
		$query_props = [
			'posts'        => 4,
			'offset'       => '',

			// Sticky posts generally only enabled for home/blog archives.
			'sticky_posts' => false,

			// Internal for section queries, and passed 'query' objects.
			// Different from offset: These posts are manually skipped in the loop.
			'skip_posts'   => 0,

			// Main category
			'cat'          => '',

			// Tag slugs - separated by commas. Not in 'terms' to allow limit by tags AND cats.
			'tags'         => [],

			// Categories, or custom taxonomies' term ids, or author ids.
			'terms'         => [],
			'exclude_terms' => [],

			// Limit to a specific custom taxonomy
			'taxonomy'     => '',

			// Custom taxonomy IDs to use
			'tax_ids'      => [],
			'sort_order'   => '',
			'sort_by'      => '',

			// Only for JetPack views sort.
			'sort_days'    => 30,
			'post_formats' => [],

			// Multiple supported only for legacy compat. Recommended: Single value.
			'post_type'    => '',

			// Specific post IDs
			'post_ids'     => [],

			// Skip posts by ids.
			// Special: Also supported for main query.
			'exclude_ids'  => [],

			// Exclude posts by tags, by ids or slugs.
			'exclude_tags' => [],

			'pagination'   => '',

			// Only show review posts if enabled.
			'reviews_only' => false,

			// A custom identifier for programmatically changing things.
			'query_id'     => '',

			// Exclude current post on a single post page.
			'exclude_current' => false,

			// Others that may be used:
			// 'page' => 0
		];

		return $query_props;
	}

	/**
	 * Default properties for the block.
	 * 
	 * @param string $type Type of props to return.
	 * @return array
	 */
	public static function get_default_props()
	{
		// Config props (not for sc)
		$props = [

			// Expected: 
			//  null|empty: Use global $wp_query or legacy $bunyad_loop, ignores all query props
			//  'custom':   Create create based on provided props.
			//  'section':  Use section query. Data must be provided in section_query.
			'query_type'      => '',

			// Forces to use the specified query.
			'query'           => null,

			// WP_Query|array Section query data.
			'section_query'   => [],

			// Whether is it called via a shortcode / builder - adds .block-sc to wrap
			'is_sc_call'      => false,

			// Style Variation for the block.
			'style'         => '',

			// Dark or normal.
			'scheme'        => '',

			// Many blocks support columns setting.
			'columns'        => '',
			'columns_main'   => '',
			'columns_medium' => '',
			'columns_small'  => '',

			'heading'           => '',
			'heading_type'      => '',
			'heading_align'     => 'left',
			'heading_link'      => '',
			'heading_more'      => '',
			'heading_more_text' => '',
			'heading_more_link' => '',
			'heading_colors'    => '',
			'heading_tag'       => 'h4',

			// Values: category, tag, or taxonomy - if empty, defaults to category 
			'filters'       => false,
			'filters_terms' => [],
			'filters_tags'  => [],

			// When using custom taxonomy
			'filters_tax'   => '',

			// Current filter to apply.
			'filter'        => '',

			// Loop specific props
			'excerpts'           => true,
			'excerpt_length'     => 0,
			'excerpt_lines'      => '',
			'cat_labels'         => '',
			'cat_labels_pos'     => '',
			'read_more'          => '',
			'reviews'            => '',
			'show_post_formats'  => true,
			'post_formats_pos'   => '',
			'show_content'       => true,
			'content_center'     => false,

			// When empty, automatically inferred based on columns.
			'show_media'         => true,
			'force_image'        => '',
			'media_ratio'        => '',
			'media_ratio_custom' => '',

			// Only for some blocks: list, small.
			'media_width'        => '',
			'media_style_shadow' => '',

			// Define a parent container to get relative width from.
			'container_width' => '',

			'separators'      => false,
			'separators_cols' => false,

			// Margin below block
			'space_below'     => '',
			'column_gap'      => '',

			'meta_items_default' => true,
			'meta_above'         => [],
			'meta_below'         => [],
			'show_title'         => true,
			'title_tag'          => 'h2',
			'title_lines'        => '',

			// These meta items should not get a default value as global settings are important.
			'meta_align'         => '',
			'meta_cat_style'     => 'text',
			'meta_author_img'    => false,

			// Pagination settings.
			'pagination_type'   => 'numbers-ajax',
			'load_more_style'   => '',
			'pagination_links'  => true,
			
			// Pagination may be enabled, but may not be always rendered, such as for multi-blocks.
			'pagination_render' => true,

			// Number of posts to skip lazyload image for.
			'skip_lazy_number'  => 0,

			// Legacy Aliases (DEPRECATED)
			'link'         => ['alias' => 'heading_link'],
			'post_format'  => ['alias' => 'post_formats'],
			'title'        => ['alias' => 'heading'],
			'cats'         => ['alias' => 'terms'],
		];

		$props = $props + static::get_query_props();

		return $props;
	}

	/**
	 * Add in some values from global options.
	 */
	public function map_global_props($props)
	{
		$global = [
			'cat_labels'         => Bunyad::options()->get('cat_labels'),
			'cat_labels_pos'     => Bunyad::options()->get('cat_labels_pos'),
			'reviews'            => Bunyad::options()->get('loops_reviews'),
			'post_formats_pos'   => Bunyad::options()->get('post_formats_pos'),
			'load_more_style'    => Bunyad::options()->get('load_more_style'),
			'meta_cat_style'     => Bunyad::options()->get('post_meta_cat_style'),
			'media_style_shadow' => Bunyad::options()->get('loops_media_style_shadow'),
		];

		// Only set this default for listings/internal calls. Blocks/SC do not use the global
		// setting for these.
		if (empty($props['is_sc_call'])) {
			$global += [
				'show_post_formats' => Bunyad::options()->get('post_format_icons'),
			];
		}

		// Setting it as it will be frequently used by inheritance too.
		$props['meta_items_default'] = !isset($props['meta_items_default']) || $props['meta_items_default'];

		// If not known or explicitly set to default, global meta should be used.
		if ($props['meta_items_default']) {
			$global += [
				'meta_above'  => Bunyad::options()->get('post_meta_above'),
				'meta_below'  => Bunyad::options()->get('post_meta_below'),
			];
		}

		return array_replace($global, $props);
	}

	/**
	 * @inheritDoc 
	 */
	public function init()
	{
		// Setup internal props. These are not set from external call.
		$this->props += [
			'image'        => '',
			'image_props'  => [],
			'class_grid'   => ['grid'],

			// Not all support it yet.
			'class'        => [],

			// Loop wrapper div attributes. Some loops use it, not all.
			'wrap_attrs'   => [],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function setup_props(array $props) 
	{
		$props = parent::setup_props($props);

		if (isset($props['columns_main'])) {
			$props['columns'] = $props['columns_main'];
		}

		// Clean up section_query props by only using valid.
		if (isset($props['section_query'])) {

			$props['section_query_type'] = isset($props['section_query']['query_type']) ? $props['section_query']['query_type'] : '';
			$props['section_query'] = array_intersect_key(
				$props['section_query'],
				$this->get_query_props()
			);
		}
		else if (isset($props['query_type']) && $props['query_type'] === 'section') {
			// section_query doesn't exist, so remove it.
			$props['query_type'] = 'custom';
		}

		return $props;
	}

	/**
	 * Set the block identifier
	 * 
	 * @return $this
	 */
	public function set_id($id) 
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * Set a single property - sets on $this->props[] array
	 * 
	 * @return $this
	 */
	public function set($key, $value) 
	{
		$this->props[$key] = $value;
		return $this;
	}

	/**
	 * Get all props.
	 * 
	 * @see $this::get_default_props()
	 * @return array
	 */
	public function get_props($original = false)
	{
		if ($original) {
			return $this->orig_props;
		}

		return $this->props;
	}

	/**
	 * Setup aliases for provided props.
	 */
	public function resolve_aliases($props)
	{
		$default = $this->get_default_props();

		foreach ($default as $key => $prop) {

			// Is not an alias? Skip
			if (!is_array($prop) OR !array_key_exists('alias', $prop)) {
				continue;
			}
			
			// Alias used here
			if (array_key_exists($key, $props)) {

				// A stray config prop, remove it
				if (!empty($props[$key]['alias'])) {

					unset($props[$key]);
					continue;
				}

				// Example: 'terms' set from $props['cats']
				$props[ $prop['alias'] ] = $props[$key];
			}
		}

		return $props;
	}

	/**
	 * Any assets to register for this block.
	 */
	public function register_assets() {}

	/**
	 * Process and setup query.
	 * 
	 * @uses \Bunyad\Blocks\Base\Query
	 */
	public function process()
	{
		$this->create_unique_id();

		// Currently applying a filter. Query type can only be custom.
		if ($this->props['filter']) {
			$this->props = array_replace($this->props, [
				'query'      => '',
				'query_type' => 'custom'
			]);
		}

		/**
		 * Determine current page based on main query or provided paged param in AJAX.
		 * Only done if pagination is enabled.
		 */
		if ($this->props['pagination']) {
			$page = (get_query_var('paged') ? get_query_var('paged') : get_query_var('page'));
			if (empty($page) && isset($_REQUEST['paged'])) {
				$this->props['page'] = intval($_REQUEST['paged']);
			}
			else {
				$this->props['page'] = intval($page);
			}
		}

		/**
		 * Query type: Global or a custom defined.
		 */
		// Section query may be using main query, instead of a custom one.
		if ($this->props['query_type'] === 'section' && $this->props['section_query_type'] === 'main-custom') {
			$this->props['query_type'] = 'main-custom';
			$this->props = array_replace(
				$this->props,
				(array) $this->props['section_query']
			);
		}

		// This query type re-executes main query, with support for some extra query props.
		if ($this->props['query_type'] === 'main-custom') {
			// Remove unsupported.
			$this->props = array_diff_key(
				$this->props, 
				array_flip([
					'cat',
					'terms',
					'taxonomy',
					'tax_ids',
					'post_type',
					'exclude_terms',
					'exclude_ids',
					'exclude_current',
					'exclude_tags',
				])
			);

			$this->props['query'] = $GLOBALS['wp_query'];
		}

		// Global query for 'main' or if no query_type is defined.
		if (
			$this->props['query_type'] === 'main'
			|| (!$this->props['query_type'] && !$this->props['query'])
		) {
			$this->props['query'] = !empty($GLOBALS['bunyad_loop']) ? $GLOBALS['bunyad_loop'] : $GLOBALS['wp_query'];
		}

		/**
		 * Use a pre-defined query. May also be the main query (defined above).
		 * 
		 * Note: This is different from an undefined 'query', which fallbacks to global (core archives).
		 * 
		 * @todo refactor to MainQuery.
		 */
		if ($this->props['query'] instanceof WP_Query) {

			// Clone to prevent modification on original query object.
			// As it may be re-used again, modifications such as post_count below would
			// affect the object passed by ref.
			$this->query = clone $this->props['query'];

			/** @var WP_Query $query */
			$query = $this->query;
			$new_args = [];
			
			/**
			 * For Main custom query, we'll add or replace query_vars based on provided props
			 * and re-execute the current main wp_query.
			 */
			if ($this->props['query_type'] === 'main-custom') {
				$query_process = new Query(array_replace(
					$this->props,
					[
						// Enable sticky posts unless disabled in original query.
						'sticky_posts' => empty($query->query_vars['ignore_sticky_posts']) ? true : false
					]
				));

				$new_args   = $query_process->setup_query_data();
				$this->data = array_replace($this->data, $query_process->get_data());

			} 
			/**
			 * Fallback to WordPress main/global query. Mainly for native archives.
			 */
			else if ($this->props['query']->is_main_query()) {

				// Exclude IDs can also be used on main query, ex. skip archive featured posts.
				if ($this->props['exclude_ids']) {
					$vars = &$query->query_vars;

					$new_args['post__not_in'] = array_merge(
						isset($vars['post__not_in']) ? (array) $vars['post__not_in'] : [],
						$this->props['exclude_ids']
					);
				}
			}
			/**
			 * Used for a mixed internal block, mega menu etc. Or for blocks that call 
			 * another block and use same query, like news focus and highlights.
			 * 
			 * - Change post_count to limit to post number specified.
			 */
			else {
				
				$do_skip_posts = $this->props['skip_posts'];

				// Adjust posts if number of posts specified.
				if ($this->props['posts']) {

					if ($query->post_count > $this->props['posts']) {
						$query->post_count = $this->props['posts'];
					} 
					else if ($query->post_count < $this->props['posts']) {
						$new_args['post_per_page'] = $this->props['posts'];
					}
				}
			}

			// Pagination fix for all queries.
			if (!$this->props['pagination'] && $query->get('paged') > 1) {
				$new_args['paged'] = 1;
			}

			/**
			 * Re-execute the global query with added vars, if needed. This is done for:
			 *  - 'main-custom': Always.
			 *  - Global Main query: If exclude_ids provided.
			 *  - Others: If more posts are required than in the original query, pagination fix etc.
			 */
			if (count($new_args)) {
				/**
				 * We use the same filter in Query::setup(), so to remain consistent do the same when the
				 * pre-provided query has to be re-executed.
				 * 
				 * Note: We don't always run this filter as that would cause issues with no duplicate posts
				 * feature. 
				 */
				$new_args = apply_filters(
					'bunyad_block_query_args', 
					array_replace($query->query_vars, $new_args), 
					$this
				);
				$query->query($new_args);
			}

			// Just sets the internal pointer to skip over some posts. Used internally.
			if (!empty($do_skip_posts)) {
				$query->current_post = $this->props['skip_posts'] - 1;
			}

			$this->query = $query;

			// Setup data required to match Query::setup().
			if (!$this->data) {
				$this->data = array_replace($this->data, [
					'heading'    => $this->props['heading'],
					'term_link'  => '',
					'term'       => '',
					'display_filters' => [],
				]);
			}

		} else {

			// Skip current post.
			if ($this->props['exclude_current'] && is_single()) {
				$this->props['exclude_ids'] = array_merge(
					isset($this->props['exclude_ids']) ? (array) $this->props['exclude_ids'] : [],
					[get_the_ID()]
				);
			}

			// All the other type of queries.
			switch ($this->props['query_type']) {
				case 'custom':
					// Setup the block query
					$query = new Query($this->props, $this);
					$this->data = array_merge($this->data, $query->setup());

					$this->query = $this->data['query'];
					unset($this->data['query']);

					break;
					
				case 'section':
					$query = $this->props['section_query'];

					if (!is_object($query)) {
						$query_props = array_replace(
							$this->props,
							(array) $this->props['section_query']
						);

						// Add posts limit.
						$query_props = array_replace($query_props, [
							'posts'  => $this->props['posts'],
						]);

						$query = new Query($query_props);
					}
					
					$this->data  = array_merge($this->data, $query->setup());
					$this->query = $this->data['query'];

					break;
			}
		}

		// Flag to mark processed
		$this->processed = true;
	}

	/**
	 * Render the partial view for this loop.
	 * 
	 * @uses \Bunyad_Core::partial()
	 */
	public function render($options = array())
	{
		$options = wp_parse_args($options, array(
			'block_markup' => true
		));

		/**
		 * Run an action before rendering the loop block.
		 * 
		 * @param self $this
		 */
		do_action('bunyad_blocks_loop_render', $this);

		$this->_pre_render();
		$this->setup_media_ratio();
		$this->infer_image_sizes();

		/**
		 * Filter block image before.
		 * 
		 * @param string $image Current image.
		 * @param string $id    Block id.
		 */
		$this->props['image'] = apply_filters('bunyad_blocks_loop_image', $this->props['image'], $this->id);

		if (!$this->processed) {
			$this->process();
		}

		// Odd case.
		if (!$this->query) {
			return;
		}

		// Render with or without block markup
		($options['block_markup'] ? $this->render_block_markup() : $this->render_view());

		// Restore post data as views will override it.
		wp_reset_postdata();

		/**
		 * Run an action after rendering the loop block.
		 * 
		 * @param self $this
		 */
		do_action('bunyad_blocks_loop_render_after', $this);
	}

	/**
	 * Checks to perform and settings to do prior to render
	 */
	public function _pre_render() {}

	/**
	 * Render the view file for this block - usually a loop
	 */
	public function render_view()
	{
		$view_id = $this->view_id ? $this->view_id : $this->id;

		Bunyad::core()->partial(
			'blocks/loops/' . $view_id . '/html/' . $view_id,
			[
				'block' => $this,
				'query' => $this->query
			]
		);
	}

	/**
	 * Get block attributes for rendering the block markup.
	 * 
	 * @return array
	 */
	public function get_block_wrap_attribs()
	{
		$classes = [
			'block-wrap', 
			'block-' . $this->id, 
			$this->props['is_sc_call'] ? 'block-sc' : '',

			// Preset column gaps.
			$this->props['column_gap'] ? 'cols-gap-' . $this->props['column_gap'] : '',

			// Dark scheme class if active.
			$this->props['scheme'] === 'dark' ? 's-dark' : '',

			// Margins below class.
			$this->props['space_below'] ? 'mb-' . $this->props['space_below'] : '',

			// Media shadows. Note: It's best done in wrapper instead of loop div for style consistency.
			$this->props['media_style_shadow'] && $this->props['media_style_shadow'] !== 'none' ? 'has-media-shadows' : '',
		];

		$attribs = [
			'class'   => $classes, 
			'data-id' => $this->data['unique_id']
		];

		/**
		 * Add block query data for dynamic pagination or blocks with filters.
		 */
		$ajax_pagination = in_array(
			$this->props['pagination_type'], 
			['load-more', 'numbers-ajax', 'infinite']
		);
		
		if ($this->props['filters'] || ($this->props['pagination'] && $ajax_pagination)) {
			$block_data = [
				'id'    => $this->id,
				'props' => $this->orig_props,
			];

			unset($block_data['props']['section_query_type']);

			// Archive, or other main query will require extra data from the original query
			// for AJAX pagination.
			if (!in_array($this->props['query_type'], ['custom', 'section'])) {

				$term_data  = $this->query->get_queried_object();
				if (is_object($term_data)) {
					$block_data['props'] += [
						'post_type'    => $this->query->get('post_type'),
						'posts'        => $this->query->get('posts_per_page'),
						'sticky_posts' => !$this->query->get('ignore_sticky_posts'),
						'taxonomy'     => !empty($term_data->taxonomy) ? $term_data->taxonomy : '',
						'terms'        => !empty($term_data->term_id) ? $term_data->term_id : '',
					];
				}
			}
			
			$attribs['data-block'] =  json_encode($block_data);
		}

		return $attribs;
	}

	/**
	 * Output common block markup and render the specific view file.
	 * 
	 * Note: Do not call directly, use render() instead.
	 * 
	 * @uses self::render_view()
	 */
	public function render_block_markup()
	{
		?>
		<section <?php 
			Bunyad::markup()->attribs(
				$this->id .'-block', 
				$this->get_block_wrap_attribs()
			); ?>>

			<?php $this->the_heading(); ?>
	
			<div class="block-content">
				<?php $this->render_view(); ?>
			</div>

		</section>
		<?php
	}

	/**
	 * Created unique ID for the block
	 */
	protected function create_unique_id()
	{
		Bunyad::registry()->block_count++;
		$this->data['unique_id'] = Bunyad::registry()->block_count;
	}

	/**
	 * Output block heading markup
	 */
	public function the_heading()
	{
		// Custom heading HTML set progrmmatically (not via prop), use this instead.
		if (!empty($this->data['heading_custom'])) {
			echo $this->data['heading_custom']; // phpcs:ignore WordPress.Security.EscapeOutput Only settable internally programmatically. 
			return;
		}

		// This check is also performed in Heading block. Done here to save resources.
		if (empty($this->data['heading']) || $this->props['heading_type'] === 'none') {
			return;
		}

		Bunyad::blocks()->load(
			'Heading',
			[
				'heading'       => $this->data['heading'],
				'align'         => $this->props['heading_align'],
				'type'          => $this->props['heading_type'],
				'link'          => $this->props['heading_link'] ? $this->props['heading_link'] : $this->data['term_link'],
				'term'          => $this->data['term'],
				'filters'       => $this->data['display_filters'],
				'more'          => $this->props['heading_more'],
				'more_text'     => $this->props['heading_more_text'],
				'more_link'     => $this->props['heading_more_link'] ? $this->props['heading_more_link'] : $this->data['term_link'],
				'accent_colors' => $this->props['heading_colors'],
				'html_tag'      => $this->props['heading_tag'],
			]
		)
		->render();
	}

	/**
	 * Load a loop post class
	 * 
	 * Example: Load the loop class from inc/loop-posts/grid.php if it exists 
	 * or fallback to inc/loop-posts/base.php
	 * 
	 * @uses \Bunyad\Blocks\Helpers::load_post()
	 * 
	 * @param string $id     Loop post id
	 * @param array  $props  Props to set for loop post
	 *
	 * @return \Bunyad\Blocks\LoopPosts\BasePost
	 */
	public function loop_post($id, $props = array()) 
	{
		$this->rendered_posts++;

		$props = array_replace($this->get_props(), $props);
		$props['loop_number'] = $this->rendered_posts;

		// Load post
		$post = Bunyad::blocks()->load_post($id, $props);
		$post->block = $this;

		return $post;
	}

	/**
	 * Get relative width for current block, based on parent column width in 
	 * relation to the whole page.
	 * 
	 * @return float Column width in percent number, example 66
	 */
	public function get_relative_width()
	{
		// Container defined in props, force it
		if (!empty($this->props['container_width'])) {
			return floatval($this->props['container_width']);
		}

		return Bunyad::blocks()->get_relative_width();
	}

	/**
	 * Set columns size based on provided columns.
	 *
	 * @param array $args Configs for columns of devices.
	 * @return void
	 */
	protected function setup_columns($args = []) 
	{
		if (empty($this->props['columns'])) {
			$this->props['columns'] = 1;
		}

		// Add the grid columns class.
		$this->props['class_grid'][] = 'grid-' . $this->props['columns'];

		$col_types = [
			'md' => 'medium', 
			'sm' => 'small', 
			'xs' => 'xsmall'
		];

		/**
		 * Add responsive column classes based on props of type columns_medium etc.
		 * OR, override via args provided.
		 */
		foreach ($col_types as $key => $columns) {

			$cols = null;
			if (!empty($this->props[ 'columns_' . $columns ])) {
				$cols = $this->props[ 'columns_' . $columns ];
			}
			else if (!empty($args[ $columns ])) {
				$cols = $args[ $columns ];
			}

			if ($cols) {
				array_push(
					$this->props['class_grid'], 
					"{$key}:grid-{$cols}"
				);
			}
		}

		 // 1/3 * 12 to get col-4
		 $column = (1 / absint($this->props['columns']) * 12);
		 $this->props['col_class'] = 'col-' . str_replace('.', '-', $column);
	}

	/**
	 * Setup media ratio if provided.
	 */
	public function setup_media_ratio()
	{
		if (empty($this->props['media_ratio'])) {
			return;
		}

		$ratio = $this->props['media_ratio'];
		if ($ratio === 'custom' && !empty($this->props['media_ratio_custom'])) {
			$ratio = $this->props['media_ratio_custom'];
		}

		$this->props['media_ratio'] = $ratio;
	}

	/**
	 * Decide the image to use for this block
	 */
	public function infer_image_sizes()
	{
		// If an image is forced, don't bother setting it
		if (!empty($this->props['image'])) {
			return;
		}

		$this->props['image'] = 'bunyad-thumb';
	}
	

	/**
	 * Render pagination for current block
	 * 
	 * @uses \Bunyad_Core::partial()
	 * @param array $props
	 */
	public function the_pagination($props = [])
	{
		if (!$this->props['pagination'] || !$this->props['pagination_render']) {
			return;
		}

		$props = array_merge(
			[
				'query'            => $this->query,
				'page'             => $this->props['page'],
				'pagination'       => $this->props['pagination'],
				'pagination_type'  => $this->props['pagination_type'],
				'load_more_style'  => $this->props['load_more_style'],
				'pagination_links' => $this->props['pagination_links']
			],
			$props
		);

		// AJAX pagination is not possible in author and search archives, yet.
		if (is_author() || is_search()) {
			$props['pagination_type'] = 'numbers';
		}

		Bunyad::core()->partial('partials/pagination', $props);
	}

}