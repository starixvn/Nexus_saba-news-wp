<?php
if (!function_exists('telegram_main_banner')) :
    /**
     * Main Banner Section
     *
     * @since telegram 1.0.0
     *
     * 
     * 
     */
    function telegram_main_banner()
    {
        if (1 != telegram_get_option('show_main_banner_section')) {
            return null;
        }
       
        ?>
        <div class="twp-banner-section">
            <div class="twp-container">
                <div class="twp-row">
                    <?php 
                    $telegram_select_category_for_banner_section = esc_attr(telegram_get_option('select_category_for_slider_section'));
                    $telegram_select_category_for_counter_list_section = esc_attr(telegram_get_option('select_category_for_counter_list_section'));
                    $telegram_number_of_home_banner_section = absint(telegram_get_option('number_of_home_slider'));
                    $telegram_main_banner_banner_args = array(
                        'post_type' => 'post',
                        'cat' => absint($telegram_select_category_for_banner_section),
                        'ignore_sticky_posts' => true,
                        'posts_per_page' => absint( $telegram_number_of_home_banner_section ),
                    ); 

                    $telegram_main_banner_banner_args_2 = array(
                        'post_type' => 'post',
                        'cat' => absint($telegram_select_category_for_counter_list_section),
                        'ignore_sticky_posts' => true,
                        'posts_per_page' => 5,
                    ); ?>
                    <div class="twp-col twp-col-xs-6 twp-col-lg-3 banner-order-1">
                        <div class="twp-exclusive-post-list">
                            <div class="twp-title-section twp-section-title-with-arrow twp-d-flex">
                                <h2 class="twp-section-title"><?php echo esc_html(telegram_get_option('telegram_recent_post_section_title')); ?></h2>
                                <div class="twp-arrow-section twp-d-flex">
                                    <span class="twp-arrow twp-exclusive-prev"><i class="ion ion-md-arrow-up"></i></span>
                                    <span class="twp-arrow twp-exclusive-next"><i class="ion ion-md-arrow-down"></i></span>
                                </div>
                            </div>
                            <div class="twp-exclusive-post-slider">
                                <?php 
                                $telegram_select_category_for_recent_section = esc_attr(telegram_get_option('select_category_for_recent_post'));
                                $telegram_number_of_home_recent_section = absint(telegram_get_option('number_of_home_recent_post'));
                                $telegram_main_banner_recent_args = array(
                                    'post_type' => 'post',
                                    'cat' => absint($telegram_select_category_for_recent_section),
                                    'ignore_sticky_posts' => true,
                                    'posts_per_page' => absint( $telegram_number_of_home_recent_section ),
                                ); ?>
                                <?php 
                                $telegram_main_banner_recent_post_query = new WP_Query($telegram_main_banner_recent_args);
                                if ($telegram_main_banner_recent_post_query->have_posts()) :
                                    while ($telegram_main_banner_recent_post_query->have_posts()) : $telegram_main_banner_recent_post_query->the_post();
                                        if(has_post_thumbnail()){
                                            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'thumbnail' );
                                            $url = $thumb['0'];
                                        }
                                        else{
                                            $url = '';
                                        }
                                        ?>
                                        <div class="twp-wrapper">
                                            <div class="twp-post twp-post-style-1">
                                                <div class="twp-image-section twp-image-70 twp-image-zoom-in-effect">
                                                    <a href="<?php the_permalink(); ?>"></a>
                                                    <div class="twp-image bg-image" style="background-image:url('<?php echo esc_url($url); ?>')">
                                                    </div>
                                                    <div class="twp-post-format-wrapper twp-d-flex">
                                                        <div class="twp-post-format twp-post-format-sm twp-post-format-with-bg">
                                                            <?php echo esc_attr(telegram_post_format(get_the_ID())); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="twp-desc">
                                                    <h3 class="twp-post-title twp-post-title-xs"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                                    <div class="twp-meta-tag twp-d-flex">
                                                        <?php
                                                            telegram_posted_on();
                                                        ?>
                                                    </div>
                                                </div>
        
                                                
                                            </div> 
                                        </div>
                                        
                                    <?php 
                                endwhile;
                                endif; 
                                wp_reset_postdata(); 
                                ?>
                            </div>

                        </div><!--/twp-exclusive-post-list-->
                    </div><!--/col-->
                    <div class="twp-col twp-col-lg-6 banner-order-2">
                        <div class="twp-banner-slider-section twp-banner-slider twp-slick-arrow">
                            <?php 
                            $telegram_main_banner_banner_post_query_args = new WP_Query($telegram_main_banner_banner_args);
                            if ($telegram_main_banner_banner_post_query_args->have_posts()) :
                            while ($telegram_main_banner_banner_post_query_args->have_posts()) : $telegram_main_banner_banner_post_query_args->the_post();
                                if(has_post_thumbnail()){
                                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium_large' );
                                    $url = $thumb['0'];
                                }
                                else{
                                    $url = '';
                                }
                                ?>
                                    <div class="twp-post twp-post-style-2">
                                        <div class="twp-image-section twp-image-with-content twp-image-zoom-in-effect  twp-overlay">
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
                                    </div>  
                            <?php endwhile;
                            endif; 
                            wp_reset_postdata(); ?>
                        </div>
                    </div>
                    <div class="twp-col twp-col-xs-6 twp-col-lg-3 banner-order-3">
                        <div class="twp-post-list-with-post-count">
                            <?php 
                            $count = 1;
                            $telegram_main_banner_banner_post_query = new WP_Query($telegram_main_banner_banner_args_2);
                            if ($telegram_main_banner_banner_post_query->have_posts()) :
                            while ($telegram_main_banner_banner_post_query->have_posts()) : $telegram_main_banner_banner_post_query->the_post();
                                if(has_post_thumbnail()){
                                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'thumbnail' );
                                    $url = $thumb['0'];
                                }
                                else{
                                    $url = '';
                                }
                                ?>
                                    <div class="twp-post twp-post-style-3">
                                        <div class="twp-image-section twp-image-zoom-in-effect">
                                            <a href="<?php the_permalink(); ?>"></a>
                                            <div class="twp-image bg-image" style="background-image:url('<?php echo esc_url($url); ?>')">
                                            </div>
                                        </div>
                                        <div class="twp-desc">
                                            <h3 class="twp-post-title twp-post-title-xs"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                            <?php //telegram_posted_on(); ?>
                                        </div>
                                      
                                    </div> 
                            <?php 
                                $count++;
                                endwhile;
                                endif; 
                                wp_reset_postdata(); ?>
                        </div>
                    </div>
                </div>
            </div>
        
        </div><!--/twp-banner-section-->
        <?php
    }   
endif;
add_action('telegram_action_main_banner', 'telegram_main_banner', 10);