<?php
/**
 * About Me Widget
 */
class SmartMag_Widgets_About extends WP_Widget
{
	/**
	 * Setup the widget
	 * 
	 * @see WP_Widget::__construct()
	 */
	public function __construct()
	{
		parent::__construct(
			'bunyad_about_widget',
			'SmartMag - About Widget',
			array('description' => esc_html__('"About" site widget.', 'bunyad-widgets'), 'classname' => 'widget-about')
		);
	}
	
	public function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);

		$image_class = [];
		$image_type  = 'image';
		if (!empty($instance['image_type'])) {
			$image_class[] = 'image-' . $instance['image_type'];
			$image_type    = 'logo';
		}

		if (!empty($instance['image_circle'])) {
			$image_class[] = 'image-circle';
		}

		$image_class = join(' ', $image_class);
		
		?>

		<?php echo $args['before_widget']; ?>
		
		<?php if (!empty($title)): ?>
			
			<?php
				echo $args['before_title'] . wp_kses_post($title) . $args['after_title']; // before_title/after_title are built-in WordPress sanitized
			?>
			
		<?php endif; ?>
	
		<div class="inner <?php echo (!empty($instance['centered']) ? 'widget-about-centered' : ''); ?>">
		
			<?php if (!empty($instance['image'])): ?>
				<div class="<?php echo esc_attr($image_class); ?>">
					<img <?php
						/**
						 * Get escaped attributes and add optionally add srcset for retina.
						 */ 
						$retina = [];
						if (!empty($instance['image_2x'])) {
							$retina[$instance['image_2x']] = '2x';
						}

						Bunyad::markup()->attribs('widget-about-' . $image_type, 
							Bunyad::theme()->get_logo_data($instance['image'])
							+ [
								'src'    => $instance['image'],
								'alt'    => $instance['title'],
								'srcset' => [$instance['image'] => ''] + $retina
							]
						); ?> />
				</div>
			<?php endif; ?>
			
			<?php if (!empty($instance['logo_text'])): ?>
				<p class="logo-text"><?php echo $instance['logo_text']; ?></p>
			<?php endif; ?>
			
			<div class="base-text about-text"><?php 
				echo do_shortcode(
					wp_kses_post(
						apply_filters('shortcode_cleanup', wpautop($instance['text']))
					)
				); 
			?></div>

			<?php if (!empty($instance['social'])): ?>
				<?php 
					echo Bunyad::blocks()->load('SocialIcons', [
						'style'    => 'b',
						'services' => $instance['social']
					]);
				?>
			<?php endif; ?>

		</div>

		<?php echo $after_widget; ?>
		
		<?php
	}
	
	/**
	 * Save widget.
	 * 
	 * Strip out all HTML using wp_kses
	 * 
	 * @see wp_kses_post_deep()
	 */
	public function update($new, $old)
	{
		foreach ($new as $key => $val) {
			
			// Social just needs intval
			if ($key == 'social') {
				
				array_walk($val, 'intval');
				$new[$key] = $val;

				continue;
			}
			
			// Filter disallowed html 			
			$new[$key] = wp_kses_post_deep($val);
		}

		if (isset($old['image'])) {
			delete_transient('bunyad_logo_' . md5($old['image']));
		}
		
		// Spaces left in commonly.
		// $new['read_more']    = trim($new['read_more']);
		$new['image_circle'] = !empty($new['image_circle']) ? 1 : 0;
		
		return $new;
	}

	/**
	 * The widget form
	 */
	public function form($instance)
	{
		$defaults = [
			'title'          => 'About',
			'image'          => '',
			'image_circle'   => '',
			'image_type'     => 'logo',
			'image_2x'       => '',
			'logo_text'      => '',
			'text'           => '',
			'social'         => [],
			'centered'       => false,
			// 'text_below'     => ''
		];

		$instance = array_merge($defaults, (array) $instance);
		
		// Social options
		$icons = [
			'facebook'   => esc_html__('Facebook', 'bunyad-admin'),
			'twitter'    => esc_html__('X (Twitter)', 'bunyad-admin'),
			'instagram'  => esc_html__('Instagram', 'bunyad-admin'),
			'pinterest'  => esc_html__('Pinterest', 'bunyad-admin'),
			'vimeo'      => esc_html__('Vimeo', 'bunyad-admin'),
			'tumblr'     => esc_html__('Tumblr', 'bunyad-admin'),
			'rss'        => esc_html__('RSS', 'bunyad-admin'),
			'bloglovin'  => esc_html__('BlogLovin', 'bunyad-admin'),
			'youtube'    => esc_html__('Youtube', 'bunyad-admin'),
			'dribbble'   => esc_html__('Dribbble', 'bunyad-admin'),
			'linkedin'   => esc_html__('LinkedIn', 'bunyad-admin'),
			'flickr'     => esc_html__('Flickr', 'bunyad-admin'),
			'soundcloud' => esc_html__('SoundCloud', 'bunyad-admin'),
			'lastfm'     => esc_html__('Last.fm', 'bunyad-admin'),
			'vk'         => esc_html__('VKontakte', 'bunyad-admin'),
			'steam'      => esc_html__('Steam', 'bunyad-admin'),
		];

		if (is_callable([Bunyad::core(), 'get_common_data'])) {
			$_common = Bunyad::core()->get_common_data('options');

			if (isset($_common['social_services'])) {
				$icons = $_common['social_services'];	
			}
		}
		
		extract($instance);
	?>
	
	<div class="bunyad-widget-option">
		<label class="label" for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'bunyad-admin'); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php 
			echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
	</div>

	<div class="bunyad-widget-option">
		<label class="label" for="<?php echo esc_attr($this->get_field_id('text')); ?>"><?php esc_html_e('About:', 'bunyad-admin'); ?></label>
		<textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('text')); ?>" name="<?php 
			echo esc_attr($this->get_field_name('text')); ?>" rows="5"><?php echo esc_textarea($text); ?></textarea>
	</div>
		
	<div class="bunyad-widget-option" style="display: none;">
		<label class="label" for="<?php echo esc_attr($this->get_field_id('logo_text')); ?>"><?php esc_html_e('Logo Text:', 'bunyad-admin'); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('logo_text')); ?>" name="<?php 
			echo esc_attr($this->get_field_name('logo_text')); ?>" type="text" value="<?php echo esc_attr($logo_text); ?>" />
	</div>
	
	<div class="bunyad-widget-option">
		<label class="label" for="<?php echo esc_attr($this->get_field_id('image')); ?>"><?php esc_html_e('Logo / Image:', 'bunyad-admin'); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('image')); ?>" name="<?php 
			echo esc_attr($this->get_field_name('image')); ?>" type="text" value="<?php echo esc_attr($image); ?>" />
		<p class="small-desc">Copy an image URL from media library. You may use a logo image or general full width image, based on setting below.</p>
	</div>

	<div class="bunyad-widget-option">
		<label class="label" for="<?php echo esc_attr($this->get_field_id('image_2x')); ?>"><?php esc_html_e('Logo Retina/2x:', 'bunyad-admin'); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('image_2x')); ?>" name="<?php 
			echo esc_attr($this->get_field_name('image_2x')); ?>" type="text" value="<?php echo esc_attr($image_2x); ?>" />
	</div>

	<div class="bunyad-widget-option">
		<label class="label" for="<?php echo esc_attr($this->get_field_id('image_type')); ?>"><?php echo esc_html__('Image Type:', 'bunyad-admin'); ?></label>
		
		<select id="<?php echo esc_attr($this->get_field_id('image_type')); ?>" name="<?php echo esc_attr($this->get_field_name('image_type')); ?>" class="widefat">
			<option value="logo" <?php selected($image_type, 'logo'); ?>><?php echo esc_html__('Logo', 'bunyad-admin'); ?></option>
			<option value="full" <?php selected($image_type, 'full'); ?>><?php echo esc_html__('Full Width', 'bunyad-admin'); ?></option>
		</select>
	</div>
	
	
	<div class="bunyad-widget-option">
		<input class="widefat" type="checkbox" id="<?php echo esc_attr($this->get_field_id('centered')); ?>" name="<?php 
			echo esc_attr($this->get_field_name('centered')); ?>" value="1" <?php checked($centered); ?>/>
			
		<label for="<?php echo esc_attr($this->get_field_id('centered')); ?>"><?php echo esc_html__('Center Align Content', 'bunyad-admin'); ?></label>
		<p class="small-desc">Enable to center align all the widget content.</p>
	</div>

	<div class="bunyad-widget-option">
		<input class="widefat" type="checkbox" id="<?php echo esc_attr($this->get_field_id('image_circle')); ?>" name="<?php 
			echo esc_attr($this->get_field_name('image_circle')); ?>" value="1" <?php checked($image_circle); ?>/>
			
		<label for="<?php echo esc_attr($this->get_field_id('image_circle')); ?>"><?php echo esc_html__('Rounded / Circular Image?', 'bunyad-admin'); ?></label>
		<p class="small-desc">Only valid when using a full width image - not for logo image.</p>
	</div>

	
	<div class="bunyad-widget-option">
		<label class="label" for="<?php echo esc_attr($this->get_field_id('social')); ?>"><?php echo esc_html__('Social Icons: (optional)', 'bunyad-admin'); ?></label>
		<p class="small-desc"><?php esc_html_e('Drag and drop to re-order.', 'bunyad-admin'); ?></p>

		<div class="bunyad-social-services">
		<?php foreach ($icons as $icon => $label): ?>
		
			<p>
				<label>
					<input class="widefat" type="checkbox" name="<?php echo esc_attr($this->get_field_name('social')); ?>[]" value="<?php echo esc_attr($icon); ?>"<?php 
					echo (in_array($icon, $social) ? ' checked' : ''); ?> /> 
				<?php echo esc_html($label); ?></label>
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
}