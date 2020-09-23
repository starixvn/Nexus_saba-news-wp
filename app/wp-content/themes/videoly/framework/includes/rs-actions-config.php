<?php
/**
 * Action Hooks.
 *
 * @package videoly
 * @since 1.0
 */

/**
 * Post Column View
 *
 * @package videoly
 * @since 1.0
 */
if(!function_exists('videoly_posts_custom_column_views')) {
  function videoly_posts_custom_column_views($column_name, $id) {
    if($column_name === 'post_views'){
      echo videoly_getPostViews(get_the_ID());
    }
  }
  add_action('manage_posts_custom_column', 'videoly_posts_custom_column_views',5,2);
}


/**
* @return null
* @param none
* register widgets
**/
if( !function_exists('videoly_register_sidebar') ) {

  function videoly_register_sidebar() {

    // register sidebars
    register_sidebar(array(
      'id'            => 'main',
      'name'          => esc_html__('Main Sidebar', 'videoly'),
      'before_widget' => '<div id="%1$s" class="sidebar-item widget %2$s">',
      'after_widget'  => '</div><div class="empty-space marg-lg-b30"></div>',
      'before_title'  => '<h5 class="c-h5 widget-title">',
      'after_title'   => '</h5><div class="empty-space marg-lg-b10"></div>',
      'description'   => 'Drag the widgets for sidebars.'
    ));

    for($i = 1; $i < 5; $i++) {
      register_sidebar(array(
        'id'            => 'footer-'.$i,
        'name'          => esc_html__('Footer Sidebar '.$i, 'videoly'),
        'before_widget' => '<div id="%1$s" class="widget tt-footer-list footer_widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="tt-title-block-2">',
        'after_title'   => '</h5><div class="empty-space marg-lg-b20"></div>',
        'description'   => 'Drag the widgets for sidebars.'
      ));
    }

    $custom_sidebars = videoly_get_opt('custom-sidebars');
    if (is_array($custom_sidebars)) {
      foreach ($custom_sidebars as $sidebar) {

        if (empty($sidebar)) {
          continue;
        }

        register_sidebar ( array (
          'name'          => $sidebar,
          'id'            => sanitize_title ( $sidebar ),
          'before_widget' => '<div id="%1$s" class="sidebar-item wow zoomIn widget %2$s" data-wow-delay="0.4s">',
          'after_widget'  => '</div><div class="empty-space marg-lg-b30"></div>',
          'before_title'  => '<h5 class="h5 widget-title">',
          'after_title'   => '</h5>',
        ) );
      }
    }
  }
  add_action( 'widgets_init', 'videoly_register_sidebar' );
}

/**
 * Set up homepage and menu
 *
 * @package adios
 * @since 1.0
 */
if(!function_exists('videoly_set_homepage_menu')) {
  function videoly_set_homepage_menu($demo_active_import, $demo_directory_path) {
    reset( $demo_active_import );
    $current_key = key( $demo_active_import );
    // set menu
    $wbc_menu_array = array( 'demo1');
    if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && in_array( $demo_active_import[$current_key]['directory'], $wbc_menu_array ) ) {
      $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
      $top_menu  = get_term_by( 'name', 'Top Menu', 'nav_menu' );
      if ( isset( $top_menu->term_id ) && isset($main_menu->term_id) ) {
        set_theme_mod( 'nav_menu_locations', array(
          'primary-menu' => $main_menu->term_id,
          'top-menu'     => $top_menu->term_id
        ));
      }
    }

    // set homepage
    $wbc_home_pages = array('demo1' => 'Home');
    if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && array_key_exists( $demo_active_import[$current_key]['directory'], $wbc_home_pages ) ) {
      $page = get_page_by_title( $wbc_home_pages[$demo_active_import[$current_key]['directory']] );
      if ( isset( $page->ID ) ) {
        update_option( 'page_on_front', $page->ID );
        update_option( 'show_on_front', 'page' );
      }
    }
  }
  add_action( 'wbc_importer_after_content_import', 'videoly_set_homepage_menu', 10, 2 );
}

/**
 * Inactive default widgets after import
 *
 * @package adios
 * @since 1.0
 */
if ( !function_exists( 'videoly_after_content_import' ) ) {
  function videoly_after_content_import( $demo_active_import , $demo_data_directory_path ) {
    $inactive = array();
    $sidebars = wp_get_sidebars_widgets();

    if( isset( $sidebars['wp_inactive_widgets'] ) ) {
      $inactive = $sidebars['wp_inactive_widgets'];
      unset( $sidebars['wp_inactive_widgets'] );
    }

    foreach ( $sidebars as $sidebar => $widgets ) {
      if(is_array($widgets)):
        $inactive = array_merge( $inactive, $widgets );
      endif;
      $sidebars[$sidebar] = array();
    }

    $sidebars['wp_inactive_widgets'] = $inactive;

    wp_set_sidebars_widgets( $sidebars );
  }

  add_action( 'wbc_importer_after_content_import', 'videoly_after_content_import', 10, 2 );
}



