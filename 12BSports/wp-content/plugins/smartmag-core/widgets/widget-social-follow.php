<?php
/**
 * Register Social Follow widget.
 */
class SmartMag_Widgets_SocialFollow extends WP_Widget 
{
	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'bunyad-social',
			esc_html__('SmartMag - Social Follow & Counters', 'bunyad-admin'),
			['description' => esc_html__('Show social follower buttons.', 'bunyad-admin'), 'classname' => 'widget-social-b']
		);
	}

	/**
	 * Register the widget if the plugin is active
	 */
	public function register_widget() {
		
		if (!class_exists('\Sphere\Core\SocialFollow\Module')) {
			return;
		}
		
		register_widget(__CLASS__);
	}
	
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance) 
	{
		$title = apply_filters('widget_title', esc_html($instance['title']));

		echo $args['before_widget'];

		if (!empty($title)) {	
			echo $args['before_title'] . wp_kses_post($title) . $args['after_title']; // before_title/after_title are built-in WordPress sanitized
		}

		$services = $this->services();
		$active   = !empty($instance['social']) ? $instance['social'] : [];
		$style    = !empty($instance['style']) ? $instance['style'] : 'a';
		$columns  = 1;
		$split_style = false;

		// Style set to type a-2 and so on.
		if (preg_match('/([a-z]+)\-(\d+)(\-(\w+)|$)/', $style, $match)) {
			$columns = intval($match[2]);
			$style   = $match[1];
			$split_style = $match[4] ?? false;
		}

		$show_counters = empty($instance['counters']) 
			? Bunyad::options()->sf_counters 
			: bool_from_yn($instance['counters']);


		$classes =  array_filter([
			'spc-social-follow',
			'spc-social-follow-' . $style,
			'spc-social-colors',

			// Background color for style a and b.
			in_array($style, ['a', 'b']) ? 'spc-social-bg' : '',
			in_array($style, ['c']) ? 'spc-social-colored' : '',

			$split_style ? 'spc-social-follow-split' : '',
			$show_counters ? 'has-counts' : ''
		]);

		$grid_classes = [
			'grid-' . $columns,
			'md:grid-4',
			'sm:grid-2',
		];

		?>
		<div class="<?php echo esc_attr(join(' ', $classes)); ?>">
			<ul class="services grid <?php echo esc_attr(join(' ', $grid_classes)); ?>" itemscope itemtype="http://schema.org/Organization">
				<link itemprop="url" href="<?php echo esc_url(home_url('/')); ?>">
				<?php 
				foreach ($active as $key):

					if (!isset($services[$key])) {
						continue;
					}
									
					$service = $services[$key];
					$count   = 0;
					
					if ($show_counters) { 
						$count = Bunyad::get('social-follow')->count($key);
					}

					$s_classes = array_filter([
						'service',
						'service-link s-' . $key,
						($count > 0 ? 'has-count' : '')
					]);
				?>
				
				<li class="service-wrap">

					<a href="<?php echo esc_url($service['url']); ?>" class="<?php echo esc_attr(join(' ', $s_classes)); ?>" target="_blank" itemprop="sameAs" rel="nofollow noopener">
						<i class="the-icon tsi tsi-<?php echo esc_attr($service['icon']); ?>"></i>
						<span class="label"><?php echo esc_html($service['text']); ?></span>

						<?php if ($count > 0): ?>
							<span class="count"><?php echo esc_html($this->readable_number($count)); ?></span>
						<?php endif; ?>	
					</a>

				</li>
				
				<?php 
				endforeach; 
				?>
			</ul>
		</div>
		
		<?php

		echo $args['after_widget'];
	}
	
	/**
	 * Supported services
	 */
	public function services()
	{
		return Bunyad::get('smartmag_social')->follow_services();
	}

	/**
	 * Make count more human in format 1.4K, 1.5M etc.
	 * 
	 * @param integer $number
	 */
	public function readable_number($number)
	{
		if ($number < 1051) {
			return $number;
		}

		if ($number < 10^6) {
			return round($number / 1000, 1) . 'K';
		}
		
		return round($number / 10^6, 1) . 'M';
	}
		

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance)
	{
		$defaults = [
			'title'    => '', 
			'style'    => 'b-2',
			'social'   => [],
			'counters' => ''
		];

		$instance = array_replace($defaults, (array) $instance);
		
		extract($instance);
		
		// Merge current values for sorting reasons
		$services = array_replace(array_flip($social), $this->services());
		
		?>
		
		<div class="bunyad-widget-option">
			<label class="label" for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html__('Title:', 'bunyad-admin'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php 
				echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</div>

		<div class="bunyad-widget-option">
			<label class="label" for="<?php echo esc_attr($this->get_field_id('style')); ?>"><?php echo esc_html__('Style:', 'bunyad-admin'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('style')); ?>" name="<?php echo esc_attr($this->get_field_name('style')); ?>" class="widefat">
				<option value="b-2" <?php selected($style, 'b'); ?>><?php echo esc_html__('Modern BG - 2 Columns', 'bunyad-admin') ?></option>
				<option value="b" <?php selected($style, 'b'); ?>><?php echo esc_html__('Modern BG - 1 Column', 'bunyad-admin') ?></option>
				<option value="a" <?php selected($style, 'a'); ?>><?php echo esc_html__('Classic BG - 1 Column', 'bunyad-admin') ?></option>
				<option value="a-2" <?php selected($style, 'a-2'); ?>><?php echo esc_html__('Classic BG - 2 Columns', 'bunyad-admin') ?></option>
				<option value="c" <?php selected($style, 'c'); ?>><?php echo esc_html__('Light - 1 Columns', 'bunyad-admin') ?></option>
				<option value="c-2" <?php selected($style, 'c-2'); ?>><?php echo esc_html__('Light - 2 Columns', 'bunyad-admin') ?></option>
				<option value="b-2-split" <?php selected($style, 'b-2-split'); ?>><?php echo esc_html__('Modern BG - 2 Col Multiline', 'bunyad-admin') ?></option>
				<option value="c-2-split" <?php selected($style, 'c-2-split'); ?>><?php echo esc_html__('Light - 2 Col Multiline', 'bunyad-admin') ?></option>
			</select>
		</div>

		<div class="bunyad-widget-option">
			<label class="label" for="<?php echo esc_attr($this->get_field_id('counters')); ?>"><?php echo esc_html__('Show Counters:', 'bunyad-admin'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('counters')); ?>" name="<?php echo esc_attr($this->get_field_name('counters')); ?>" class="widefat">
				<option value="" <?php selected($counters, ''); ?>><?php echo esc_html__('Global Inherit (From Customize > Social Follow)', 'bunyad-admin') ?></option>
				<option value="y" <?php selected($counters, 'y'); ?>><?php echo esc_html__('Yes', 'bunyad-admin') ?></option>
				<option value="n" <?php selected($counters, 'n'); ?>><?php echo esc_html__('No', 'bunyad-admin') ?></option>
			</select>
		</div>
		
		<div class="bunyad-widget-option">
			<label class="label" for="<?php echo esc_attr($this->get_field_id('social')); ?>"><?php echo esc_html__('Social Icons:', 'bunyad-admin'); ?></label>
			<p class="small-desc"><?php esc_html_e('Drag and drop to re-order.', 'bunyad-admin'); ?></p>
			
			<div class="bunyad-social-services">
			<?php 
				foreach ($services as $key => $service): 
					if (!is_array($service)) {
						continue;
					}
			?>
				<p>
					<label>
						<input class="widefat" type="checkbox" name="<?php echo esc_attr($this->get_field_name('social')); ?>[]" value="<?php echo esc_attr($key); ?>"<?php 
						echo (in_array($key, $social) ? ' checked' : ''); ?> /> 
					<?php echo esc_html($service['label']); ?></label>
				</p>
			
			<?php endforeach; ?>
			
			</div>
			
			<p class="bunyad-note"><strong><?php echo esc_html__('Note:', 'bunyad-admin'); ?></strong>
			<?php echo esc_html__('Configure URLs from Customize > General Settings > Social Media Links.', 'bunyad-admin'); ?></p>
			
		</div>
		
		<script>
		jQuery(function($) { 
			$('.bunyad-social-services').sortable();
		});
		</script>
	
	
		<?php
	}

	/**
	 * Save widget.
	 * 
	 * Strip out all HTML using wp_kses
	 * 
	 * @see wp_kses_post()
	 */
	public function update($new, $old)
	{
		foreach ($new as $key => $val) {

			// Social just needs intval
			if ($key === 'social') {
				
				array_walk($val, 'intval');
				$new[$key] = $val;

				continue;
			}
			
			// Filter disallowed html.
			$new[$key] = wp_kses_post_deep($val);
		}
		
		return $new;
	}
}
