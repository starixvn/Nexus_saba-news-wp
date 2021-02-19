<?php
if (isset($comment)) {
    $comment_id = $comment->comment_ID;
}
$like_count = get_comment_meta($comment_id, 'cld_like_count', true);
$dislike_count = get_comment_meta($comment_id, 'cld_dislike_count', true);

$cld_settings = get_option('cld_settings');
if (!empty($cld_settings['basic_settings']['display_zero'])) {
    $like_count = (empty($like_count)) ? 0 : $like_count;
    $dislike_count = (empty($dislike_count)) ? 0 : $dislike_count;
}
/**
 * Filters like count
 *
 * @param type int $like_count
 * @param type int $comment_id
 *
 * @since 1.0.0
 */
$like_count = apply_filters('cld_like_count', $like_count, $comment_id);

/**
 * Filters dislike count
 *
 * @param type int $dislike_count
 * @param type int $comment_id
 *
 * @since 1.0.0
 */
$dislike_count = apply_filters('cld_dislike_count', $dislike_count, $comment_id);
if ($cld_settings['basic_settings']['status'] != 1) {
    // if comments like dislike is disabled from backend
    return;
}
$liked_ips = get_comment_meta($comment_id, 'cld_ips', true);
global $cld_library;
$user_ip = $cld_library->get_user_IP();
if (empty($liked_ips)) {
    $liked_ips = array();
}
if (is_user_logged_in()) {
    $liked_users = get_comment_meta($comment_id, 'cld_users', true);
    $liked_users = (empty($liked_users)) ? array() : $liked_users;
    $current_user_id = get_current_user_id();
    if (in_array($current_user_id, $liked_users)) {
        $user_check = 1;
        $already_liked = 1;
    } else {
        $user_check = 0;
    }
} else {
    $user_check = 1;
    $already_liked = 0;
}

if ($cld_settings['basic_settings']['like_dislike_resistriction'] == 'user' && !empty($cld_settings['basic_settings']['login_link']) && $user_check == 1 && $already_liked == 0) {
    $href = $cld_settings['basic_settings']['login_link'];
} else {
    $href = 'javascript:void(0)';
}


$user_ip_check = (in_array($user_ip, $liked_ips)) ? 1 : 0;
$like_title = isset($cld_settings['basic_settings']['like_hover_text']) ? esc_attr($cld_settings['basic_settings']['like_hover_text']) : __('Like', CLD_TD);
$dislike_title = isset($cld_settings['basic_settings']['dislike_hover_text']) ? esc_attr($cld_settings['basic_settings']['dislike_hover_text']) : __('Dislike', CLD_TD);
?>
<div class="cld-like-dislike-wrap cld-<?php echo esc_attr($cld_settings['design_settings']['template']); ?>">
    <?php
    /**
     * Like Dislike Order
     */
    if ($cld_settings['basic_settings']['display_order'] == 'like-dislike') {
        if ($cld_settings['basic_settings']['like_dislike_display'] != 'dislike_only') {
            include(CLD_PATH . 'inc/views/frontend/like.php');
        }
        if ($cld_settings['basic_settings']['like_dislike_display'] != 'like_only') {
            include(CLD_PATH . 'inc/views/frontend/dislike.php');
        }
    } else {
        /**
         * Dislike Like Order
         */
        if ($cld_settings['basic_settings']['like_dislike_display'] != 'like_only') {
            include(CLD_PATH . 'inc/views/frontend/dislike.php');
        }
        if ($cld_settings['basic_settings']['like_dislike_display'] != 'dislike_only') {
            include(CLD_PATH . 'inc/views/frontend/like.php');
        }
    }
    ?>
</div>
