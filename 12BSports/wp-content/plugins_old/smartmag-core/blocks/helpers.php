<?php

namespace Bunyad\Blocks;
use Bunyad;

/**
 * Helper methods for Blocks & Listing Archives
 */
class Helpers 
{
	/**
	 * @var \Bunyad\Blocks\Ajax
	 */
	public $ajax;

	public function __construct()
	{
		$this->ajax = new Ajax;
		$this->ajax->init();
	}
	
	/**
	 * Load a block 
	 * 
	 * Usage: 
	 *  load('Loops\Grid');
	 * 
	 * @param string $block  Block identifier
	 * @param array  $atts   Attributes for the block
	 * 
	 * @return Base\LoopBlock|bool
	 */
	public function load($block, $atts = [])
	{
		/**
		 * Locate and initialize the loop post.
		 */
		$class = '\\' . __NAMESPACE__ . '\\' . $block;

		// Fallback to base if not present, for Loops.
		if (!class_exists($class)) {
			if (!strstr($block, 'Loops')) {
				return false;
			}

			$class = __NAMESPACE__ . '\\' . 'Base\LoopBlock';
		}

		/** @var Base\LoopBlock $obj */
		$obj = new $class($atts);

		if (!$obj->id) {
			$obj->set_id($block);
		}

		return $obj;
	}

	/**
	 * Load legacy loops - for archives, cats, tags etc.
	 * 
	 * @return Base\LoopBlock
	 */
	public function load_loop($loop, $props = array()) 
	{
		// Some sane defaults for general loops.
		$props = array_replace([
			'pagination'      => true,
			'pagination_type' => Bunyad::options()->pagination_type,
			'space_below'     => 'none',
		], $props);

		// Set column for ids like loop-3
		if (preg_match('/([a-z\-]+)\-(\d)$/i', $loop, $match)) {
			$loop             = $match[1];
			$props['columns'] = $match[2];
		}

		// @deprecated Old key maps, just in case.
		$map = array(
			'loop'       => 'grid',
			'loop-alt'   => 'posts-list',
			
			// Still needed.
			'classic'    => 'large',
		);

		// Classic requires large_style
		if ($loop === 'classic') {
			$props['large_style'] = 'legacy';
		}

		$id = (array_key_exists($loop, $map) ? $map[$loop] : $loop);
		$id = 'Loops\\' . Bunyad::file_to_class_name($id);

		$block = $this->load($id, $props);

		return $block;
	}

	/**
	 * Load a loop post class
	 * 
	 * Example: Load the loop class from inc/loop-posts/grid.php if it exists 
	 * or fallback to inc/loop-posts/post.php
	 * 
	 * @param string $id     Loop post id
	 * @param array  $props  Props to set for loop post
	 *
	 * @return LoopPosts\BasePost
	 */
	public function load_post($id, $props = array()) 
	{
		/**
		 * Locate and initialize the loop post
		 */
		$class = __NAMESPACE__ . '\LoopPosts\\' . Bunyad::file_to_class_name($id) . 'Post';

		// Fallback to base if not present
		if (!class_exists($class)) {
			$class = __NAMESPACE__ . '\LoopPosts\BasePost';
		}

		/** @var LoopPosts\BasePost $obj */
		$obj = new $class($props);

		return $obj;
	}
		
	/**
	 * Get HTML for category label with link. 
	 * 
	 * Checks global and local settings before generating the output.
	 *
	 * @uses  Bunyad::registry()->block_atts  The current block attribs in registry
	 * @param  boolean $options 
	 * @return string|void  HTML with category label
	 */
	public function cat_label($options = [], $post_id = false)
	{
		$options = wp_parse_args($options, [
			'force_show' => false,
			'position'   => ''
		]);		

		$position = $options['position'] ? 'p-' . $options['position'] : '';
		$output   = sprintf(
			'<span class="cat-labels cat-labels-overlay c-overlay %1$s">
				%2$s
			</span>
			',
			esc_attr($position),
			$this->get_categories()
		);
		
		return apply_filters('bunyad_blocks_cat_label', $output);
	}

	/**
	 * Categories for meta.
	 * 
	 * @param boolean|null $all  Display primary/one category or all categories.
	 * @return string Rendered HTML.
	 */
	public function get_categories($all = null, $post_id = false)
	{
		// Object has category taxonomy? i.e., is it a post or a valid CPT?
		if (!is_object_in_taxonomy(get_post_type($post_id), 'category')) {
			return;
		}

		$categories = apply_filters('the_category_list', get_the_category($post_id), $post_id);
		$output     = [];

		// Not showing all categories.
		if (!$all) {
			$category = $this->get_primary_cat();

			$categories = [];
			if (is_object($category)) { 
				$categories[] = $category;
			}
		}
		
		foreach ($categories as $category) {

			$classes = ['category'];
			if (Bunyad::options()->cat_labels_use_colors) {
				$classes[] = 'term-color-' . $category->term_id;
			}

			$output[] = sprintf(
				'<a href="%1$s" class="%2$s" rel="category">%3$s</a>',
				esc_url(get_category_link($category)),
				esc_attr(join(' ', $classes)),
				esc_html($category->name)
			);
		}

		return join(' ', $output);
	}

	/**
	 * Get primary category for a post.
	 *
	 * @param int $post_id
	 * @return object|WP_Error|null
	 */
	public function get_primary_cat($post_id = null)
	{
		// Object must have category taxonomy? i.e., is it a post or a valid CPT.
		if (!is_object_in_taxonomy(get_post_type($post_id), 'category')) {
			return;
		}

		// Primary category defined.
		if (($cat_label = Bunyad::posts()->meta('cat_label', $post_id))) {
			$category = get_category($cat_label);
		}

		// Fallback to using Yoast if available.
		if (empty($category) && Bunyad::options()->yoast_primary_cat && function_exists('yoast_get_primary_term_id')) {
			$id = yoast_get_primary_term_id();
			$category = $id ? get_term($id) : null;
		}
		
		// This test is needed even if a primary cat is defined to test for its
		// existence (it might be deleted etc.)
		if (empty($category)) {
			$category = current(get_the_category($post_id));
		}

		return apply_filters('bunyad_get_primary_cat', $category);
	}	

	/**
	 * Get relative width for current block, based on sidebar or data stored 
	 * in the registry.
	 * 
	 * @return float Column width in percent number, example 66.
	 */
	public function get_relative_width()
	{
		// Set current column width weight (width/100) - used to determine image sizing 
		$col_relative_width = 1;
		if (isset(Bunyad::registry()->layout['col_relative_width'])) {
			$col_relative_width = Bunyad::registry()->layout['col_relative_width'];
		}
	
		// Adjust relative width if there's a sidebar
		if (Bunyad::core()->get_sidebar() !== 'none') {
			$col_relative_width = ($col_relative_width * (8/12));
		}

		return $col_relative_width * 100;
	}
}

// init and make available in Bunyad::get('blocks')
Bunyad::register('blocks', array(
	'class' => '\Bunyad\Blocks\Helpers',
	'init' => true
));
