<?php
/**
 * Frontend Theme Functions.
 *
 * @package videoly
 * @subpackage Template
 */
 /**
 * Theme Loader
 * @param string $logo_field
 * @param string $default_url
 * @param string $class
 */
if(!function_exists('videoly_loader')) {
  function videoly_loader() {  if(class_exists('ReduxFramework') && !videoly_get_opt('general-loader-enable-switch')) { return; } ?>
    <div id="loader-wrapper">
      <div id="loader"></div>
      <div id="loading-text"><?php echo esc_html__('LOADING', 'videoly'); ?></div>
    </div>
  <?php
  }
}

 /**
 * Sideheader
 * @param string $logo_field
 * @param string $default_url
 * @param string $class
 */
 if( !function_exists('videoly_sideheader')) {
  function videoly_sideheader() { ?>
    <div class="tt-mobile-block">
        <div class="tt-mobile-close"></div>
        <?php videoly_logo('side-header-logo', get_template_directory_uri().'/img/header/logo_2.png', 'tt-mobile-logo img-responsive'); ?>
        <nav class="tt-mobile-nav">
          <?php videoly_main_menu('side-menu', 'side-header-nav'); ?>
        </nav>
    </div>
    <div class="tt-mobile-overlay"></div>
  <?php
  }
}

 /**
 * Sponsor Ads
 * @param string $logo_field
 * @param string $default_url
 * @param string $class
 */
 if( !function_exists('videoly_sponsor_ads')) {
  function videoly_sponsor_ads($style = 'style1') {
    $ads_title     = videoly_get_opt('header-ads-title');
    $ads_btn_text  = videoly_get_opt('header-ads-btn-text');
    $ads_btn_link  = videoly_get_opt('header-ads-btn-link');
    $ads_img       = videoly_get_opt('header-ads-img');
    $ads_size_text = videoly_get_opt('header-ads-size-text');

    switch ($style) {
      case 'style2':
      if(isset($ads_img['url']) && !empty($ads_img['url'])): ?>
      <div class="tt-sponsor type-2 clearfix">
        <a class="tt-sponsor-img">
          <img src="<?php echo esc_url($ads_img['url']); ?>" height="89" width="62" alt="">
        </a>
        <div class="tt-sponsor-info">
          <div class="tt-sponsor-entry">
            <?php if(!empty($ads_title)): ?>
              <a class="tt-sponsor-title c-h6" href="<?php echo esc_url($ads_btn_link); ?>"><?php echo esc_html($ads_title); ?><?php echo (!empty($ads_size_text)) ? '('.$ads_size_text.')':''; ?></a>
            <?php endif; ?>
            <div class="simple-text color-5">
              <p><i>You can remove this easily, if you don`t need!</i></p>
            </div>
          </div>
        </div>
      </div>
      <?php
        endif;
        break;
      case 'style1':
      default:
      if(isset($ads_img['url']) && !empty($ads_img['url'])): ?>
        <div class="tt-sponsor clearfix">
          <a class="tt-sponsor-img">
            <img src="<?php echo esc_url($ads_img['url']); ?>" height="89" width="62" alt="">
          </a>
          <div class="tt-sponsor-info">
            <?php if(!empty($ads_title)): ?>
              <a class="tt-sponsor-title c-h6" href="<?php echo esc_url($ads_btn_link); ?>"><?php echo esc_html($ads_title); ?></a>
            <?php endif; ?>
            <?php if(!empty($ads_size_text)): ?>
            <div class="simple-text size-2">
              <p><?php echo esc_html($ads_size_text); ?></p>
            </div>
            <?php endif; ?>
            <?php if(!empty($ads_btn_text)): ?>
            <a class="c-btn type-1" href="<?php echo esc_url($ads_btn_link); ?>"><span><?php echo esc_html($ads_btn_text); ?></span></a>
            <?php endif; ?>
          </div>
        </div>
      <?php endif;
        break;
    }
    
  }
}


 /**
 * Theme logo
 * @param string $logo_field
 * @param string $default_url
 * @param string $class
 */
 if( !function_exists('videoly_logo')) {
  function videoly_logo($logo_field = '', $default_url = '', $class = '') {

    if (empty($logo_field)) {
      $logo_field = 'logo';
    }

    $logo = '';

    if( videoly_get_opt( $logo_field ) != null ) {
      $logo_array = videoly_get_opt( $logo_field );
    }

    if( (!isset( $logo_array['url'] ) || empty($logo_array['url'])) && empty($default_url)) {
      return;
    }

    if(empty($class)) {
      $class = ' logo';
    }

    if( !isset( $logo_array['url'] ) || empty($logo_array['url']) ) {
      $logo_url = $default_url;
    } else {
      $logo_url = $logo_array['url'];
    }

    ?>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="<?php echo videoly_sanitize_html_classes($class); ?>"><img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo( 'name' )); ?>"></a>
    <?php
  }
}

/**
 *
 * Main Menu
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( !function_exists('videoly_main_menu')) {
  function videoly_main_menu($class = '', $menu_id = 'nav') {
    if ( function_exists('wp_nav_menu') && has_nav_menu( 'primary-menu' ) ) {
      $menu = '';
      if(is_singular()) {
        $menu = videoly_get_opt('header-primary-menu');
      }
      wp_nav_menu(array(
        'theme_location' => 'primary-menu',
        'container'      => false,
        'menu_id'        => $menu_id,
        'menu'           => $menu,
        'menu_class'     => $class,
        'walker'         => new videoly_menu_widget_walker_nav_menu()
      ));
    } else {
      echo '<a target="_blank" href="'. admin_url('nav-menus.php') .'" class="nav-list cell-view no-menu">'. esc_html__( 'You can edit your menu content on the Menus screen in the Appearance section.', 'videoly' ) .'</a>';
    }
  }
}

/**
 *
 * Pagination
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'videoly_paging_nav' ) ) {
  function videoly_paging_nav( $max_num_pages = false, $blog_style = 'default' ) {

    if (get_query_var('paged')) {
      $paged = get_query_var('paged');
    } elseif (get_query_var('page')) {
      $paged = get_query_var('page');
    } else {
      $paged = 1;
    }

    if ($max_num_pages === false) {
      global $wp_query;
      $max_num_pages = $wp_query -> max_num_pages;
    }

    $big = 999999999; // need an unlikely integer

    $links = paginate_links( array(
      'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
      'format'    => '?paged=%#%',
      'current'   => $paged,
      'total'     => $max_num_pages,
      'prev_next' => true,
      'prev_text' => esc_html__('...', 'videoly'),
      'prev_text' => esc_html__('...', 'videoly'),
      'end_size'  => 1,
      'mid_size'  => 2,
      'type'      => 'list',
    ) );

    if (!empty($links)): ?>
      <div class="text-center">
         <?php echo wp_kses_post($links); ?>                           
      </div>
      <div class="empty-space marg-sm-b60"></div>
    <?php endif;

  }
}

/**
 *
 * Get the Page Title
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( !function_exists('videoly_get_the_title')) {
  function videoly_get_the_title() {

    $title = '';

    //woocoomerce page
    if (function_exists('is_woocoomerce') && is_woocommerce() || function_exists('is_shop') && is_shop()):
      if (apply_filters( 'woocommerce_show_page_title', true )):
        $title = woocommerce_page_title(false);
      endif;
    // Default Latest Posts page
    elseif( is_home() && !is_singular('page') ) :
      $title = esc_html__('Blog','videoly');

    // Singular
    elseif( is_singular() ) :
      $title = get_the_title();

    // Search
    elseif( is_search() ) :
      global $wp_query;
      $total_results = $wp_query->found_posts;
      $prefix = '';

      if( $total_results == 1 ){
        $prefix = esc_html__('1 search result for', 'videoly');
      }
      else if( $total_results > 1 ) {
        $prefix = $total_results . ' ' . esc_html__('search results for', 'videoly');
      }
      else {
        $prefix = esc_html__('Search results for', 'videoly');
      }
      //$title = $prefix . ': ' . get_search_query();
      $title = get_search_query();

    // Category and other Taxonomies
    elseif ( is_category() ) :
      $title = single_cat_title('', false);

    elseif ( is_tag() ) :
      $title = single_tag_title('', false);

    elseif ( is_author() ) :
      $title = wp_kses(sprintf( __( 'Author: %s', 'videoly' ), '<span class="vcard">' . get_the_author() . '</span>' ));

    elseif ( is_day() ) :
      $title = wp_kses(sprintf( __( 'Day: %s', 'videoly' ), '<span>' . get_the_date() . '</span>' ));

    elseif ( is_month() ) :
      $title = wp_kses(sprintf( __( 'Month: %s', 'videoly' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'videoly' ) ) . '</span>' ));

    elseif ( is_year() ) :
      $title = wp_kses(sprintf( __( 'Year: %s', 'videoly' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'videoly' ) ) . '</span>' ));

    elseif( is_tax() ) :
      $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
      $title = $term->name;

    elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
      $title = esc_html__( 'Asides', 'videoly' );

    elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
      $title = esc_html__( 'Galleries', 'videoly');

    elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
      $title = esc_html__( 'Images', 'videoly');

    elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
      $title = esc_html__( 'Videos', 'videoly' );

    elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
      $title = esc_html__( 'Quotes', 'videoly' );

    elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
      $title = esc_html__( 'Links', 'videoly' );

    elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
      $title = esc_html__( 'Statuses', 'videoly' );

    elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
      $title = esc_html__( 'Audios', 'videoly' );

    elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :
      $title = esc_html__( 'Chats', 'videoly' );

    elseif( is_404() ) :
      $title = esc_html__( '404', 'videoly' );

    else :
      $title = esc_html__( 'Archives', 'videoly' );
    endif;

    return $title;
  }
}

/**
 *
 * Social Share
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if(!function_exists('videoly_social_share')) {
  function videoly_social_share($style) {
    if(class_exists('ReduxFramework') && !videoly_get_opt('post-enable-post-share')) { return; }
    global $post;
    $pinterest_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'videoly-big-alt' );
    switch ($style) {
      case 'style1':
      default: ?>

        <div class="tt-share position-2">
          <ul class="tt-share-list">
            <li><a class="tt-share-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url(get_the_permalink()); ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
            <li><a class="tt-share-twitter" href="https://twitter.com/home?status=<?php echo esc_url(get_the_permalink()); ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
            <li><a class="tt-share-pinterest" href="https://pinterest.com/pin/create/button/?url=&amp;media=<?php echo esc_url($pinterest_image[0]); ?>&amp;description=<?php echo urlencode(get_the_title()); ?>"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
            <li><a class="tt-share-google" href="https://plus.google.com/share?url=<?php echo esc_url(get_the_permalink()); ?>"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
            <li><a class="tt-share-reddit" href="http://www.reddit.com/submit?url=<?php echo esc_url(get_the_permalink()); ?>&amp;title="><i class="fa fa-reddit-alien" aria-hidden="true"></i></a></li>
            <li><a class="tt-share-mail" href="http://digg.com/submit?url=<?php echo esc_url(get_the_permalink()); ?>&amp;title="><i class="fa fa-digg" aria-hidden="true"></i></a></li>
          </ul>
        </div>
        <?php
        break;

      case 'style2': ?>
      <?php
        break;
    }
  }
}


if ( ! function_exists( 'videoly_comment' ) ) :
/**
 * Comments and pingbacks. Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since videoly 1.0
 */
function videoly_comment( $comment, $args, $depth ) {
  $GLOBALS['comment'] = $comment;
  switch ( $comment->comment_type ) :
    case 'pingback' :
    case 'trackback' :
      ?>
      <li <?php comment_class('comment'); ?> id="li-comment-<?php comment_ID(); ?>">
        <div class="media-body"><?php _e( 'Pingback:', 'videoly' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'videoly' ), ' ' ); ?></div>
      </li>
      <?php
    break;

    default :
      $class = array('comment_wrap');
      if ($depth > 1) {
        $class[] = 'chaild';
      }
      ?>
      <!-- Comment Item -->
      <li <?php comment_class('comment-list'); ?> id="comment-<?php comment_ID(); ?>">

        <div class="tt-comment-container clearfix">
          <a class="tt-comment-avatar" href="#">
            <?php echo get_avatar( $comment, 40 ); ?>
          </a>

          <div class="tt-comment-info">


            <div class="tt-comment-label">
              <span><a href="#" class="tt-comment-name"><?php comment_author_link();?></a></span>
              <span><?php echo comment_date(get_option('date_format')) ?></span>
            </div>


            <div class="simple-text font-poppins">
              <?php if ( $comment->comment_approved == '0' ) : ?>
                <em><?php _e( 'Your comment is awaiting moderation.', 'videoly' ); ?></em>
              <?php endif; ?>
              <?php comment_text(); ?>
            </div>




            <?php 
              $reply = get_comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => 2 ) ) );
              if (!empty($reply)): ?>
                <?php echo wp_kses_post($reply); ?>
              <?php endif;
              edit_comment_link( __( 'Edit', 'videoly' ), '', '' );
            ?>
          </div>



        </div>
      <?php
      break;
  endswitch;
}

endif; // ends check for videoly_comment()

if (!function_exists('videoly_close_comment')):
/**
 * Close comment
 *
 * @since videoly 1.0
 */
function videoly_close_comment() { ?>
  </li>
  <!-- End Comment Item -->
<?php }

endif; // ends check for videoly_close_comment()


/**
 *
 * Related Post
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if(!function_exists('videoly_related_post')) {
  function videoly_related_post() {
    if(class_exists('ReduxFramework') && !videoly_get_opt('post-enable-similar-posts')) { return; }
    global $post;
    $tags = wp_get_post_tags($post->ID);

    if(!empty($tags) && is_array($tags)):
      $simlar_tag = $tags[0]->term_id;
    ?>

    <?php
      $args = array(
        'tag__in'             => array($simlar_tag),
        'post__not_in'        => array($post->ID),
        'posts_per_page'      => 4,
        'meta_query'          => array(array('key' => '_thumbnail_id', 'compare' => 'EXISTS')),
        'ignore_sticky_posts' => 1,
      );
      $re_query = new WP_Query($args);
      if($re_query->have_posts()):
      
    ?>

    <div class="tt-title-block">
      <h3 class="tt-title-text"><?php echo esc_html__('You Might also Like', 'videoly'); ?></h3>
    </div>
    <div class="empty-space marg-lg-b25"></div>
    <div class="row">

    <?php while ($re_query->have_posts()) : $re_query->the_post(); ?>
      <div <?php post_class('col-xs-6 col-sm-4 col-lg-3'); ?>>
        <div class="tt-post type-3">
          <?php videoly_blog_featured_image('videoly-small', 'img-responsive'); ?>
          <div class="tt-post-info">
            <a class="tt-post-title c-h5" href="<?php echo esc_url(get_the_permalink()); ?>"><small><?php the_title(); ?></small></a>
            <?php videoly_blog_author_date(); ?>
          </div>
        </div> 
        <div class="empty-space marg-lg-b15"></div>                 
      </div>
      <div class="clearfix visible-md-block"></div>

      <?php endwhile; wp_reset_postdata(); ?>

    </div>
    <div class="empty-space marg-lg-b40 marg-sm-b30"></div>
  <?php endif;
    endif;
  }
}

/**
 * Get Social Icons links
 *
 * @param type $terms
 * @return boolean
 */
if(!function_exists('videoly_post_author_details')) {
  function videoly_post_author_details() {
    if(class_exists('ReduxFramework') && !videoly_get_opt('post-enable-author-description')) { return; }
    global $post;
    $curauth = get_userdata($post->post_author);
    if(!empty($curauth->description)): ?>
      <div class="tt-devider"></div>
      <div class="empty-space marg-lg-b60 marg-sm-b50 marg-xs-b30"></div>     
      <div class="tt-author clearfix">
        <a class="tt-author-img" href="#">
          <?php echo get_avatar( get_the_author_meta('ID'), 90 ); ?>
        </a>
        <div class="tt-author-info">
          <a class="tt-author-title" href="#"><?php the_author(); ?></a>
          <div class="simple-text font-poppins">
            <p><?php echo get_the_author_meta('description'); ?></p>
          </div>
          <ul class="tt-author-social">
            <?php videoly_social_links('%s', videoly_get_opt('author-social-icons-category')); ?>
          </ul>
        </div>
      </div>
      <div class="empty-space marg-lg-b55 marg-sm-b50 marg-xs-b30"></div>
    <?php endif;
  }
}

/**
 * Get Social Icons links
 *
 * @param type $terms
 * @return boolean
 */
if(!function_exists('videoly_social_links')) {
  function videoly_social_links($pattern = '%s', $category = '') {
    $args = array(
      'posts_per_page' => -1,
      'offset'          => 0,
      'orderby'         => 'menu_order',
      'order'           => 'ASC',
      'post_type'       => 'social-site',
      'post_status'     => 'publish'
    );

    if (!empty($category)) {
      $args['tax_query'] = array(
        array(
          'taxonomy' => 'social-site-category',
          'field'    => 'id',
          'terms'    => $category,
        ),
      );
    }

    $custom_query = new WP_Query( $args );
    if ( $custom_query->have_posts() ):

      $found_posts = $custom_query->found_posts;
      while ( $custom_query -> have_posts() ) :
        $custom_query -> the_post();
        echo sprintf($pattern, '<li><a href="'.esc_url(get_the_title()).'"><i class="fa '.esc_attr(videoly_get_post_opt('icon')).'"></i></a></li>');
      endwhile; // end of the loop.
    endif;
    wp_reset_postdata();
  }
}

/**
 * Get Social Icons links
 *
 * @param type $terms
 * @return boolean
 */
if(!function_exists('videoly_accent_css')) {
  function videoly_accent_css() {
    $accent_color_first  = videoly_get_opt('theme-skin-accent-first');
    if(empty($accent_color_first)) { return; }
    $output = 
   '.tt-header .main-nav > ul > li:not(.mega) > ul > li > a:hover,
    .tt-header .main-nav > ul > li:not(.mega) > ul > li > ul > li > a:hover,
    .tt-mega-list a:hover,.tt-s-popup-devider:after,
    .tt-s-popup-close:hover:before,.tt-s-popup-close:hover:after,.tt-tab-wrapper.type-1 .tt-nav-tab-item:before,
    .tt-pagination a:hover,.tt-pagination li.active a,.tt-thumb-popup-close:hover,.tt-video-popup-close:hover,
    .c-btn.type-1.color-2:before,.c-btn.type-1.style-2.color-2, .page-numbers a:hover, .page-numbers li span.current, .tt-post-quality {
      background: '.esc_attr($accent_color_first).';
    }

    .tt-header .main-nav > ul > li.active > a,
    .tt-header .main-nav > ul > li:hover > a,.tt-s-popup-btn:hover,
    .tt-header.color-2 .top-menu a:hover,.tt-header.color-2 .top-social a:hover,
    .tt-s-popup-submit:hover .fa,.tt-mslide-label a:hover,
    .tt-sponsor-title:hover,.tt-sponsor.type-2 .tt-sponsor-title:hover,
    .tt-post-title:hover,.tt-post-label span a:hover,
    .tt-post-bottom a:hover,.tt-post-bottom a:hover .fa,
    .tt-post.light .tt-post-title:hover,.tt-blog-user-content a:hover,
    .tt-blog-user.light .tt-blog-user-content a:hover,.simple-img-desc a:hover,
    .tt-author-title:hover,.tt-author-social a:hover,.tt-blog-nav-title:hover,
    .tt-comment-label a:hover,.tt-comment-reply:hover,
    .tt-comment-reply:hover .fa,
    .comment-reply-link:hover,
    .comment-reply-link:hover .fa,
    .comment-edit-link:hover,.tt-search-submit:hover,.tt-news-title:hover,
    .tt-mblock-title:hover,.tt-mblock-label a:hover,.simple-text a,.c-btn.type-1.color-2,
    .c-btn.type-1.style-2.color-2:hover,.c-btn.type-2:hover,.c-btn.type-3.color-2:hover,
    .c-btn.type-3.color-3 {
      color: '.esc_attr($accent_color_first).';
    }

    .c-pagination.color-2 .swiper-pagination-switch,
    .c-pagination.color-2 .swiper-active-switch,.tt-search input[type="text"]:focus,
    #loader,.c-btn.type-1.color-2,.c-input:focus,.c-btn.type-3.color-2:hover,.c-area:focus, .tt-title-text {
      border-color: '.esc_attr($accent_color_first).';
    }';

    return $output;
  }
}

/**
 * Inline CSS code
 *
 * @param type $terms
 * @return boolean
 */
if(!function_exists('videoly_inline_css')) {
  global $videoly_force_content;
  function videoly_inline_css(){
  if(is_404() || is_search()) { return; }
  global $videoly_force_content;

    $content = do_shortcode( get_page( get_the_ID() )->post_content );
    $oArgs = ThemeArguments::getInstance('inline_style'); 
    $inline_styles = $oArgs -> get('inline_styles');
    if (is_array($inline_styles) && count($inline_styles) > 0) {
      $videoly_force_content = $content;
      return videoly_css_compress( htmlspecialchars_decode( wp_kses_data( join( '', $inline_styles ) ) ) );
    }
    $oArgs -> reset();

  }  
}

/**
 * Blog Featured Image
 * @param type $type
 * @return array
 */
if(!function_exists('videoly_blog_featured_image')) {
  function videoly_blog_featured_image($image_size = 'full', $class_name = '') { ?>
    <?php if(has_post_thumbnail()): ?>
    <a class="tt-post-img custom-hover" href="<?php echo esc_url(get_the_permalink()); ?>">
      <?php the_post_thumbnail($image_size, array('class' => $class_name)); ?>
    </a>
  <?php endif;
  }
}

/**
 * Blog Category
 * @param type $type
 * @return array
 */
if(!function_exists('videoly_blog_category')) {
  function videoly_blog_category() { ?>
    <div class="tt-post-cat"><?php echo get_the_category_list( esc_html__( ', ', 'videoly' ) );?></div>
  <?php
  }
}

/**
 * Blog Title
 * @param type $type
 * @return array
 */
if(!function_exists('videoly_blog_title')) {
  function videoly_blog_title($class = 'c-h2') { ?>
    <a class="tt-post-title <?php echo esc_attr($class); ?>" href="<?php echo esc_url(get_the_permalink()); ?>"><?php the_title(); ?></a>
  <?php
  }
}

/**
 * Blog Author & Date
 * @param type $type
 * @return array
 */
if(!function_exists('videoly_blog_author_date')) {
  function videoly_blog_author_date() { ?>
    <div class="tt-post-label">
      <span><a href="#"><?php echo get_the_author(); ?></a></span>
      <span><?php the_time('M d' ); ?></span>
    </div>
  <?php
  }
}

/**
 * Blog Excerpt
 * @param type $type
 * @return array
 */
if(!function_exists('videoly_blog_excerpt')) {
  function videoly_blog_excerpt($length = 20) { ?>
    <div class="simple-text">
      <p><?php echo videoly_auto_post_excerpt($length); ?></p>
    </div>
  <?php
  }
}

/**
 * Blog Post Bottom
 * @param type $type
 * @return array
 */
if(!function_exists('videoly_blog_post_bottom')) {
  function videoly_blog_post_bottom() { ?>
    <div class="tt-post-bottom">
      <span><a href="#"><i class="fa fa-eye" aria-hidden="true"></i><?php echo videoly_getPostViews(get_the_ID()); ?></a></span>
      <span><a href="#"><i class="fa fa-comment" aria-hidden="true"></i><?php comments_number( '0 Comment', '01 Comment', '% Comments' ); ?></a></span>
    </div>
  <?php
  }
}


/**
 * Post Navigation
 * @param type $type
 * @return array
 */
if(!function_exists('videoly_post_navigation')) {
  function videoly_post_navigation() { 

    $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
    $next     = get_adjacent_post( false, '', false );

    if(empty($previous) && empty($next)) { return; }

    ?>

    <!-- TT-NAV -->
    <div class="row">

      <?php if (get_previous_post()): ?>
      <div class="col-sm-6">
        <div class="tt-blog-nav left">
          <div class="tt-blog-nav-label"><?php esc_html_e('Previous Article', 'videoly'); ?></div>
          <?php previous_post_link('%link', '%title'); ?> 
        </div>
        <div class="empty-space marg-xs-b20"></div>
      </div>
      <?php endif; ?>

      <?php if (get_next_post()): ?>
      <div class="col-sm-6">
        <div class="tt-blog-nav right">
          <div class="tt-blog-nav-label"><?php esc_html_e('Next Article', 'videoly'); ?></div>
          <?php next_post_link('%link', '%title'); ?>                                    
        </div>
      </div>
      <?php endif; ?>
    </div>

    <div class="empty-space marg-lg-b55 marg-sm-b50 marg-xs-b30"></div>
  <?php
  }
}

/**
 * Video Popup
 * @param type $type
 * @return array
 */
if(!function_exists('videoly_popup')) {
  function videoly_popup() { ?>
    <div class="tt-video-popup">
      <div class="tt-video-popup-overlay"></div>
      <div class="tt-video-popup-content">
        <div class="tt-video-popup-layer"></div>
        <div class="tt-video-popup-container">
          <div class="tt-video-popup-align">
            <div class="embed-responsive embed-responsive-16by9">
              <iframe class="embed-responsive-item" src="about:blank"></iframe>
            </div>
          </div>
          <div class="tt-video-popup-close"></div>
        </div>
      </div>
    </div> 

    <div class="tt-thumb-popup">
      <div class="tt-thumb-popup-overlay"></div>
      <div class="tt-thumb-popup-content">
        <div class="tt-thumb-popup-layer"></div>
        <div class="tt-thumb-popup-container">
          <div class="tt-thumb-popup-align">
            <img class="tt-thumb-popup-img img-responsive" src="about:blank" alt="">
          </div>
          <div class="tt-thumb-popup-close"></div>
        </div>
      </div>
    </div>  
  <?php
  }
}

/**
 * Search Popup
 * @param type $type
 * @return array
 */
if(!function_exists('videoly_search_popup')) {
  function videoly_search_popup() { ?>
    <div class="tt-s-popup">
      <div class="tt-s-popup-overlay"></div>
      <div class="tt-s-popup-content">
        <div class="tt-s-popup-layer"></div>
        <div class="tt-s-popup-container">
          <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" class="tt-s-popup-form">
            <div class="tt-s-popup-field">
              <input type="text" id="s" name="s" value="" placeholder="Search" class="input" required>
              <div class="tt-s-popup-devider"></div>
              <h3 class="tt-s-popup-title"><?php echo esc_html__('Type to search', 'videoly'); ?></h3>     
            </div>
            <a href="#" class="tt-s-popup-close"></a>
          </form> 
        </div>
      </div>
    </div>
  <?php
  }
}


/**
 * Post Grid
 * @param type $type
 * @return array
 */
if(!function_exists('videoly_post_grid')) {
  function videoly_post_grid($class) { ?>
    <div <?php post_class($class.' post-handy-picked'); ?>>
      <div class="tt-post type-3">
        <?php videoly_post_format('videoly-small', 'img-responsive'); ?>
          <div class="tt-post-info">
            <?php videoly_blog_title('c-h5'); ?>
            <?php videoly_blog_author_date(); ?>
          </div>
      </div> 
      <div class="empty-space marg-lg-b25"></div>                 
    </div>
  <?php
  }
}

/**
 * Post Format
 *
 * @param type $terms
 * @return boolean
 */
if(!function_exists('videoly_post_format')) {
  function videoly_post_format($image_size = '', $class = '', $post_format = '') {
    $post_format = (empty($post_format)) ? get_post_format():$post_format;
    switch ($post_format) {
      case 'video': 
      $video_url      = videoly_get_post_opt('post-video-url'); 
      $video_length   = videoly_get_post_opt('post-video-length'); 
      $video_quality  = videoly_get_post_opt('post-video-quality'); 
      $video_lightbox = videoly_get_post_opt('post-video-lightbox'); 
      $permalink      = ($video_lightbox) ? $video_url.'?autoplay=1':get_the_permalink();

      if(!empty($video_url)): ?>
        <a class="tt-post-img <?php echo ($video_lightbox) ? 'tt-video-open':''; ?> custom-hover " href="<?php echo esc_url($permalink); ?>">
          <?php if(!empty($video_length) || !empty($video_quality)): ?>
          <div class="tt-video-attributes">
            <?php if(!empty($video_quality)): ?>
              <span class="tt-post-quality"><?php echo esc_html($video_quality); ?></span>
            <?php endif; ?>
            <?php if(!empty($video_length)): ?>
              <span class="tt-post-length"><?php echo esc_html($video_length); ?></span>
            <?php endif; ?>
          </div>
          <?php endif; ?>
          <?php the_post_thumbnail($image_size, array('class' => $class)); ?>
        </a>
        <?php endif;
        break;
      case 'gallery': ?>
        <?php
          wp_enqueue_script('swiper');
          wp_enqueue_style('swiper'); 
          $gallery = videoly_get_post_opt('post-gallery');
          if(is_array($gallery)): ?>

          <div class="tt-post-img swiper-container" data-autoplay="5000" data-loop="1" data-speed="500" data-center="0" data-slides-per-view="1">
            <div class="swiper-wrapper">

              <?php foreach($gallery as $key => $item): ?>
              <div class="swiper-slide <?php echo ($key == 0) ? 'active':''; ?>" data-val="<?php echo esc_attr($key); ?>">
                <a class="custom-hover" href="<?php echo esc_url(get_the_permalink()); ?>">
                  <?php echo wp_get_attachment_image( $item['attachment_id'], $image_size, array('class' => $class) ); ?>
                </a>                                         
              </div>
              <?php endforeach; ?>

            </div>
            <div class="pagination c-pagination"></div>
            <div class="swiper-arrow-left c-arrow left hidden-xs hidden-sm"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
            <div class="swiper-arrow-right c-arrow right hidden-xs hidden-sm"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
          </div>
        <?php endif; ?>

        <?php
        break;
      
      default:
        videoly_blog_featured_image($image_size, $class);
        break;
    }
  }
}

/**
 * Post Format
 *
 * @param type $terms
 * @return boolean
 */
if(!function_exists('videoly_weekly_post_format')) {
  function videoly_weekly_post_format($image_url = '', $post_format, $permalink, $post_gallery = array(), $post_video_url = '', $video_length = '', $video_quality, $video_lightbox) {
    $permalink = ($video_lightbox) ? $post_video_url.'?autoplay=1':$permalink;
    switch ($post_format) {
      case 'video': if(!empty($post_video_url)): ?>
        <a class="tt-post-img <?php echo ($video_lightbox) ? 'tt-video-open':''; ?> custom-hover " href="<?php echo esc_url($permalink); ?>">
          <?php if(!empty($video_length) || !empty($video_quality)): ?>
          <div class="tt-video-attributes">
            <?php if(!empty($video_quality)): ?>
              <span class="tt-post-quality"><?php echo esc_html($video_quality); ?></span>
            <?php endif; ?>
            <?php if(!empty($video_length)): ?>
              <span class="tt-post-length"><?php echo esc_html($video_length); ?></span>
            <?php endif; ?>
          </div>
          <?php endif; ?>
          <img class="img-responsive" src="<?php echo esc_url($image_url); ?>" alt="">
        </a>
        <?php endif;
        break;

      case 'gallery': ?>
        <?php
          wp_enqueue_script('swiper');
          wp_enqueue_style('swiper'); 
          if(is_array($post_gallery) && !empty($post_gallery)): ?>

          <div class="tt-post-img swiper-container" data-autoplay="5000" data-loop="1" data-speed="500" data-center="0" data-slides-per-view="1">
            <div class="swiper-wrapper">

              <?php foreach($post_gallery as $key => $item): ?>
              <div class="swiper-slide <?php echo ($key == 0) ? 'active':''; ?>" data-val="<?php echo esc_attr($key); ?>">
                <a class="custom-hover" href="<?php echo esc_url($permalink); ?>">
                  <img class="img-responsive" src="<?php echo esc_url($image_url); ?>" alt="">
                </a>                                         
              </div>
              <?php endforeach; ?>

            </div>
            <div class="pagination c-pagination"></div>
            <div class="swiper-arrow-left c-arrow left hidden-xs hidden-sm"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
            <div class="swiper-arrow-right c-arrow right hidden-xs hidden-sm"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
          </div>
        <?php endif; ?>

        <?php
        break;
      
      default: ?>
        <a class="tt-post-img custom-hover" href="<?php echo esc_url($permalink); ?>">
          <img class="img-responsive" src="<?php echo esc_url($image_url); ?>" alt="">
        </a>
      <?php
        break;
    }
  }
}