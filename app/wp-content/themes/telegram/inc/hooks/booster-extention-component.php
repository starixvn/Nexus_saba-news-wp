<?php
/**
* The modified features form plugin booster extention
*
*/
if( !function_exists( 'telegram_social_share_option' ) ):

    function telegram_social_share_option(){

        if(class_exists( 'Booster_Extension_Class' ) ): ?>
            <div>
                <?php if( class_exists( 'Booster_Extension_Class') ){
                    $args = array('layout'=>'layout-2','status' => 'enable');
                    do_action('booster_extension_social_icons',$args);
                } ?>
            </div>
        <?php
        endif;
    }
endif;


if( !function_exists( 'telegram_post_view_count' ) ):

    function telegram_post_view_count(){

        if(class_exists( 'Booster_Extension_Class' ) ): ?>
            <div>
                <span class="entry-meta-icon views-icon">
                    <!-- icon -->
                </span>
                <span class="post-view-count">
                    <?php
                    $args = array('count_only'=>'count','label' => ''.esc_html__('Views','telegram').'');?>
                   <?php do_action('booster_extension_post_view_action',$args); ?>
                </span>
            </div>
        <?php
        endif;
    }
endif;

if( !function_exists( 'telegram_post_read_time' ) ):

    function telegram_post_read_time(){

        if( class_exists( 'Booster_Extension_Class' )): ?>
            <div class="twp-post-read-time">
                <?php do_action('booster_extension_read_time'); ?>
            </div>

        <?php
        endif;
    }
endif;

if( !function_exists( 'telegram_post_like_dislike' ) ):

    function telegram_post_like_dislike(){

        if( class_exists( 'Booster_Extension_Class' )): ?>

            <div class="entry-meta-item entry-meta-like-dislike">
                <div class="entry-meta-wrapper">
                    <?php do_action('booster_extension_like_dislike'); ?>
                </div>
            </div>
        
        <?php
        endif;
    }
endif;


if( !function_exists('telegram_disable_post_views') ):

    /** Disable Post Views **/
    function telegram_disable_post_views() {

        add_filter('booster_extension_filter_views_ed', function ( ) {
            return false;
        });

    }

endif;

if( !function_exists('telegram_disable_post_read_time') ):

    /** Disable Read Time **/
    function telegram_disable_post_read_time() {

        add_filter('booster_extension_filter_readtime_ed', function ( ) {
            return false;
        });

    }

endif;

if( !function_exists('telegram_disable_post_like_dislike') ):

    /** Disable Like Dislike **/
    function telegram_disable_post_like_dislike() {

        add_filter('booster_extension_filter_like_ed', function ( ) {
            return false;
        });

    }

endif;