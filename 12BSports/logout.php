<?php
// 加載 WordPress 核心
require_once( dirname( __FILE__ ) . '/wp-load.php' );

// 檢查用戶是否已登入
if ( is_user_logged_in() ) {
    wp_logout(); // 執行登出操作
    $redirect_url = home_url( '/login' ); // 設定登出後重定向的 URL
    wp_safe_redirect( $redirect_url ); // 安全重定向到登出後的頁面
    exit;
} else {
    // 如果用戶未登入，直接重定向到登入頁面
    wp_safe_redirect( home_url( '/login' ) );
    exit;
}
