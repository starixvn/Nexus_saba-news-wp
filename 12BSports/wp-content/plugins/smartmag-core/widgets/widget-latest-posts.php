<?php

class SmartMag_Widgets_LatestPosts extends WP_Widget
{
	public function __construct()
	{
		parent::__construct(
			'bunyad-latest-posts-widget',
			'SmartMag Legacy: Recent Posts',
			array('description' => 'Recent posts with thumbnail.', 'classname' => 'latest-posts')
		);
	}
	
	// code below is modified from default
	public function widget($args, $instance) 
	{
		if (!class_exists('\Bunyad') || !\Bunyad::get('blocks')) {
			return;
		}

		if (empty($instance['number']) || !$number = absint($instance['number'])) {
			$number = 5;
		}

		$title = apply_filters(
			'widget_title', 
			empty($instance['title']) ? 'Recent Posts' : $instance['title'],
			$instance, 
			$this->id_base
		);

		$new_args = [
			'query_type'     => 'custom',
			'pagination'     => false,
			'posts'          => $number,
			'columns'        => 1,
			'heading'        => $title,
			'space_below'    => 'none'
		];

		// Limit by category.
		if (!empty($instance['category'])) {
			$new_args['cat'] = $instance['category'];
		}
		
		// Limited by tag.
		if (!empty($instance['limit_tag'])) {
			$new_args['tags'] = $instance['limit_tag'];
		}

		$block = new \Bunyad\Widgets\Loops\PostsSmall_Block;
		$block->widget($args, $new_args);
	}

	public function update($new, $old) 
	{
		foreach ($new as $key => $val) {
			$new[$key] = wp_filter_kses($val);
		}

		return $new;
	}

	public function form($instance)
	{	
		$instance = array_merge(array('title' => '', 'number' => 5, 'category' => '', 'limit_tag' => ''), $instance);
		extract($instance);
		
		$fields = apply_filters('bunyad_widget_latest_posts_form', array('title' => '', 'category' => '', 'number' => ''));
?>

	<p><strong>NOTE:</strong> This is a deprecated widget. We recommend using the <strong>SmartMag - Latest Posts</strong> widget instead.</p>

	<?php if (isset($fields['title'])): ?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', 'bunyad-admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<?php endif; ?>

	<?php if (isset($fields['category'])): ?>
		<p><label for="<?php echo $this->get_field_id('category'); ?>"><?php esc_html_e('Limit to Category:', 'bunyad-admin'); ?></label>
		<?php wp_dropdown_categories(array(
				'show_option_all' => esc_html__('-- Not Limited --', 'bunyad-admin'), 
				'hierarchical' => 1,
				'hide_empty' => 0,
				'order_by' => 'name', 
				'class' => 'widefat', 
				'name' => $this->get_field_name('category'), 
				'selected' => $category
		)); ?>
		</p>
	<?php endif; ?>
	
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('limit_tag')); ?>"><?php echo esc_html_x('Limit to Tag (Optional):', 'bunyad-widgets'); ?></label>
			
			<input id="<?php echo esc_attr($this->get_field_id('limit_tag')); ?>" name="<?php 
				echo esc_attr($this->get_field_name('limit_tag')); ?>" type="text" class="widefat" value="<?php echo esc_attr($limit_tag); ?>" />
		</p>

	<?php if (isset($fields['type'])): ?>
	
		<p><label for="<?php echo $this->get_field_id('type'); ?>"><?php esc_html_e('Type:', 'bunyad-admin'); ?></label>
		
		<select id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>" class="widefat">
			<option value="list" <?php selected($type, 'list'); ?>><?php esc_html_e('List', 'bunyad-admin'); ?></option>
			<option value="blocks" <?php selected($type, 'blocks'); ?>><?php esc_html_e('Image Blocks', 'bunyad-admin'); ?></option>
			<option value="numbered" <?php selected($type, 'numbered'); ?>><?php esc_html_e('Numbered List (No Thumbnails)', 'bunyad-admin'); ?></option>
		</select>
		
		</p>
		
	<?php endif; ?>
	

	<?php if (isset($fields['number'])): ?>
		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php esc_html_e('Number of posts to show:', 'bunyad-admin'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	<?php endif; ?>
	
<?php
	}
}