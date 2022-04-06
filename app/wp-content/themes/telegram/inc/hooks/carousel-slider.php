<?php
if (!function_exists('telegram_action_carousel_slider')) :
    /**
     * Carousel Slider Section
     *
     * @since Telegram 1.0.0
     *
     */
    function telegram_action_carousel_slider()
    {
        if (1 != telegram_get_option('show_carousel_slider_section')) {
            return null;
        }
       
        ?>
        <div class="twp-carousel-section">
            <div class="twp-carousel-slider twp-slick-arrow">
                <?php 
                $select_category_on_carousel = esc_attr(telegram_get_option('select_category_on_carousel'));
                $number_of_post_on_carousle_slider = absint(telegram_get_option('number_of_post_on_carousle_slider'));
                $telegram_action_carousel_slider_args = array(
                    'post_type' => 'post',
                    'cat' => absint($select_category_on_carousel),
                    'ignore_sticky_posts' => true,
                    'posts_per_page' => absint($number_of_post_on_carousle_slider),
                ); ?>
                <?php 
                $telegram_action_carousel_slider_post_query = new WP_Query($telegram_action_carousel_slider_args);
                if ($telegram_action_carousel_slider_post_query->have_posts()) :
                while ($telegram_action_carousel_slider_post_query->have_posts()) : $telegram_action_carousel_slider_post_query->the_post();
                    if(has_post_thumbnail()){
                        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium_large' );
                        $url = $thumb['0'];
                    }
                    else{
                        $url = '';
                    }
                    ?>
                        <div class="twp-wrapper">
                            <div class="twp-post twp-post-style-2">
                                <div class="twp-image-section twp-image-with-content twp-image-zoom-in-effect twp-overlay twp-image-420">
                                    <a href="<?php the_permalink(); ?>"></a>
                                    <div class="twp-image bg-image" style="background-image:url('<?php echo esc_url($url); ?>')"></div>
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
                                        <div class="twp-meta-tag twp-d-flex">
                                            <?php
                                                telegram_posted_by();
                                                telegram_posted_on();
                                            ?>
                                            
                                        </div>
                                        <h3 class="twp-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php endwhile;
                    endif; 
                wp_reset_postdata(); ?>
            </div>
        </div><!--/twp-carousel-section-->
    <?php }   
endif;
add_action('telegram_action_carousel_slider_section', 'telegram_action_carousel_slider', 10);
