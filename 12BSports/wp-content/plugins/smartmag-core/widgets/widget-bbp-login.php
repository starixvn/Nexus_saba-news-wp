<?php
/**
 * bbPress Login Widget.
 */
class SmartMag_Widgets_BbpLogin extends WP_Widget {

	/**
	 * bbPress Login Widget
	 *
	 * Registers the login widget
	 *
	 * @uses apply_filters() Calls 'bbp_login_widget_options' with the
	 *                        widget options
	 */
	public function __construct() 
	{
		$widget_ops = apply_filters( 'bunyad_bbp_login_widget_options', array(
			'classname'   => 'bbp_widget_login',
			'description' => esc_html__('A simple login form with optional links to sign-up and lost password pages.', 'bbpress')
		) );

		parent::__construct(false, esc_html__('(bbPress) SmartMag Login Widget', 'bunyad'), $widget_ops);
	}

	/**
	 * Register the widget
	 *
	 * @uses register_widget()
	 */
	public static function register_widget() 
	{
		
		if (class_exists('bbpress')) {
			register_widget(static::class);
		}
	}

	/**
	 * Displays the output, the login form
	 * 
	 * @param mixed $args Arguments
	 * @param array $instance Instance
	 * @uses apply_filters() Calls 'bbp_login_widget_title' with the title
	 * @uses get_template_part() To get the login/logged in form
	 */
	public function widget($args, $instance) 
	{

		// Get widget settings
		$settings = $this->parse_settings( $instance );

		// Typical WordPress filter
		$settings['title'] = apply_filters( 'widget_title', $settings['title'], $instance, $this->id_base );

		// bbPress filters
		$settings['title']    = apply_filters( 'bbp_login_widget_title',    $settings['title'],    $instance, $this->id_base );
		$settings['register'] = apply_filters( 'bbp_login_widget_register', $settings['register'], $instance, $this->id_base );
		$settings['lostpass'] = apply_filters( 'bbp_login_widget_lostpass', $settings['lostpass'], $instance, $this->id_base );

		if (empty($settings['register'])) {
			$settings['register'] = wp_registration_url();
		}

		if (empty($settings['lostpass'])) {
			$settings['lostpass'] = wp_lostpassword_url();
		}

		echo $args['before_widget'];

		if ( !empty( $settings['title'] ) ) {
			echo $args['before_title'] . $settings['title'] . $args['after_title'];
		}

		if (!is_user_logged_in()) : ?>

			<form method="post" action="<?php echo site_url('wp-login.php', 'login_post'); ?>" class="bbp-login-widget auth-widget">

				<div class="input-group">
					<input type="text" name="log" value="" placeholder="<?php esc_html_e('Username or Email', 'bunyad'); ?>" />
				</div>

				<div class="input-group">
					<input type="password" name="pwd" value="" placeholder="<?php esc_html_e('Password', 'bunyad'); ?>" />
				</div>

				<?php Bunyad::authenticate() && Bunyad::authenticate()->do_login_hooks(); // Calls native 'login_form' hook. ?>
				<?php !function_exists('bbp_user_login_fields') || bbp_user_login_fields(); ?>

				<button type="submit" name="wp-submit" id="user-submit" class="ts-button submit user-submit"><?php esc_html_e('Log In', 'bunyad'); ?></button>

				<div class="footer">
					<div class="remember">
						<input name="rememberme" type="checkbox" id="rememberme" value="forever" />
						<label for="rememberme"><?php esc_html_e('Remember Me', 'bunyad'); ?></label>
					</div>

					<a href="<?php echo esc_url($settings['lostpass']); ?>" title="<?php esc_attr_e('Lost password?', 'bunyad'); ?>" class="lost-pass">
						<?php esc_html_e('Lost password?', 'bunyad'); ?>
					</a>
				</div>

			</form>
			
			<?php  if (!empty($settings['register'])): ?>
				
			<div class="bbp-register-info"><?php echo esc_html_x("Don't have an account?", 'bbPress', 'bunyad'); ?>
				<a href="<?php echo esc_url( $settings['register'] ); ?>" class="register-modal"><?php esc_html_e('Register Now!', 'bunyad'); ?></a>
			</div>
							
			<?php endif; ?>
			

		<?php else : ?>

			<div class="bbp-logged-in">
				<a href="<?php bbp_user_profile_url(bbp_get_current_user_id()); ?>" class="submit user-submit"><?php echo get_avatar(bbp_get_current_user_id(), '60'); ?></a>
				<div class="content">
				
				<?php echo esc_html_x('Welcome back, ', 'bbPress', 'bunyad'); ?>
				<?php bbp_user_profile_link(bbp_get_current_user_id()); ?>
				
				<ol class="links">
					<li><a href="<?php bbp_user_profile_edit_url(bbp_get_current_user_id()); ?>">
						<?php echo esc_html_x('Edit Profile', 'bbPress', 'bunyad'); ?></a></li>
					<li><a href="<?php bbp_subscriptions_permalink(bbp_get_current_user_id()); ?>">
						<?php echo esc_html_x('Subscriptions', 'bbPress', 'bunyad'); ?></a></li>
					<li><a href="<?php bbp_favorites_permalink(bbp_get_current_user_id()); ?>">
						<?php echo esc_html_x('Favorites', 'bbPress', 'bunyad'); ?></a></li>
				</ol>

				<?php bbp_logout_link(); ?>
				
				</div>
			</div>

		<?php endif;

		echo $args['after_widget'];
	}

	/**
	 * Update the login widget options
	 *
	 *
	 * @param array $new_instance The new instance options
	 * @param array $old_instance The old instance options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance             = $old_instance;
		$instance['title']    = strip_tags( $new_instance['title'] );
		$instance['register'] = esc_url_raw( $new_instance['register'] );
		$instance['lostpass'] = esc_url_raw( $new_instance['lostpass'] );

		return $instance;
	}

	/**
	 * Output the login widget options form
	 *
	 *
	 * @param $instance Instance
	 * @uses BBP_Login_Widget::get_field_id() To output the field id
	 * @uses BBP_Login_Widget::get_field_name() To output the field name
	 */
	public function form($instance) {

		// Get widget settings
		$settings = $this->parse_settings( $instance ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e('Title:', 'bunyad-admin'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>" /></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'register' ); ?>"><?php esc_html_e('Register URI:', 'bunyad-admin'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id( 'register' ); ?>" name="<?php echo $this->get_field_name( 'register' ); ?>" type="text" value="<?php echo esc_url( $settings['register'] ); ?>" /></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'lostpass' ); ?>"><?php esc_html_e('Lost Password URI:', 'bunyad-admin'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id( 'lostpass' ); ?>" name="<?php echo $this->get_field_name( 'lostpass' ); ?>" type="text" value="<?php echo esc_url( $settings['lostpass'] ); ?>" /></label>
		</p>

		<?php
	}

	/**
	 * Merge the widget settings into defaults array.
	 *
	 * @param $instance Instance
	 * @uses bbp_parse_args() To merge widget settings into defaults
	 */
	public function parse_settings( $instance = array() ) {
		return bbp_parse_args( $instance, array(
			'title'    => '',
			'register' => '',
			'lostpass' => ''
		), 'login_widget_settings' );
	}
}