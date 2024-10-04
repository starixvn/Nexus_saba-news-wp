<?php

class SmartMag_Widgets_LatestReviews extends WP_Widget
{
	public function __construct()
	{
		parent::__construct(
			'bunyad-latest-reviews-widget',
			'SmartMag - Reviews (Basic)',
			array('description' => 'Latest Reviews with thumbnails.', 'classname' => 'latest-reviews')
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
			$instance['title'],
			$instance, 
			$this->id_base
		);

		$new_args = [
			'query_type'     => 'custom',
			'pagination'     => false,
			'posts'          => $number,
			'reviews_only'   => true,
			'columns'        => 1,
			'heading'        => $title,
			'sort_by'        => $instance['order'],
			'space_below'    => 'none'
		];

		$block = new \Bunyad\Widgets\Loops\PostsSmall_Block;
		$block->widget($args, $new_args);
	}

	public function update($new_instance, $old_instance) 
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['order'] = strip_tags($new_instance['order']);
		
		return $instance;
	}

	public function form($instance) 
	{
		$values = array_merge(array('title' => '', 'number' => 5, 'order' => ''), (array) $instance);
		extract($values);
?>

		<p><strong>NOTE:</strong> This is a basic widget. We recommend using the <strong>SmartMag - Latest Posts</strong> widget (tick option "Review Posts Only" under Posts Source).</p>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', 'bunyad-admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php esc_html_e('Number of posts to show:', 'bunyad-admin'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
		
		<p>
			<label for="<?php echo $this->get_field_id('order'); ?>"><?php esc_html_e('Order By:', 'bunyad-admin'); ?></label>
			<select name="<?php echo $this->get_field_name('order'); ?>" id="<?php echo $this->get_field_id('order'); ?>">
				<option value="date"<?php selected($order, 'date'); ?>><?php esc_html_e('Date', 'bunyad-admin'); ?></option>
				<option value="rating"<?php selected($order, 'rating'); ?>><?php esc_html_e('Rating', 'bunyad-admin'); ?></option>
			</select>
		</p>
<?php
	}
}