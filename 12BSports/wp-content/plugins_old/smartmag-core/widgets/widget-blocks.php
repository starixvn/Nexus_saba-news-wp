<?php
/**
 * A super widget to add theme blocks in the sidebar
 */
class SmartMag_Widgets_Blocks extends WP_Widget
{
	public function __construct()
	{
		parent::__construct(
			'bunyad-blocks-widget',
			'SmartMag Legacy: Listing Block',
			array('description' => 'Only for legacy sites. Use newer SmartMag widgets instead.', 'classname' => 'page-blocks')
		);
	}
	
	/**
	 * Output the widget
	 * 
	 * @see WP_Widget::widget()
	 */
	public function widget($args, $instance) 
	{
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		
		// Alias for same modern grid
		if ($instance['block'] == 'blog-modern-2') {
			
			$instance['type'] = 'modern';
			$instance['excerpts'] = 0;
			$instance['columns'] = 2;
		}

		$block_args = array_replace($instance, [
			'columns' => 1,
			'terms'   => isset($instance['cats']) ? $instance['cats'] : '',
			'heading' => $title,
			'space_below' => 'none'
		]);
		
		$instance['block'] = str_replace('blog-', '', $instance['block']);
		switch ($instance['block']) {
			case 'modern':
				$block = 'Grid_Block';
				break;

			case 'modern-2':
				$block = 'Grid_Block';
			
				$block_args = array_replace($block_args, [
					'columns' => 2,
					'excerpts' => false,
					'style'    => 'sm',
					'meta_items_default' => false,
					'meta_above' => [],
					'meta_below' => [],
					'column_gap' => 'sm',
				]);
			break;

			case 'grid-overlay':
				$block = 'Overlay_Block';
				$block_args['excerpts'] = false;
				break;

			case 'highlights':
				$block = 'Highlights_Block';
				$block_args['excerpt_length'] = Bunyad::options()->loop_grid_excerpt_length;
				break;

			// Not supported anymore.
			case 'timeline':
				return;
		}

		// Use the new widget.
		$class = 'Bunyad\Widgets\Loops\\' . $block;
		$widget = new $class;

		$widget->widget($args, $block_args);
	}

	/**
	 * Save the widget data.
	 * 
	 * @see WP_Widget::update()
	 */
	public function update($new, $old) 
	{
		foreach ($new as $key => $val) {
			
			if (is_array($val)) {
				foreach ($val as $key => $value) {
					$val[$key] = wp_kses_post($val);
				}
			}
			
			$new[$key] = wp_kses_post($val);
		}

		return $new;
	}


