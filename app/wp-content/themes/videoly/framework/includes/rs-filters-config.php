<?php
/**
 * Filter Hooks
 *
 * @package make
 * @since 1.0
 */

/**
 * Title Filter
 *
 * @package make
 * @since 1.0
 */
if (! function_exists('videoly_wp_title') ) {
  function videoly_wp_title( $title, $sep ) {
    global $paged, $page;

    if ( is_feed() ) {
      return $title;
    } // end if

    // Add the site name.
    $title .= get_bloginfo( 'name' );

    // Add the site description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) ) {
      $title = "$title $sep $site_description";
    } // end if

    // Add a page number if necessary.
    if ( $paged >= 2 || $page >= 2 ) {
      $title = sprintf( __( 'Page %s', 'videoly' ), max( $paged, $page ) ) . " $sep $title";
    } // end if

    return $title;

  } // end rs_wp_title
  add_filter( 'wp_title', 'videoly_wp_title', 10, 2 );
}

/**
 * Post Column View
 *
 * @package videoly
 * @since 1.0
 */
if(!function_exists('videoly_posts_column_views')) {
  function videoly_posts_column_views($defaults) {
    $defaults['post_views'] = esc_html__('Views', 'videoly');
    return $defaults;
  }
  add_filter('manage_posts_columns', 'videoly_posts_column_views');
}

/**
 * Allow xml file to upload
 *
 * @package adios
 * @since 1.0
 */
if(!function_exists('videoly_upload_xml')) {
  function videoly_upload_xml($mimes) {
    $mimes = array_merge($mimes, array('xml' => 'application/xml'));
    return $mimes;
  }
  add_filter('upload_mimes', 'videoly_upload_xml');
}

/**
 * Avatar img class
 *
 * @package make
 * @since 1.0
 */
if( !function_exists('videoly_add_gravatar_class')) {
  function videoly_add_gravatar_class( $class ) {
    $class = str_replace("class='avatar", "class='tt-comment-form-ava", $class);
    return $class;
  }
  add_filter('get_avatar','videoly_add_gravatar_class');
}

/**
 * Body Filter Hook
 *
 * @package make
 * @since 1.0
 */
if( !function_exists('videoly_body_class')) {
  function videoly_body_class($classes) {
    $layout    = videoly_get_opt('main-layout');
    $classes[] = '';
    $classes[] = ($layout == 'default') ? 'no-sidebar':$layout;
    $classes[] = videoly_get_opt('page-layout');
    return $classes;
  }
  add_filter('body_class', 'videoly_body_class');
}

/**
 * Content Froce
 *
 * @package make
 * @since 1.0
 */
if(!function_exists('videoly_the_content_force')) {
  global $videoly_force_content;
  function videoly_the_content_force( $content ){

    global $videoly_force_content;

    if( is_singular() && ! empty( $videoly_force_content ) ) {
      $content = $videoly_force_content;
      $videoly_force_content = 0;
    }
    return $content;
  }
  add_filter('the_content', 'videoly_the_content_force' );
}

/**
 * Add Custom Class
 *
 * @package make
 * @since 1.0
 */
if(!function_exists('videoly_post_link_next_class')) {
  function videoly_post_link_next_class($format){
   $format = str_replace('href=', 'class="tt-blog-nav-title" href=', $format);
   return $format;
  }
  add_filter('next_post_link', 'videoly_post_link_next_class');
}

/**
 * Add Custom Class
 *
 * @package make
 * @since 1.0
 */
if(!function_exists('videoly_post_link_prev_class')) {
  function videoly_post_link_prev_class($format) {
   $format = str_replace('href=', 'class="tt-blog-nav-title" href=', $format);
   return $format;
  }
  add_filter('previous_post_link', 'videoly_post_link_prev_class');
}

/**
 * Allow demo name to be changed
 *
 * @package videoly
 * @since 1.0
 */
if(!function_exists('videoly_importer_filter_title')) {
  function videoly_importer_filter_title( $title ) {
    $title = esc_html__('Demo Content', 'videoly');
    return trim( ucfirst( str_replace( '-', ' ', $title ) ) );
  }
  add_filter( 'wbc_importer_directory_title', 'videoly_importer_filter_title', 10 );
}