<?php
/**
 * Theme widgets.
 *
 * @package Telegram
 */

// Load widget base.
if (!function_exists('telegram_load_list_post_widget')) :
    /**
     * Load widgets.
     *
     * @since 1.0.0
     */
    function telegram_load_list_post_widget()
    {
        // List Post widget.
        register_widget('TWP_List_Post_Widget');

    }
endif;
add_action('widgets_init', 'telegram_load_list_post_widget');

/*List Post widget*/
if (!class_exists('TWP_List_Post_Widget')) :

    /**
     * List Post widget Class.
     *
     * @since 1.0.0
     */
    class TWP_List_Post_Widget extends Telegram_Widget_Base
    {

        /**
         * Sets up a new widget instance.
         *
         * @since 1.0.0
         */
        function __construct()
        {
            $opts = array(
                'classname' => 'twp-home-widget-section twp-list-widget-section',
                'description' => __('Displays post form selected category specific for List post in sidebars.', 'telegram'),
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
                'enable_counter' => array(
                    'label' => __('Enable Counter:', 'telegram'),
                    'type' => 'checkbox',
                    'default' => true,
                ),
                'post_number' => array(
                    'label' => __('Number of Posts:', 'telegram'),
                    'type' => 'number',
                    'default' => 4,
                    'css' => 'max-width:60px;',
                    'min' => 1,
                    'max' => 9,
                ),
            );

            parent::__construct('telegram-list-post-sidebar-layout', __('TG :- List Post Widget', 'telegram'), $opts, array(), $fields);
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
            $all_posts = new WP_Query($qargs);
            ?>
            <?php global $post; 
            $count = 1;
            ?>
            <?php if (!empty($all_posts)) : ?>

                <?php if (!empty($params['title'])) { ?>
                    <h3 class="widget-title"><?php echo esc_html($params['title']); ?></h3>
                <?php } ?>
                <div class="twp-post-list">
                    <div class="twp-row">
                        <?php if ( $all_posts->have_posts() ) : ?>            
                            <?php while ( $all_posts->have_posts() ) : $all_posts->the_post(); ?>
                                <div class="twp-col twp-col-xs-6 twp-col-lg-4">
                                    <div class="twp-post twp-post-style-1">
                                        <div class="twp-image-section twp-image-80 twp-image-zoom-in-effect">
                                            <?php if (has_post_thumbnail()) {
                                                $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'thumbnail' );
                                                $url = $thumb['0'];
                                                } else {
                                                    $url = '';
                                            }
                                            ?>
                                            <a href="<?php the_permalink(); ?>"></a>
                                            <div class="twp-image bg-image" style="background-image:url('<?php echo esc_url($url); ?>')">
                                                
                                            </div>
                                            <div class="twp-post-format-wrapper">
                                                <div class="twp-post-format twp-post-format-sm twp-post-format-with-bg">
                                                    <?php echo esc_attr(telegram_post_format(get_the_ID())); ?>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="twp-desc">
                                            <h3 class="twp-post-title twp-post-title-xs"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h3>
                                            <div class="twp-meta-tag twp-d-flex">
                                                <?php
                                                    telegram_posted_on();
                                                ?>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                        <?php 
                            $count++;
                            endwhile;
                            wp_reset_postdata(); 
                        ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php wp_reset_postdata(); ?>

        <?php endif; ?>
            <?php echo $args['after_widget'];
        }
    }
endif;
