<?php

class SmartMag_Widgets_PopularPosts extends WP_Widget
{
	public function __construct()
	{
		parent::__construct(
			'bunyad-popular-posts-widget',
			'SmartMag Legacy: Popular Posts',
			array('description' => 'Displays posts with most comments.', 'classname' => 'popular-posts')
		);
	}

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
			'columns'        => 1,
			'heading'        => $title,
			'sort_by'        => isset($instance['type']) ? $instance['type'] : 'comments',
			'sort_days'      => isset($instance['days']) ? $instance['days'] : 30,
			'space_below'    => 'none'
		];

		$block = new \Bunyad\Widgets\Loops\PostsSmall_Block;
		$block->widget($args, $new_args);
	}

	public function update($new, $old) 
	{
		$new['title']  = strip_tags($new['title']);
		$new['number'] = intval($new['number']);
		$new['days']   = intval($new['days']);
		$new['type']   = strip_tags($new['type']);

		return $new;
	}

	public function form($instance) 
	{
		
		$instance = wp_parse_args($instance, array('title' => '', 'number' => 5, 'type' => 'comments', 'days' => 30));
		
		extract($instance, EXTR_SKIP);
				
		
?>
		<p><strong>NOTE:</strong> This is a deprecated widget. We recommend using the <strong>SmartMag - Latest Posts</strong> widget instead (Use sorting option under Posts Source).</p>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', 'bunyad-admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php esc_html_e('Number of posts to show:', 'bunyad-admin'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
		

		<p>
			<label for="<?php echo $this->get_field_id('type'); ?>"><?php esc_html_e('Sorting Type:', 'bunyad-admin'); ?></label>
			<select id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>" class="widefat bunyad-popular-posts-type">
				<option value="comments" <?php selected($type, 'comments'); ?>><?php esc_html_e('Comments', 'bunyad-admin'); ?></option>
				<option value="jetpack" <?php selected($type, 'jetpack'); ?>><?php 
					_e((!$this->has_jetpack() ? 'Views (Requires Jetpack plugin with Stats module)' : 'Views - via Jetpack Stats'), 'bunyad-widgets'); ?></option>
			</select>
		</p>
		
		<div class="jetpack-extras hidden">
			<p>
				<label for="<?php echo $this->get_field_id('days'); ?>"><?php esc_html_e('From past days:', 'bunyad-admin'); ?></label>
				<input id="<?php echo $this->get_field_id('days'); ?>" name="<?php echo $this->get_field_name('days'); ?>" type="text" value="<?php echo $days; ?>" size="3" />
			</p>
			
			<p><?php esc_html_e('Note that it may take a few hours before views are counted. It will fallback to comments sorting type until then.', 'bunyad-admin'); ?></p>
		</div>
		
		<script>
		
		// show days only if needed
		jQuery(function($) {
			$(document).on('change', '.bunyad-popular-posts-type', function() {

				var ele = $(this).closest('.widget-content').find('.jetpack-extras');

				if ($(this).val() == 'jetpack') {
					ele.show();
				}
				else {
					ele.hide();
				}
			});

			$('.bunyad-popular-posts-type').trigger('change');
		});
		</script>
		
<?php
	}
	
	public function has_jetpack() {
		return (class_exists('Jetpack') && Jetpack::is_module_active('stats'));
	}
	
}