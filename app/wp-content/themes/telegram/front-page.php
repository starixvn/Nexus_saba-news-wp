<?php
/**
 * The template for displaying home page.
 * @package Telegram
 */

get_header();?>
<?php if ( is_paged()) { ?>
<?php } else {
            do_action( 'telegram_action_main_banner' );
            do_action( 'telegram_action_grid_post' );
            do_action( 'telegram_action_list_post_with_add' ); 
            do_action( 'telegram_action_carousel_slider_section' );
            ?>

            <?php if(is_active_sidebar('home-front-page-primary-widget-area') || is_active_sidebar('home-front-page-secondary-widget-area') ){ ?>
                <div class="twp-home-widget-area-section">
                    <div class="twp-container">
                        <div class="twp-row">
                            <?php 
                                if(is_active_sidebar('home-front-page-primary-widget-area') && is_active_sidebar('home-front-page-secondary-widget-area') ){
                                    $widget_content_class ="twp-col twp-col-lg-9";
                                    $sidebar_class ="twp-col twp-col-lg-3";
                                }else{
                                    $widget_content_class ="twp-col";
                                    $sidebar_class ="twp-col";
                                }

                            ?>
                            <div class="<?php echo $widget_content_class; ?>">
                                <?php if (is_active_sidebar('home-front-page-primary-widget-area')) { ?>
                                    <div class="twp-home-page-primary-widget-area widget-sticky-scroll">
                                        <?php dynamic_sidebar('home-front-page-primary-widget-area'); ?>
                                    </div>
                                <?php }?>
                            </div>
                            <div class="<?php echo $sidebar_class; ?>">
                                <?php if (is_active_sidebar('home-front-page-secondary-widget-area')) { ?>
                                    <div class="twp-home-page-secondary-widget-area widget-sticky-scroll widget-area">
                                        <?php dynamic_sidebar('home-front-page-secondary-widget-area'); ?>
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
<?php } ?>

        <?php if ('posts' == get_option('show_on_front')) { ?>
            <?php if (1 == telegram_get_option('show_latest_post_content_on_homepage')) { ?>
            <div class="twp-home-page-latest-post">
                <div class="twp-container">
                    <div class="twp-d-flex">
                        <div id="primary" class="content-area">
                            <main id="main" class="site-main">
                                <div class="<?php echo esc_attr(telegram_get_option( 'select_global_archive_layout' )); ?> twp-archive-post-list">
                                    <?php
                                    if ( have_posts() ) :

                                        if ( is_home() && ! is_front_page() ) :
                                            ?>
                                            <header>
                                                <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                                            </header>
                                            <?php
                                        endif;

                                        /* Start the Loop */
                                        while ( have_posts() ) :
                                            the_post();

                                            /*
                                            * Include the Post-Type-specific template for the content.
                                            * If you want to override this in a child theme, then include a file
                                            * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                                            */
                                            get_template_part( 'template-parts/content', get_post_type() );

                                        endwhile;
                                        do_action('telegram_action_posts_navigation');
                                    else :

                                        get_template_part( 'template-parts/content', 'none' );

                                    endif;
                                    ?>
                                </div>
                            </main><!-- #main -->
                        </div><!-- #primary -->
                        <?php get_sidebar();?>
                    </div>
                </div>
            </div>
            <?php } ?>
        <?php } else { ?>
            <?php if (1 == telegram_get_option('show_selected_page_content_on_homepage')) { ?>
                <div class="twp-home-static-page" id="content-container">
                    <div class="twp-container">
                        <div class="twp-d-flex">
                            <div id="primary" class="content-area">
                                <?php
                                while (have_posts()) : the_post();
                                    get_template_part('template-parts/content', 'page');
    
                                endwhile; // End of the loop.
                                ?>
                            </div><!-- #primary -->
                            <?php get_sidebar(); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
                
    <?php } ?>

<?php get_footer();