<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Telegram
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function telegram_body_classes( $classes ) {
    $global_layout = telegram_get_option( 'global_layout' );
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

    if ($global_layout == 'left-sidebar') {
        $classes[]= 'left-sidebar';
    }
    elseif ($global_layout == 'no-sidebar') {
        $classes[]= 'no-sidebar';
    }
    else{
        $classes[]= 'right-sidebar';

    }

	return $classes;
}
add_filter( 'body_class', 'telegram_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function telegram_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'telegram_pingback_header' );

if( !function_exists( 'telegram_header_tages' ) ):

    function telegram_header_tages() {

        $latest_post_query = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 50, 'post__not_in' => get_option("sticky_posts") ) );
        $tag_lists = array();

        if( $latest_post_query->have_posts() ):

            while( $latest_post_query->have_posts() ):
                $latest_post_query->the_post();

                $tags = get_the_tags( get_the_ID() );

                if( $tags ){

                    foreach( $tags as $tag ){

                        if( !in_array($tag->term_id, $tag_lists) ){
                            
                            $tag_lists[ $tag->term_id ] = $tag->count;

                        }

                    }

                }

            endwhile;

        endif;

        arsort( $tag_lists);

        $telegram_default = telegram_get_default_theme_options();
        $telegram_header_tags = telegram_get_option('top_header_tags');
        if( $telegram_header_tags  == 1 ){

            $telegram_header_tags_title = telegram_get_option('top_header_tags_title');
            $telegram_header_tags_number = telegram_get_option('top_header_tags_count');
            ?>
            <div class="twp-top-tag twp-d-flex">
                <div class="twp-icon">
                    <span><i class="ion ion-ios-flame"></i></span>
                </div>
                <?php if( $telegram_header_tags_title ){ ?>
                    <div class="twp-tags-title">
                        <span><?php echo esc_html( $telegram_header_tags_title ); ?></span>
                    </div>
                <?php } ?>
                <div class="twp-tags-list">
                    <?php
                    if ($tag_lists) {
                        $count  = 1;
                        foreach( $tag_lists as $key => $value ){


                            $tag = get_tag($key); // <-- your tag ID
                            ?>
                            <a href="<?php echo esc_url(get_tag_link($key)); ?>" class="tags-title"><?php echo esc_html($tag->name); ?></a>
                            <?php

                            if( $count == $telegram_header_tags_number ){ break; }
                            $count++;
                        }
                    }
                    ?>
                </div>
            </div>

            <?php
        }

    }
endif;




if ( ! function_exists( 'telegram_posts_navigation' ) ) :
    /**
     * Posts navigation.
     *
     * @since 1.0.0
     */
    function telegram_posts_navigation() {

        $pagination_type = telegram_get_option( 'pagination_type' );

        switch ( $pagination_type ) {

            case 'default':
                echo '<div class="twp-pagination">';
                    the_posts_navigation();
                echo '</div>';
            break;

            case 'numeric':
                echo '<div class="twp-pagination twp-pagination-numeric">';
                    the_posts_pagination(array(
                            'mid_size' => 4,
                            'prev_text'          => '<i class="ion ion-md-arrow-back"></i>',
                            'next_text'          => '<i class="ion ion-md-arrow-forward"></i>',
                            'screen_reader_text' => '',
                        ));
                echo '</div>';
            break;

            default:
            break;
        }

    }
endif;

add_action( 'telegram_action_posts_navigation', 'telegram_posts_navigation' );



if( ! function_exists( 'telegram_excerpt_length' ) ) :

    /**
     * Excerpt length
     *
     * @since  telegram 1.0.0
     *
     * @param null
     * @return int
     */
    function telegram_excerpt_length( $length ){
        if ( is_admin() ) {
                return $length;
        }
        $excerpt_length = telegram_get_option('excerpt_length_global');
        if ( empty( $excerpt_length) ) {
            $excerpt_length = $length;
        }
        return absint( $excerpt_length );

    }

endif;
add_filter( 'excerpt_length', 'telegram_excerpt_length', 999 );


/**
 * Returns no image url.
 *
 * @since  Telegram_ 1.0.0
 */
if (!function_exists('telegram_post_format')):
    function telegram_post_format($post_id)
    {
        $post_format = get_post_format($post_id);
        switch ($post_format) {
            case "image":
                $post_format = "<span class='twp-post-format-icon'><i class='ion ion-ios-camera'></i></span>";
                break;
            case "video":
                $post_format = "<span class='twp-post-format-icon'><i class='ion ion-ios-videocam'></i></span>";
                break;
            case "gallery":
                $post_format = "<span class='twp-post-format-icon'><i class='ion ion-md-images'></i></span>";
                break;
            case "quote":
                $post_format = "<span class='twp-post-format-icon'><i class='ion ion-md-quote'></i></span>";
                break; 
           case "audio":
                $post_format = "<span class='twp-post-format-icon'><i class='ion ion-ios-musical-notes'></i></span>";
                break;
            default:
                $post_format = "";
        }

        echo $post_format;
    }

