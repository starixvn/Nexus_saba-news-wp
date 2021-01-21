<?php
if (!function_exists('telegram_list_post_with_args')) :
    /**
     * Banner grid Details
     *
     * @since Telegram 1.0.0
     *
     * @return array $qargs grid details.
     */
    function telegram_list_post_with_args()
    {
        $telegram_list_post_number = absint(telegram_get_option('number_of_list_posts'));
        $telegram_list_post_category = esc_attr(telegram_get_option('select_category_for_list_post_section'));
        $qargs = array(
            'posts_per_page' => esc_attr($telegram_list_post_number),
            'post_type' => 'post',
            'cat' => $telegram_list_post_category,
        );
        return $qargs;
        ?>
        <?php
    }
endif;

if (!function_exists('telegram_list_post_with_add')) :
    /**
     * List Post With Add Section
     *
     * @since telegram 1.0.0
     *
     */
    function telegram_list_post_with_add()
    {
        if (1 != telegram_get_option('show_list_section_with_add')) {
            return null;
        }
        $telegram_list_post_with_add_title_text = esc_html(telegram_get_option('heading_text_on_list_post_add'));
        $telegram_list_post_with_args = telegram_list_post_with_args();
        $telegram_list_post_with_add_query = new WP_Query($telegram_list_post_with_args); ?>
        <div class="twp-advertisement-post-section">
            <div class="twp-container">
                <?php if (!empty($telegram_list_post_with_add_title_text)) { ?>
                        <h2 class="twp-section-title">
                            <?php echo esc_html($telegram_list_post_with_add_title_text); ?>
                        </h2>
                <?php } ?>
                <div class="twp-row">
                    <div class="twp-col twp-col-lg-9 banner-sticky-scroll">
                        <div class="twp-post-list">
                            <?php
                            if ($telegram_list_post_with_add_query->have_posts()) :
                                while ($telegram_list_post_with_add_query->have_posts()) : $telegram_list_post_with_add_query->the_post();
                                    if (has_post_thumbnail()) {
                                        $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'medium_large');
                                        $url = $thumb['0'];
                                    }
                                    ?>
            
                                    <div class="twp-post twp-post-style-5 twp-d-flex">
                                        <div class="twp-image-section twp-image-with-content twp-image-hover-effect twp-image-zoom-in-effect twp-image-350 ">
                                            <a href="<?php the_permalink(); ?>"></a>
                                            <div class="twp-image bg-image" style="background-image:url('<?php echo esc_url($url); ?>')">
                                            </div>
                                            <div class="twp-post-format-wrapper twp-d-flex">
                                                <div class="twp-post-format twp-post-format-with-bg">
                                                    <?php echo esc_attr(telegram_post_format(get_the_ID())); ?>
                                                </div>
                                                <?php telegram_post_read_time(); ?>
                                            </div>
                                        </div>
                                        <div class="twp-desc">
                                             <div class="twp-wrapper">
                                                <div class="twp-category twp-category-with-bg">
                                                    <?php telegram_posted_category(); ?>
                                                </div>
                                                <h3 class="twp-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                                <!-- Meta Tag -->
                                                <?php if ( 'post' === get_post_type() ) : ?>
                                                    <div class="twp-meta-tag entry-meta twp-d-flex twp-meta-tag-lg">
                                                        <?php
                                                        telegram_posted_on();
                                                        telegram_posted_by();
                                                        ?>
                                                    </div><!-- .entry-meta -->
                                                <?php endif; ?>
                                                <?php the_excerpt(); ?>
                                                <div class="twp-social-share-section twp-social-share-lg twp-social-share-rounded">
                                                    <?php telegram_social_share_option(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            endif; ?>
                        </div>
                    </div>
                    <div class="twp-col twp-col-lg-3 banner-sticky-scroll">
                        <?php if (telegram_get_option('show_list_post_add_section') == 1) { ?>
                            <div class="twp-advertisement-section twp-center">
                                <a  href="<?php echo esc_url(telegram_get_option('list_post_advertisement_url')); ?>">
                                    <img src="<?php echo esc_url(telegram_get_option('list_post_section_advertisement')); ?>" alt="<?php echo esc_html("Ad  image"); ?>">
                                </a>
                            </div><!--/twp-advertisement-section-->
                        <?php } ?>
                    </div>
                </div>
               
            </div>
           

        </div>
        <?php
}  
endif;
add_action('telegram_action_list_post_with_add', 'telegram_list_post_with_add', 10);
