<?php
/*
 * Template Name: User Verification
 */
include_once("wp-includes/class-phpass.php");
define('WP_USE_THEMES', false);
require_once 'wp-load.php';


//get_header();
echo '<div style="text-align:center; margin-top: 100px;">';
$verification_code = isset($_GET['code']) ? $_GET['code'] : '';

if (!empty($verification_code)) {
    // 查詢匹配的用戶
    $users = get_users(array(
        'meta_key' => 'verification_code',
        'meta_value' => $verification_code,
        'number' => 1,
        'count_total' => false
    ));

    if (!empty($users)) {
        $user = $users[0];
        // 啟用帳號，這裡示例是將 'has_verified_email' 設置為 true
        update_user_meta($user->ID, 'has_verified_email', true);
        // 可選：刪除驗證碼
        delete_user_meta($user->ID, 'verification_code');

        echo '<h4>Your account has been successfully activated!</h4>';
        echo '<p><a href="https://www.thesabasportsindo.com">You will automatically go to the home page in 5 seconds.</a></p>';
        // 5秒後自動跳轉到會員中心
        echo '<script>setTimeout(function(){location.href="https://www.thesabasportsindo.com";},5000);</script>';


    } else {
        echo '<p>Invalid enable link.</p>';
    }
} else {
    echo '<p>The enable link is invalid or has expired.</p>';
}

echo '</div>';

//get_footer();