	/**
	 * Add/edit widget form.
	 */
	public function form($instance)
	{	
		$defaults = array(
			'title' => '', 
			'posts' => 4, 
			'type' => '', 
			'cats' => '',
			'cat_labels' => 0,
			'post_type' => '', 
			'tags' => '', 
			'offset' => 0, 
			'sort_by' => '',
			'sort_order' => '',
			'block' => ''
		);
		
		$instance = array_merge($defaults, (array) $instance);
		extract($instance);
				
		$render = Bunyad::factory('admin/option-renderer'); /* @var $render Bunyad_Admin_OptionRenderer */
		
	?>

	<p><strong>NOTE:</strong> This is a deprecated widget. Instead, we recommend using non-legacy block widgets labeled by <strong>SmartMag Block</strong>.</p>

	<p>
		<label><?php esc_html_e('Title:', 'bunyad-admin'); ?></label>
		<input name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
	</p>		
		
	<p>
		<label><?php esc_html_e('Number of Posts:', 'bunyad-admin'); ?></label>
		<input name="<?php echo esc_attr($this->get_field_name('posts')); ?>" type="text" value="<?php echo esc_attr($posts); ?>" />
	</p>
	<p class="description"><?php esc_html_e('Configures posts to show for each listing. Leave empty to use theme default number of posts.', 'bunyad-admin'); ?></p>
	
	<p>
		<label><?php esc_html_e('Sort By:', 'bunyad-admin'); ?></label>
		<select name="<?php echo esc_attr($this->get_field_name('sort_by')); ?>">
			<option value=""><?php esc_html_e('Published Date', 'bunyad-admin'); ?></option>
			<option value="modified"  <?php selected($sort_by, 'modified'); ?>><?php esc_html_e('Modified Date', 'bunyad-admin'); ?></option>
			<option value="random" <?php selected($sort_by, 'random'); ?>><?php esc_html_e('Random', 'bunyad-admin'); ?></option>
		</select>
		
		<select name="<?php echo esc_attr($this->get_field_name('sort_order')); ?>">
			<option value="desc" <?php selected($sort_order, 'desc'); ?>><?php esc_html_e('Latest First - Descending', 'bunyad-admin'); ?></option>
			<option value="asc" <?php selected($sort_order, 'asc'); ?>><?php esc_html_e('Oldest First - Ascending', 'bunyad-admin'); ?></option>
		</select>
	</p>
	
	<p>
		<label><?php esc_html_e('Block:', 'bunyad-admin'); ?></label>
		
		<select class="widefat" name="<?php echo esc_attr($this->get_field_name('block')); ?>">
			<option value="blog-modern" <?php selected($block, 'blog-modern'); ?>><?php esc_html_e('Listing: Modern Grid', 'bunyad-admin'); ?></option>
			<option value="blog-modern-2" <?php selected($block, 'blog-modern-2'); ?>><?php esc_html_e('Listing: Modern Grid - 2 Columns', 'bunyad-admin'); ?></option>
			<option value="blog-grid-overlay" <?php selected($block, 'blog-grid-overlay'); ?>><?php esc_html_e('Listing: Grid Overlay Style', 'bunyad-admin'); ?></option>
			<!-- <option value="blog-timeline" <?php selected($block, 'blog-timeline'); ?>><?php esc_html_e('Listing: Timeline Style', 'bunyad-admin'); ?></option> -->
			<option value="highlights" <?php selected($block, 'highlights'); ?>><?php esc_html_e('Block: Highlights', 'bunyad-admin'); ?></option>
		</select>

	</p>
	<p class="description"><?php esc_html_e('Check docs and demo to choose the right style.', 'bunyad-admin'); ?></p>
	
	<div class="taxonomydiv"> <!-- borrow wp taxonomydiv > categorychecklist css rules -->
		<label><?php esc_html_e('Limit Categories:', 'bunyad-admin'); ?></label>
		
		<div class="tabs-panel">
			<ul class="categorychecklist">
				<?php
				ob_start();
				wp_category_checklist(0, 0, $cats, false, null, false);
				
				echo str_replace('post_category[]', $this->get_field_name('cats') .'[]', ob_get_clean());
				?>
			</ul>			
		</div>
	</div>
	<p class="description"><?php esc_html_e('By default, all categories will be used. Tick categories to limit to a specific category or categories.', 'bunyad-admin'); ?></p>
	
	<p>
		<label><?php esc_html_e('Show Category Overlays?', 'bunyad-admin'); ?></label>
		<select class="widefat" name="<?php echo esc_attr($this->get_field_name('cat_labels')); ?>">
			<option value="1" <?php selected($cat_labels, 1); ?>><?php esc_html_e('Yes', 'bunyad-admin'); ?></option>
			<option value="0" <?php selected($cat_labels, 0); ?>><?php esc_html_e('No', 'bunyad-admin'); ?></option>
		</select>
	</p>
	
	<p class="tag">
		<?php esc_html_e('or Limit with Tags: (optional)', 'bunyad-admin'); ?> 
		<input type="text" name="<?php echo $this->get_field_name('tags'); ?>" value="<?php echo esc_attr($tags); ?>" class="widefat" />
	</p>
	
	<p class="description"><?php esc_html_e('Separate tags with comma. e.g. cooking,sports', 'bunyad-admin'); ?></p>
	
	<p>
		<label><?php esc_html_e('Offset: (Advanced)', 'bunyad-admin'); ?></label> 
		<input type="text" name="<?php echo $this->get_field_name('offset'); ?>" value="<?php echo esc_attr($offset); ?>" />
	</p>
	<p class="description"><?php esc_html_e('By specifying an offset as 10 (for example), you can ignore 10 posts in the results.', 'bunyad-admin'); ?></p>
	
	<p>
		<label><?php esc_html_e('Post Types: (Advanced)', 'bunyad-admin'); ?></label>
		<input name="<?php echo esc_attr($this->get_field_name('post_type')); ?>" type="text" value="<?php echo esc_attr($post_type); ?>" />
	</p>
	<p class="description"><?php esc_html_e('Only for advanced users! You can use a custom post type here - multiples supported when separated by comma. Leave empty to use the default format.', 'bunyad-admin'); ?></p>
	
	<?php
	}
}