endif;


/* Display Breadcrumbs */
if (!function_exists('telegram_get_breadcrumb')) :

    /**
     * Simple breadcrumb.
     *
     * @since 1.0.0
     */
    function telegram_get_breadcrumb()
    {
        // Bail if Home Page.
        if (is_front_page() || is_home()) {
            return;
        }
        $breadcrumb_type = telegram_get_option( 'breadcrumb_type' );
        if ( 'disabled' === $breadcrumb_type ) {
            return;
        }

        if (!function_exists('breadcrumb_trail')) {

            /**
             * Load libraries.
             */

            require_once get_template_directory() . '/assets/resources/breadcrumb-trail/breadcrumb-trail.php';
        }

        $telegram_breadcrumb_args = array(
            'container' => 'div',
            'show_browse' => false,
        ); ?>


        <div class="twp-breadcrumbs">
            <div class="twp-container">
                <?php breadcrumb_trail($telegram_breadcrumb_args); ?>
            </div>
        </div>


    <?php }

endif;
add_action('telegram_action_get_breadcrumb', 'telegram_get_breadcrumb');



/*related post*/
if (!function_exists('telegram_get_related_posts')) :
    /*
     * Function to get related posts
     */
    function telegram_get_related_posts()
    {
        global $post;

        //$options = telegram_get_theme_options(); // get theme options

        $post_categories = get_the_category($post->ID); // get category object
        $category_ids = array(); // set an empty array
        $telegram_related_post_number = absint(telegram_get_option( 'number_of_single_related_post' ));
        foreach ($post_categories as $post_category) {
            $category_ids[] = $post_category->term_id;
        }

        if (empty($category_ids)) return;

        $qargs = array(
            'posts_per_page' => $telegram_related_post_number,
            'category__in' => $category_ids,
            'post__not_in' => array($post->ID),
            'order' => 'ASC',
            'orderby' => 'rand'
        );

        $related_posts = get_posts($qargs); // custom posts
        ?>
        <div class="twp-related-post-section">
            <div class="twp-container">
                <div class="twp-wrapper">
                    <h2 class="twp-section-title">
                        <?php echo esc_html(telegram_get_option( 'single_related_post_title' )); ?>
                    </h2>
                    <div class="twp-post-list">
                        <div class="twp-row">
                            <?php foreach ($related_posts as $related_post) {
                                $post_title = get_the_title($related_post->ID);
                                $post_url = get_permalink($related_post->ID);
                                $post_date = get_the_date('', $related_post->ID);
                                ?>
                                <div class="twp-col twp-col-xs-6 twp-col-md-4 twp-col-lg-3">
                                    <div class="twp-related-post twp-post twp-post-style-4 twp-post-with-border">
                                        <?php if (has_post_thumbnail($related_post->ID)) {
                                            $img_array = wp_get_attachment_image_src(get_post_thumbnail_id($related_post->ID), 'medium'); ?>
                                            <div class="twp-image-section twp-image-with-content twp-overlay twp-image-250  twp-image-hover-effect twp-image-zoom-in-effect">
                                                <a href="<?php echo esc_url($post_url); ?>"></a>
                                                <div class="twp-image bg-image" style="background-image:url('<?php echo esc_url($img_array[0]); ?>')"></div>
                                                <div class="twp-post-format-wrapper twp-d-flex">
                                                    <div class="twp-post-format twp-post-format-with-bg">
                                                        <?php echo esc_attr(telegram_post_format(get_the_ID())); ?>
                                                    </div>
                                                    <?php telegram_post_read_time(); ?>
    
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="twp-desc">
                                            <div class="twp-category twp-category-with-multi-color">
                                                <?php telegram_posted_category(); ?>
                                            </div>
                                            <div class="twp-meta-tag twp-d-flex">
                                                <?php
                                                    telegram_posted_by();
                                                    telegram_posted_on();
                                                ?>
                                            </div>
                                            <h3 class="twp-post-title twp-post-title-xs">
                                                <a href="<?php echo esc_url($post_url); ?>"><?php echo wp_kses_post($post_title); ?></a>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div><!--/twp-post-list-->
                </div><!--/twp-wrapper-->
            </div><!--/container-->
        </div><!--twp-related-post--section-->
        <?php
    }
endif;
add_action('telegram_related_posts', 'telegram_get_related_posts');

