<?php
ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once '../wp-load.php'; // 載入 WordPress 核心
require_once '../vendor/autoload.php';

session_start();
$website_url = 'https://' . $_SERVER['HTTP_HOST'];
$client = new Google_Client();
$client->setClientId('926868985895-gv31tsp21j36k7stm3cp9t7pbc16pfbt.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri($website_url . '/bvg/auth_google.php');
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    $client->setAccessToken($token);

    // 檢查是否有錯誤
    if (isset($token['error']) && $token['error']) {
        echo 'Error logging in';
    } else {
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        
        /*
        // 將用戶信息存儲在陣列中
        $user_info = [
            'email' => $google_account_info->email,
            'name' => $google_account_info->name,
            'picture' => $google_account_info->picture
        ];
        */

        // 檢查電子郵件地址是否已存在於 WordPress 用戶中
        $user = get_user_by('email', $google_account_info->email);
        if (!$user) {
            // 如果用戶不存在，則使用 Google 提供的信息創建新用戶，包含usermeta: has_verified_email = 1
            $userdata = [
                'user_login' => $google_account_info->email,
                'user_email' => $google_account_info->email,
                'first_name' => $google_account_info->givenName,
                'last_name' => $google_account_info->familyName,
                'user_pass' => wp_generate_password(), // 生成隨機密碼
                'role' => 'subscriber',
            ];
    
            $user_id = wp_insert_user($userdata);
    
            if (is_wp_error($user_id)) {
                // 處理創建用戶過程中的錯誤
                die('Error creating user: ' . $user_id->get_error_message());
                update_user_meta( $user_id, 'has_verified_email', '1' );
                update_user_meta( $user_id, 'auth_google', '1' );
            } else {
                $user = get_user_by('id', $user_id);
                update_user_meta( $user_id, 'has_verified_email', '1' );
                update_user_meta( $user_id, 'auth_google', '1' );
            }
        }

        // 使用者已經存在，進行登入
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);
        $user_role = get_user_meta( $user->ID, "saba_role", true ); // 抓取用户角色
        $user_sports = get_user_meta( $user->ID, "sports", true ); // 抓取用户喜愛的運動

        if(!$user_sports) {
            header('Location: /social-sign-up/?provider=google');
        } else {
            if(isset($_SESSION['goto']) && $_SESSION['goto'] != '') {
                $goto = $_SESSION['goto'];
                // session 清除
                unset($_SESSION['goto']);
                header('Location: '.$goto);
            } else {
                // 重定向到首頁或其他頁面
                header('Location: /?login=success&by=google');
            }
            
        }

        
    }
} else {
    header('Location: /login');
}


