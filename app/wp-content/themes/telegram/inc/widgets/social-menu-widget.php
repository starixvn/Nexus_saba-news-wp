<?php
/**
 * Theme widgets.
 *
 * @package Telegram
 */

// Load widget base.
if (!function_exists('telegram_social_menu_sidebar_widget')) :
    /**
     * Load widgets.
     *
     * @since 1.0.0
     */
    function telegram_social_menu_sidebar_widget()
    {
        // List Post widget.
        register_widget('TWP_Social_Menu_Widget');

    }
endif;
add_action('widgets_init', 'telegram_social_menu_sidebar_widget');

/*Social widget*/
if (!class_exists('TWP_Social_Menu_Widget')) :

    /**
     * Social widget Class.
     *
     * @since 1.0.0
     */
    class TWP_Social_Menu_Widget extends Telegram_Widget_Base
    {

        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $opts = array(
                'classname' => 'twp-home-widget-section twp-social-widget-section',
                'description' => __('Displays Social share.', 'telegram'),
                'customize_selective_refresh' => true,
            );
            $fields = array(
                'title' => array(
                    'label' => __('Title:', 'telegram'),
                    'type' => 'text',
                    'class' => 'widefat',
                ),
            );

            parent::__construct('telegram-social-layout', __('TG :- Social Menu Widget', 'telegram'), $opts, array(), $fields);
        }

        /**
         * Outputs the content for the current widget instance.
         *
         * @since 1.0.0
         *
         * @param array $args Display arguments.
         * @param array $instance Settings for the current widget instance.
         */
        function widget($args, $instance)
        {

            $params = $this->get_params($instance);

            echo $args['before_widget']; ?>
            <div class='container'>
            <?php if ( ! empty( $params['title'] ) ) {
                echo $args['before_title'] . $params['title'] . $args['after_title'];
            } ?>
            
                <div class="twp-social-widget-menu">
                    <?php
                        wp_nav_menu(
                            array('theme_location' => 'social-nav',
                                'link_before' => '<span>',
                                'link_after' => '</span>',
                                'menu_id' => 'social-menu',
                                'fallback_cb' => false,
                                'menu_class' => 'twp-social-icon-with-text'
                            )); 
                        ?>
                    <?php if ( ! has_nav_menu( 'social-nav' ) ) : ?>
                        <p>
                            <?php esc_html_e( 'Social menu is not set. You need to create menu and assign it to Social Menu on Menu Settings.', 'telegram' ); ?>
                        </p>
                    <?php endif; ?>
                </div><!--/twp-social-widget-menu-->
            </div><!--/container-->
            <?php echo $args['after_widget'];
        }
    }
endif;
