<?php
/**
 * The template includes necessary functions for theme.
 *
 * @package videoly
 * @since 1.0
 */
/**
 * Theme options variable $rs_theme_options
 */
define ('REDUX_OPT_NAME', 'videoly_theme_options');

/**
 * Theme version used for styles,js
 */
define ('VIDEOLY_THEME_VERSION','1.0');
/**
 * Setting constant to inform the plugin that theme is activated
 */
define ('VIDEOLY_THEME_ACTIVATED' , true);

require get_template_directory() . '/framework/includes/rs-theme-argument-class.php';
require get_template_directory() . '/framework/includes/rs-actions-config.php';
require get_template_directory() . '/framework/includes/rs-helper-functions.php';
require get_template_directory() . '/framework/includes/rs-frontend-functions.php';
require get_template_directory() . '/framework/includes/plugins/tgm/class-tgm-plugin-activation.php';
require get_template_directory() . '/framework/includes/rs-filters-config.php';
require get_template_directory() . '/framework/includes/rs-menu-walker-class.php';
require get_template_directory() . '/framework/admin/admin-init.php';

if( !function_exists('videoly_after_setup')) {

  function videoly_after_setup() {

    add_image_size('videoly-thumb',        40,   40,  true ); 
    add_image_size('videoly-small',        183,  96,  true ); 
    add_image_size('videoly-small-alt',    104,  63,  true ); 
    add_image_size('videoly-small-ver',    225,  305,  true ); 
    add_image_size('videoly-medium',       394,  218, true ); 
    add_image_size('videoly-medium-ver',   288,  180, true ); 
    add_image_size('videoly-medium-hor',   335,  160, true ); 
    add_image_size('videoly-medium-alt',   290,  162, true ); 
    add_image_size('videoly-big-alt',      608,  505, true ); 
    add_image_size('videoly-big',          820,  394, true );
    add_image_size('videoly-big-alt-2',    608,  307, true );

    add_theme_support('post-thumbnails');
    add_theme_support('custom-background' );
    add_theme_support('automatic-feed-links' );
    add_theme_support('post-formats',   array('video', 'gallery') );
    add_theme_support('title-tag' );

    register_nav_menus (array(
      'primary-menu' => esc_html__( 'Main Menu', 'videoly' ),
      'top-menu'     => esc_html__( 'Top Menu', 'videoly' ),
    ) );
  }
  add_action( 'after_setup_theme', 'videoly_after_setup' );
}

if ( ! isset( $content_width ) ) {
  $content_width = 1140;
}

?>
