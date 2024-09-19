<?php
/**
 * An ad widget wrapper
 */
class SmartMag_Widgets_Ads extends WP_Widget
{
	/**
	 * Setup the widget
	 * 
	 * @see WP_Widget::__construct()
	 */
	public function __construct()
	{
		parent::__construct(
			'bunyad_ads_widget',
			esc_html__('SmartMag Legacy - Advertisement', 'bunyad-admin'),
			array(
				'description' => esc_html__('Add advertisement code to your sidebar.', 'bunyad-admin'), 
				'classname'   => 'code-widget'
			)
		);
	}
	
	/**
	 * Widget output 
	 * 
	 * @see WP_Widget::widget()
	 */
	public function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', esc_html($instance['title']));
		
		if (empty($instance['code'])) {
			return;
		}

		$instance['code'] = str_replace('<img', '<img loading="lazy"', $instance['code']);
		
		?>

		<?php echo $args['before_widget']; ?>

			<?php if (!empty($title)): ?>
				
				<?php
					echo $args['before_title'] . wp_kses_post($title) . $args['after_title']; // before_title/after_title are built-in WordPress sanitized
				?>
				
			<?php endif; ?>
			
			<div class="a-wrap">
				<?php echo do_shortcode($instance['code']); // It's an ad code - we shouldn't be escaping it ?>
			</div>

		
		<?php echo $args['after_widget']; ?>
		
		<?php
	}
	
	/**
	 * Save widget
	 * 
	 * Strip out all HTML using wp_kses
	 * 
	 * @see wp_filter_post_kses()
	 */
	public function update($new, $old)
	{
		// foreach ($new as $key => $val) {			
			// Filter disallowed html - Removed as Adsense code would get stripped here 			
			// $new[$key] = wp_kses_post($val);
		// }
		
		return $new;
	}
	
	/**
	 * The widget form
	 */
	public function form($instance)
	{
		$defaults = array(
			'title' => '', 
			'code' => '',
		);

		$instance = array_replace($defaults, (array) $instance);
		extract($instance);

		?>

		<p><strong>NOTE:</strong> This is a deprecated widget. We recommend using the <strong>SmartMag - Ads / Code</strong> widget instead.</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('code')); ?>"><?php echo esc_html__('Ad Code:', 'bunyad-admin'); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('code')); ?>" name="<?php 
				echo esc_attr($this->get_field_name('code')); ?>" rows="5"><?php echo esc_textarea($code); ?></textarea>
		</p>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html__('Title: (Optional)', 'bunyad-admin'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php 
				echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>		
	
	
		<?php
	}
}