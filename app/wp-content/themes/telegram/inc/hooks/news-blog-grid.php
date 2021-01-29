<?php
if (!function_exists('telegram_grid_block_args')) :
    /**
     * Banner grid Details
     *
     * @since Telegram 1.0.0
     *
     * @return array $qargs grid details.
     */
    function telegram_grid_block_args()
    {
        $telegram_grid_block_number = absint(telegram_get_option('number_of_home_grid'));
        $telegram_grid_block_category = esc_attr(telegram_get_option('select_category_for_grid'));
        $qargs = array(
            'posts_per_page' => esc_attr($telegram_grid_block_number),
            'post_type' => 'post',
            'cat' => $telegram_grid_block_category,
        );
        return $qargs;
        ?>
        <?php
    }
endif;


if (!function_exists('telegram_grid_block')) :
    /**
     * Banner grid
     *
     * @since Telegram 1.0.0
     *
     */
    function telegram_grid_block()
    {
        $telegram_grid_block_title_text = esc_html(telegram_get_option('heading_text_on_grid'));

        if (1 != telegram_get_option('show_grid_section')) {
            return null;
        }
        $telegram_grid_block_args = telegram_grid_block_args();
        $telegram_grid_bg_image = esc_url(telegram_get_option('grid_section_background_image'));
        $telegram_grid_block_query = new WP_Query($telegram_grid_block_args); ?>

        <div class="twp-featured-post-section bg-image twp-overlay" style="background-image:url('<?php echo esc_url($telegram_grid_bg_image); ?>')">
            <div class="twp-container">
                <?php if (!empty($telegram_grid_block_title_text)) { ?>
                        <h2 class="twp-section-title">
                            <?php echo esc_html($telegram_grid_block_title_text); ?>
                        </h2>
                <?php } ?>
                <div class="twp-post-list twp-d-flex">
                    <?php
                    if ($telegram_grid_block_query->have_posts()) :
                        while ($telegram_grid_block_query->have_posts()) : $telegram_grid_block_query->the_post();
                            if (has_post_thumbnail()) {
                                $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'medium');
                                $url = $thumb['0'];
                            }
                            ?>
                            <div class="twp-post twp-post-style-4">
                                <div class="twp-image-section twp-image-with-content twp-image-hover-effect twp-image-zoom-in-effect twp-image-250">
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
                                    <div class="twp-meta-tag twp-d-flex">
                                        <?php
                                            telegram_posted_by();
                                            telegram_posted_on();
                                        ?>
                                        <div class="twp-social-share-section twp-social-share-with-hover-effect">
                                            <?php //telegram_social_share_option(); ?>
                                        </div>
                                    </div>
                                    <h3 class="twp-post-title twp-post-title-xs"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                </div>
                            </div><!--/twp-post-->
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    endif; ?>
                </div><!--/twp-post-list-->
            </div><!--/container-->
        </div><!--/twp-fetaured-post-section--> 
        <?php
    }
endif;
add_action('telegram_action_grid_post', 'telegram_grid_block', 10);