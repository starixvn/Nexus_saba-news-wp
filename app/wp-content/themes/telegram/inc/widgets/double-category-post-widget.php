<?php
/**
 * Theme widgets.
 *
 * @package Telegram
 */

// Load widget base.
if (!function_exists('telegram_load_double_category_post_widget')) :
    /**
     * Load widgets.
     *
     * @since 1.0.0
     */
    function telegram_load_double_category_post_widget()
    {
        // List Post widget.
        register_widget('TWP_Double_Category_Post_Widget');

    }
endif;
add_action('widgets_init', 'telegram_load_double_category_post_widget');

/*List Post widget*/
if (!class_exists('TWP_Double_Category_Post_Widget')) :

    /**
     * List Post widget Class.
     *
     * @since 1.0.0
     */
    class TWP_Double_Category_Post_Widget extends Telegram_Widget_Base
    {

        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $opts = array(
                'classname' => 'twp-home-widget-section twp-popular-widget-section',
                'description' => __('Build for fullwidth homepage widget area, one ad grid and other list', 'telegram'),
                'customize_selective_refresh' => true,
            );
            $fields = array(
                'list_post_title' => array(
                    'label' => __('List Post Title:', 'telegram'),
                    'type' => 'text',
                    'class' => 'widefat',
                ),
                'post_category_list' => array(
                    'label' => __('Select Category for List Section:', 'telegram'),
                    'type' => 'dropdown-taxonomies',
                    'show_option_all' => __('All Categories', 'telegram'),
                ),
                'post_number_list' => array(
                    'label' => __('Number of Posts on List Section:', 'telegram'),
                    'type' => 'number',
                    'default' => 6,
                    'css' => 'max-width:60px;',
                    'min' => 1,
                    'max' => 9,
                ),
                'title' => array(
                    'label' => __('Block Section Title:', 'telegram'),
                    'type' => 'text',
                    'class' => 'widefat',
                ),
                'post_category' => array(
                    'label' => __('Select Category for Block Section:', 'telegram'),
                    'type' => 'dropdown-taxonomies',
                    'show_option_all' => __('All Categories', 'telegram'),
                ),
                'post_number' => array(
                    'label' => __('Number of Posts on Block Section:', 'telegram'),
                    'type' => 'number',
                    'default' => 6,
                    'css' => 'max-width:60px;',
                    'min' => 1,
                    'max' => 9,
                ),
                'enable_post_meta' => array(
                    'label' => __('Enable Post category Meta', 'telegram'),
                    'type' => 'checkbox',
                    'default' => true,
                ),
                
            );

            parent::__construct('telegram-double-category-post-widgets', __('TG :- Full-Width Double Category Grid and List', 'telegram'), $opts, array(), $fields);
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


            $qargs_list = array(
                'posts_per_page' => esc_attr($params['post_number_list']),
            );
            if (absint($params['post_category_list']) > 0) {
                $qargs_list['cat'] = absint($params['post_category_list']);
            } 

            $qargs = array(
                'posts_per_page' => esc_attr($params['post_number']),
            );
            if (absint($params['post_category']) > 0) {
                $qargs['cat'] = absint($params['post_category']);
            }   

            if (absint($params['post_category']) > 0) {
                $cat_link =esc_url(get_category_link( $params['post_category'] ));
            } else {
                $cat_link ='';
            }
                
                $all_posts = new WP_Query($qargs);
                $qargs_list = new WP_Query($qargs_list);
                ?>
                <?php global $post; 
                ?>
                <div class="twp-row">
                    <div class="twp-col twp-col-lg-4">
                        <div class="twp-popular-post-list">
                            <h2 class="widget-title"><?php echo esc_html($params['list_post_title']); ?></h2>
                            <div class="twp-post-list">
                                <?php if ( $qargs_list->have_posts() ) : ?>            
                                        <?php while ( $qargs_list->have_posts() ) : $qargs_list->the_post(); ?>
                                            <?php if (has_post_thumbnail()) {
                                                $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'thumbnail' );
                                                $url = $thumb['0'];
                                                } else {
                                                    $url = '';
                                            }
                                            ?>
                                            <div class="twp-post twp-post-style-1">
                                                <div class="twp-image-section twp-image-80">
                                                    <a href="<?php the_permalink();?>"></a>
                                                    <div class="twp-image bg-image" style="background-image:url('<?php echo esc_url($url); ?>')">
                                                    </div>
                                                    <div class="twp-post-format twp-post-format-with-bg twp-post-format-sm twp-post-format-white-bg">
                                                        <?php echo esc_attr(telegram_post_format(get_the_ID())); ?>
                                                    </div>
                                                </div>
                                                <div class="twp-desc">
                                                    <h3 class="twp-post-title twp-post-title-xs"><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h3>
                                                    <div class="twp-meta-tag twp-d-flex">
                                                        <?php
                                                            telegram_posted_on();
                                                        ?>
                                                    </div>
                                                </div>
                                            </div> 
                                        <?php 
                                            endwhile;
                                            wp_reset_postdata(); 
                                        ?>
                            <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="twp-col twp-col-lg-8">
                        <div class="twp-grid-post-section">
                            <h2 class="widget-title"><?php echo esc_html($params['title']); ?></h2>
                            <?php if ( $all_posts->have_posts() ) : ?>  
                                <div class="twp-post-list">
                                    <div class="twp-row">          
                                        <?php while ( $all_posts->have_posts() ) : $all_posts->the_post(); ?>
                                            <?php if (has_post_thumbnail()) {
                                                $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium_large' );
                                                $url = $thumb['0'];
                                                } else {
                                                    $url = '';
                                                }
                                            ?>
                                            <div class="twp-col twp-col-xs-6 twp-col-md-4 twp-col-lg-6">
                                                <div class="twp-post twp-post-style-4 twp-post-with-box-shadow">
                                                    <div class="twp-image-section twp-image-with-content twp-image-hover-effect twp-image-zoom-in-effect twp-image-200">
                                                        <a href="<?php the_permalink();?>"></a>
                                                        <div class="twp-image bg-image" style="background-image:url('<?php  echo esc_url($url);?>')">
                                                        </div>
                                                        <div class="twp-post-format-wrapper twp-d-flex">
                                                            <div class="twp-post-format twp-post-format-with-bg">
                                                                <?php echo esc_attr(telegram_post_format(get_the_ID())); ?>
                                                            </div>
                                                            <?php telegram_post_read_time(); ?>

                                                        </div>
                                                    </div><!--/twp-image-section-->
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
                                                        <h3 class="twp-post-title twp-post-title-xs"><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h3>
                                                    </div>
                                                </div><!--/twp-post-->
                                            </div>
                                        <?php 
                                            endwhile;
                                            wp_reset_postdata(); 
                                        ?>
                                    </div><!--/row-->
                                </div><!--/twp-post-list-->
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <?php echo $args['after_widget'];
        }
    }
endif;
