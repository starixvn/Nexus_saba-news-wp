<?php

$redux_opt_name = REDUX_OPT_NAME;

function videoly_redux_add_metaboxes($metaboxes) {

  // Variable used to store the configuration array of metaboxes
  $metaboxes = array();

  $metaboxes[] = videoly_redux_get_page_metaboxes();
  $metaboxes[] = videoly_redux_get_video_post_metaboxes();
  $metaboxes[] = videoly_redux_get_gallery_post_metaboxes();
  $metaboxes[] = videoly_redux_get_post_adv_metaboxes();
  $metaboxes[] = videoly_redux_get_social_metaboxes();
  $metaboxes[] = videoly_redux_get_post_metaboxes();

  return $metaboxes;
}
add_action('redux/metaboxes/'.$redux_opt_name.'/boxes', 'videoly_redux_add_metaboxes');

/**
 * Get configuration array for post metaboxes
 * @return type
 */
function videoly_redux_get_post_metaboxes() {
  
  // Variable used to store the configuration array of sections
  $sections = array();
  
  // Metabox used to overwrite theme options by page
  require get_template_directory() . '/framework/admin/metaboxes/post.php';
  
  return array(
    'id' => 'videoly-post-options',
    'title' => esc_html__('Post Options', 'videoly'),
    'post_types' => array('post', 'movie'),
    'position' => 'normal', // normal, advanced, side
    'priority' => 'high', // high, core, default, low
    'sections' => $sections,
  );
}


/**
 * Get configuration array for contact template
 * @return type
 */
function videoly_redux_get_social_metaboxes() {

  // Variable used to store the configuration array of sections
  $sections = array();

  // Metabox used to overwrite theme options by page
  require get_template_directory() . '/framework/admin/metaboxes/social-site.php';

  return array(
    'id' => 'videoly-template-social-options',
    'title' => esc_html__('Social Options', 'videoly'),
    'post_types' => array('social-site'),
    'position' => 'normal', // normal, advanced, side
    'priority' => 'high', // high, core, default, low
    'sections' => $sections,
  );
}


/**
 * Get configuration array for page metaboxes
 * @return type
 */
function videoly_redux_get_page_metaboxes() {

  // Variable used to store the configuration array of sections
  $sections = array();

  // Metabox used to overwrite theme options by page
  require get_template_directory() . '/framework/admin/metaboxes/general.php';
  require get_template_directory() . '/framework/admin/metaboxes/header.php';
  require get_template_directory() . '/framework/admin/metaboxes/slider.php';
  require get_template_directory() . '/framework/admin/metaboxes/title-wrapper.php';
  require get_template_directory() . '/framework/admin/metaboxes/content.php';
  require get_template_directory() . '/framework/admin/metaboxes/fonts.php';
  require get_template_directory() . '/framework/admin/metaboxes/footer.php';
  require get_template_directory() . '/framework/admin/metaboxes/sidebar.php';

  return array(
    'id' => 'videoly-page-options',
    'title' => esc_html__('Options', 'videoly'),
    'post_types' => array('page'),
    'position' => 'normal', // normal, advanced, side
    'priority' => 'high', // high, core, default, low
    'sections' => $sections
  );
}


/**
 * Get configuration array for video post metaboxes
 * @return type
 */
function videoly_redux_get_video_post_metaboxes() {

  // Variable used to store the configuration array of sections
  $sections = array();

  // Metabox used to overwrite theme options by page
  require get_template_directory() . '/framework/admin/metaboxes/post-video.php';

  return array(
    'id' => 'videoly-video-post-options',
    'title' => esc_html__('Video Post Options', 'videoly'),
    'post_types' => array('post', 'movie'),
    'position' => 'normal', // normal, advanced, side
    'priority' => 'high', // high, core, default, low
    'sections' => $sections,
    'post_format' => array('video')
  );
}

/**
 * Get configuration array for video post metaboxes
 * @return type
 */
function videoly_redux_get_gallery_post_metaboxes() {

  // Variable used to store the configuration array of sections
  $sections = array();

  // Metabox used to overwrite theme options by page
  require get_template_directory() . '/framework/admin/metaboxes/post-gallery.php';

  return array(
    'id' => 'videoly-gallery-post-options',
    'title' => esc_html__('Gallery Post Options', 'videoly'),
    'post_types' => array('post'),
    'position' => 'normal', // normal, advanced, side
    'priority' => 'high', // high, core, default, low
    'sections' => $sections,
    'post_format' => array('gallery')
  );
}

/**
 * Get configuration array for page metaboxes
 * @return type
 */
function videoly_redux_get_post_adv_metaboxes() {

  // Variable used to store the configuration array of sections
  $sections = array();

  // Metabox used to overwrite theme options by page
  require get_template_directory() . '/framework/admin/metaboxes/header.php';
  require get_template_directory() . '/framework/admin/metaboxes/title-wrapper.php';
  require get_template_directory() . '/framework/admin/metaboxes/content.php';
  require get_template_directory() . '/framework/admin/metaboxes/sidebar.php';
  require get_template_directory() . '/framework/admin/metaboxes/footer.php';

  return array(
    'id' => 'videoly-post-adv-options',
    'title' => esc_html__('Options', 'videoly'),
    'post_types' => array('post'),
    'position' => 'normal', // normal, advanced, side
    'priority' => 'high', // high, core, default, low
    'sections' => $sections
  );
}
