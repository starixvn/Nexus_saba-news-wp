<?php
/**
 * Theme widgets.
 *
 * @package Telegram
 */

// Load widget base.
if (!function_exists('telegram_load_featured_post_with_list_widget')) :
    /**
     * Load widgets.
     *
     * @since 1.0.0
     */
    function telegram_load_featured_post_with_list_widget()
    {
        // List Post widget.
        register_widget('TWP_Featured_With_List_Post_Widget');

    }
endif;
add_action('widgets_init', 'telegram_load_featured_post_with_list_widget');

/*List Post widget*/
if (!class_exists('TWP_Featured_With_List_Post_Widget')) :

    /**
     * List Post widget Class.
     *
     * @since 1.0.0
     */
    class TWP_Featured_With_List_Post_Widget extends Telegram_Widget_Base
    {

        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $opts = array(
                'classname' => 'twp-home-widget-section twp-featured-widget-section',
                'description' => __('Build for fullwidth homepage widget area, one featured and other list', 'telegram'),
                'customize_selective_refresh' => true,
            );
            $fields = array(
                'title' => array(
                    'label' => __('Title:', 'telegram'),
                    'type' => 'text',
                    'class' => 'widefat',
                ),
                'post_category' => array(
                    'label' => __('Select Category:', 'telegram'),
                    'type' => 'dropdown-taxonomies',
                    'show_option_all' => __('All Categories', 'telegram'),
                ),
                'post_number' => array(
                    'label' => __('Number of Posts:', 'telegram'),
                    'type' => 'number',
                    'default' => 6,
                    'css' => 'max-width:60px;',
                    'min' => 1,
                    'max' => 9,
                ),
                'enable_post_meta' => array(
                    'label' => __('Enable Post Meta:', 'telegram'),
                    'type' => 'checkbox',
                    'default' => true,
                ),
            );

            parent::__construct('telegram-featured-list-post-widgets', __('TG :- Full-Width Featured Post With List', 'telegram'), $opts, array(), $fields);
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

            echo $args['before_widget'];
            $qargs = array(
                'posts_per_page' => esc_attr($params['post_number']),
                'no_found_rows' => true,
            );
            if (absint($params['post_category']) > 0) {
                $qargs['category'] = absint($params['post_category']);
            }
            if (absint($params['post_category']) > 0) {
                $cat_link =esc_url(get_category_link( $params['post_category'] ));
            } else {
                $cat_link ='';
            }
                if (!empty($params['title'])) {
                    echo $args['before_title'] .'<a href="'.$cat_link.'">' .$params['title'] .'</a>' .$args['after_title'];
                }
                $all_posts = new WP_Query($qargs);

                ?>
                <?php global $post; 
                $count = 1;
                ?>
                <?php if (!empty($all_posts)) : ?>
                    <div class="twp-d-flex">                
                        <?php if ( $all_posts->have_posts() ) : ?>            
                            <?php while ( $all_posts->have_posts() ) : $all_posts->the_post(); ?>
                                <?php if ($count == 1) { ?>
                                    <?php if (has_post_thumbnail()) {
                                        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium_large' );
                                        $url = $thumb['0'];
                                        } else {
                                            $url = '';
                                    }
                                    ?>
                                    <div class="twp-feature-single-post twp-post twp-post-style-2 twp-col twp-col-md-6 twp-no-gap">
                                        <div class="twp-image-section twp-image-with-content twp-image-zoom-in-effect twp-overlay twp-image-420--">
                                            <a href="<?php the_permalink(); ?>"></a>
                                            <div class="twp-image bg-image" style="background-image:url('<?php echo esc_url($url); ?>')">
                                            </div>
                                            <div class="twp-category twp-category-with-bg">
                                                <?php telegram_posted_category(); ?>
                                            </div>
                                            <div class="twp-desc">
                                                <div class="twp-post-format-wrapper twp-d-flex">
                                                    <div class="twp-post-format twp-post-format-with-bg">
                                                        <?php echo esc_attr(telegram_post_format(get_the_ID())); ?>
                                                    </div>
                                                    <?php telegram_post_read_time(); ?>

                                                </div>
                                                <h3 class="twp-post-title twp-post-title-lg"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                                <div class="twp-meta-tag twp-d-flex">
                                                    <?php
                                                        telegram_posted_by();
                                                        telegram_posted_on();
                                                    ?>
                                                    <div class="twp-social-share-section twp-social-share-with-hover-effect">
                                                        <?php telegram_social_share_option(); ?>
                                                    </div>
                                                </div>
                                                <?php the_excerpt(); ?>
                                            </div>
                                        </div>
                                    </div><!--/twp-post-->
                                <?php } else { ?>
                                    <?php if ($count <= 2) {
                                        echo '<div class="twp-post-list twp-col twp-col-md-6 twp-no-gap">';
                                    } ?>

                                    <div class="twp-post twp-post-style-1">
                                        <div class="twp-image-section twp-image-70 twp-image-zoom-in-effect">
                                            <?php if (has_post_thumbnail()) {
                                                $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'thumbnail' );
                                                $url = $thumb['0'];
                                                } else {
                                                    $url = '';
                                            }
                                            ?>
                                            <a  href="<?php the_permalink(); ?>"></a>
                                            <div class="twp-image bg-image" style="background-image:url('<?php echo esc_url($url); ?>')"></div>
                                            <div class="twp-post-format twp-post-format-sm twp-post-format-with-bg">
                                                <?php echo esc_attr(telegram_post_format(get_the_ID())); ?>
                                            </div>
                                        </div>
                                        <div class="twp-desc">
                                            <?php if (true === $params['enable_post_meta']) { ?>
                                                <div class="twp-d-flex twp-meta-wrapper">
                                                    <div class="twp-category twp-category-with-multi-color">
                                                        <?php telegram_posted_category(); ?>
                                                    </div>
                                                    <div class="twp-meta-tag">
                                                        <?php telegram_posted_on(); ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <h3 class="twp-post-title twp-post-title-xs"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        
                                        </div>
                                    </div>
                                    <?php if( $count == ( count( $all_posts->posts ))) {
                                        echo '</div>';
                                    }?>
                                <?php } ?>
                            <?php 
                                $count++;
                                endwhile;
                                wp_reset_postdata(); 
                            ?>
                            <?php endif; ?>
                        </div>

                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
                <?php echo $args['after_widget'];
        }
    }
endif;