/**
* @return null
* @param none
* loads all the js and css script to frontend
**/
if( !function_exists('videoly_enqueue_scripts')) {

  function videoly_enqueue_scripts() {

    if(( is_admin())) { return; }

    if (is_singular()) { wp_enqueue_script( 'comment-reply' ); }

    wp_enqueue_script('videoly-global',       get_template_directory_uri() .'/js/global.js',array('jquery'), VIDEOLY_THEME_VERSION,true);
    wp_register_script('swiper',               get_template_directory_uri() .'/js/idangerous.swiper.min.js',array('jquery'), VIDEOLY_THEME_VERSION,true);
    wp_enqueue_script('match-height',         get_template_directory_uri() .'/js/match.height.min.js',array('jquery'), VIDEOLY_THEME_VERSION,true);
    wp_enqueue_script('image-load',           get_template_directory_uri() .'/js/image.loaded.min.js',array('jquery'), VIDEOLY_THEME_VERSION,true);
    wp_enqueue_script('appear',             get_template_directory_uri() .'/js/jquery.appear.js',array('jquery'), VIDEOLY_THEME_VERSION,true);
    
    wp_localize_script('map', 'get',
      array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'siteurl' => get_template_directory_uri()
      )
    );

    wp_enqueue_style('videoly-fonts',         videoly_fonts_url(), null, VIDEOLY_THEME_VERSION );
    wp_enqueue_style('font-awesome-theme',    get_template_directory_uri(). '/css/font-awesome.min.css',null, VIDEOLY_THEME_VERSION);
    wp_register_style('landing',              get_template_directory_uri(). '/css/landing.css',null, VIDEOLY_THEME_VERSION);
    wp_enqueue_style('bootstrap',             get_template_directory_uri(). '/css/bootstrap.min.css',null, VIDEOLY_THEME_VERSION);
    wp_register_style('swiper',               get_template_directory_uri(). '/css/idangerous.swiper.css',null, VIDEOLY_THEME_VERSION);
    wp_enqueue_style('videoly-main-style',    get_template_directory_uri(). '/css/style.css',null, VIDEOLY_THEME_VERSION);

    $pages = array();
  
    if (videoly_get_post_opt('page-show-special-content-after-content')) {
      $pages[] = videoly_get_post_opt('page-after-special-content');
    }
  
    if (is_array($pages) && count($pages) > 0) {
      foreach ($pages as $page) {        
        if (empty($page)) { continue; }
        $shortcodes_custom_css = get_post_meta( $page, '_wpb_shortcodes_custom_css', true );
        if(!empty($shortcodes_custom_css)) {
          wp_add_inline_style( 'videoly-main-style', $shortcodes_custom_css );
        }
      }
    }

    $css_code    = videoly_get_opt('css_editor');
    $accent_code = videoly_accent_css();
    $inline_css  = videoly_inline_css();
    $style       = '';
    $style       .= ( !empty($css_code) || !empty($accent_code)|| !empty($inline_css)) ? $css_code.$accent_code.$inline_css:'';

    wp_add_inline_style('videoly-main-style', $style);
  }
  add_action( 'wp_enqueue_scripts', 'videoly_enqueue_scripts' );
}

if(! function_exists('videoly_include_required_plugins')) {

  function videoly_include_required_plugins() {

    $plugins = array(

      array(
        'name'     => 'Redux Framework',
        'slug'     => 'redux-framework',
        'required' => true,
      ),

      array(
        'name'               => esc_html__('Contact Form 7', 'videoly'), // The plugin name
        'slug'               => 'contact-form-7', // The plugin slug (typically the folder name)
        'required'           => false, // If false, the plugin is only 'recommended' instead of required
        'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
        'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
        'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
        'external_url'       => '', // If set, overrides default API URL and points to an external URL
      ),
      array(
        'name'               => esc_html__('All-in-One WP Migration', 'videoly'), // The plugin name
        'slug'               => 'all-in-one-wp-migration', // The plugin slug (typically the folder name)
        'required'           => false, // If false, the plugin is only 'recommended' instead of required
        'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
        'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
        'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
        'external_url'       => '', // If set, overrides default API URL and points to an external URL
      ),      
      array(
        'name'               => esc_html__('WPide', 'videoly'), // The plugin name
        'slug'               => 'wpide', // The plugin slug (typically the folder name)
        'required'           => false, // If false, the plugin is only 'recommended' instead of required
        'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
        'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
        'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
        'external_url'       => '', // If set, overrides default API URL and points to an external URL
      ),
      array(
        'name'               => esc_html__('Visual Composer', 'videoly'), // The plugin name
        'slug'               => 'js_composer', // The plugin slug (typically the folder name)
        'source'             => get_template_directory_uri().'/plugins/js_composer.zip', // The plugin source
        'required'           => true, // If false, the plugin is only 'recommended' instead of required
        'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
        'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
        'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
        'external_url'       => '', // If set, overrides default API URL and points to an external URL
      ),
      array(
        'name'               => esc_html__('Videoly Addons', 'videoly'), // The plugin name
        'slug'               => 'videoly-addons', // The plugin slug (typically the folder name)
        'source'             =>  get_template_directory_uri().'/plugins/videoly-addons.zip', // The plugin source
        'required'           => true, // If false, the plugin is only 'recommended' instead of required
        'version'            => '1.2', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
        'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
        'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
        'external_url'       => '', // If set, overrides default API URL and points to an external URL
      ),
    );

    $config = array(
      'id'           => 'videoly',              // Unique ID for hashing notices for multiple instances of TGMPA.
      'default_path' => '',                      // Default absolute path to bundled plugins.
      'menu'         => 'tgmpa-install-plugins', // Menu slug.
      'parent_slug'  => 'themes.php',            // Parent menu slug.
      'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
      'has_notices'  => true,                    // Show admin notices or not.
      'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
      'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
      'is_automatic' => false,                   // Automatically activate plugins after installation or not.
      'message'      => '',                      // Message to output right before the plugins table.
    );

    tgmpa( $plugins, $config );

  }
  add_action( 'tgmpa_register', 'videoly_include_required_plugins' );
}

