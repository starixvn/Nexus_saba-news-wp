<?php
header('Content-Type: application/json; charset=utf-8');

include_once("../saba/Connections/db_pdo.php"); 
include_once("../wp-includes/class-phpass.php");
define('WP_USE_THEMES', false);
require_once '../wp-load.php';

include_once('config.php');

//date_default_timezone_set('Asia/Taipei');



function uuid($prefix = '')

  {

    $chars = md5(uniqid(mt_rand(), true));

    $uuid  = substr($chars,0,8) . '-';

    $uuid .= substr($chars,8,4) . '-';

    $uuid .= substr($chars,12,4) . '-';

    $uuid .= substr($chars,16,4) . '-';

    $uuid .= substr($chars,20,12);

    return $prefix . $uuid;

  }


$info = array(); 

/*---------- 1. 註冊會員 ------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "register")) {
    // 獲取POST數據
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $email = strtolower(str_replace(' ', '', $email)); // 移除email中的空格並轉換為小寫
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $sports = isset($_POST['sports']) ? $_POST['sports'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';

    $recaptcha_response = isset($_POST['recaptcha_response']) ? $_POST['recaptcha_response'] : '';

    // reCAPTCHA 驗證
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secret_key . "&response=" . $recaptcha_response);
    $response_data = json_decode($response);
    if (!$response_data->success) {
        $info['status'] = 'error';
        $info['message'] = 'Verifikasi reCAPTCHA gagal, coba lagi.';
        $info['response'] = $response_data;
        print_r(json_encode($info));
        exit;
    }

    if (empty($email) || empty($password)) {
        $info['status'] = 'error';
        $info['message'] = 'Email dan Kata Sandi diperlukan.';
    } else {
        $userdata = array(
            'user_login' => $email,
            'user_pass' => $password,
            'user_email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'role' => 'subscriber', // 或其他角色
            'meta_input' => array(
                'phone' => $phone,
                'sports' => $sports,
                'gender' => $gender
            )
        );

        $user_id = wp_insert_user($userdata);

        if (is_wp_error($user_id)) {
            $info['status'] = 'error';
            $info['message'] = $user_id->get_error_message();
        } else {

            update_user_meta($user_id, 'has_verified_email', false); // 添加一個用戶元數據標記郵件未驗證
            // 添加一個用戶元數據作為驗證碼
            $verification_code = md5($email . time());
            update_user_meta($user_id, 'verification_code', $verification_code);
            wp_new_user_notification($user_id, null, 'user'); // 第三個參數為'user'發送給用戶, 'admin'發送給管理員, 'both'都發送

            $info['status'] = 'success';
            $info['message'] = 'Pendaftaran berhasil, silakan periksa email Anda untuk menyelesaikan verifikasi.';
            $info['user_id'] = $user_id;

        }
    }
}

/*---------- 2. 忘記密碼 ------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "forgetpassword")) {
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    $recaptcha_response = isset($_POST['recaptcha_response']) ? $_POST['recaptcha_response'] : '';

    // reCAPTCHA 驗證
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secret_key . "&response=" . $recaptcha_response);
    $response_data = json_decode($response);
    if (!$response_data->success) {
        $info['status'] = 'error';
        $info['message'] = 'Verifikasi reCAPTCHA gagal, coba lagi.';
        print_r(json_encode($info));
        exit;
    }

    if (empty($email)) {
        $info['status'] = 'error';
        $info['message'] = 'no_email';
    } else {
        $user = get_user_by('email', $email);
        if (!$user) {
            $info['status'] = 'error';
            $info['message'] = 'no_user';
        } else {
            // 生成驗證碼
            $verification_code = uuid();
            
            $post_data = array(
                'id' => $verification_code,
                'user_id' => $user->ID,
                'dateandtime' => date('Y-m-d H:i:s')
            );
            $website_db->insert('wp_bvg_reset_password', $post_data);

            // 發送包含驗證碼的郵件給用戶
            $reset_link = $website_url . "/forget-password/?key=" . $verification_code;
            $message = "Kode verifikasi pengaturan ulang kata sandi Anda adalah: $verification_code \n\nSilakan klik tautan di bawah untuk mengatur ulang kata sandi Anda:\n$reset_link\n\nJika ini bukan operasi Anda, harap abaikan email ini.";
            // 確保使用正確的郵件發送函數和郵件標題
            wp_mail($email, 'Mereset password Anda', $message);

            $info['status'] = 'success';
            $info['message'] = 'done';
        }
    }
}


/*---------- 3. 取得用戶自己的資料 ------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "get_my_user_info")) {
    // 獲取當前用戶 id
    $user_id = get_current_user_id();

    if (empty($user_id)) {
        $info['status'] = 'error';
        $info['message'] = 'Tidak ditandai'; // 尚未登入
    } else {
        $user = get_user_by('id', $user_id);
        if (!$user) {
            $info['status'] = 'error';
            $info['message'] = 'Pengguna tidak dapat ditemukan.'; // 找不到該用戶
        } else {
            $logo = get_user_meta($user->ID, 'club_logo', true);
            // 如果用戶沒有上傳俱樂部 Logo，則使用預設的
            if (empty($logo)) {
                $logo = 'null.png';
            }
            $info['status'] = 'success';
            $info['user_info'] = array(
                'user_id' => $user->ID,
                'user_login' => $user->user_login,
                'user_email' => $user->user_email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'phone' => get_user_meta($user->ID, 'phone', true),
                'address' => get_user_meta($user->ID, 'address', true),
                'logo' => $logo,
                'country' => get_user_meta($user->ID, 'country', true),
                'club_name' => get_user_meta($user->ID, 'club_name', true),
                'has_verified_email' => get_user_meta($user->ID, 'has_verified_email', true)
            );
        }
    }
}

/*---------- 4. 更新用戶自己的資料 ------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "save_my_user_info")) {
    // 獲取當前用戶 id
    $user_id = get_current_user_id();

    if (empty($user_id)) {
        $info['status'] = 'error';
        $info['message'] = 'Tidak ditandai'; // 尚未登入
    } else {
        $user = get_user_by('id', $user_id);
        if (!$user) {
            $info['status'] = 'error';
            $info['message'] = 'Pengguna tidak dapat ditemukan.'; // 找不到該用戶
        } else {
            $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
            $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
            $country = isset($_POST['country']) ? $_POST['country'] : '';
            $club_name = isset($_POST['club_name']) ? $_POST['club_name'] : '';
            $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
            $address = isset($_POST['address']) ? $_POST['address'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            $userdata = array(
                'ID' => $user_id,
                'first_name' => $first_name,
                'last_name' => $last_name,
            );

            $result = wp_update_user($userdata);
            
            if (is_wp_error($result)) {
                $info['status'] = 'error';
                $info['message'] = $result->get_error_message();
            } else {

                if (!empty($password)) {
                    wp_set_password($password, $user_id);
                }
                
                update_user_meta($user_id, 'country', $country);
                update_user_meta($user_id, 'club_name', $club_name);
                update_user_meta($user_id, 'phone', $phone);
                update_user_meta($user_id, 'address', $address);

                $info['status'] = 'success';
                $info['message'] = 'Profil pengguna telah diperbarui.';
            }
        }
    }
}


/*---------- 5. 檢查登入 ------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "check_login")) {
    // 獲取當前用戶 id
    $user_id = get_current_user_id();
    
    if (empty($user_id)) {
        $info['status'] = 'error';
        $info['message'] = 'no_login';
    } else {
        $user = get_user_by('id', $user_id);
        if (!$user) {
            $info['status'] = 'error';
            $info['message'] = 'no_user';
        } else {
            // 取得 user meta 的 bvg_role
            $bvg_role = get_user_meta($user_id, 'bvg_role', true);
            $info['status'] = 'success';
            $info['message'] = 'done';
            $info['user_id'] = $user_id;
            $info['bvg_role'] = $bvg_role;
        }
    }
}

/*---------- 6. 登入用戶 ------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "login")) {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $recaptcha_response = isset($_POST['recaptcha_response']) ? $_POST['recaptcha_response'] : '';

    // reCAPTCHA 驗證
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secret_key . "&response=" . $recaptcha_response);
    $response_data = json_decode($response);
    if (!$response_data->success) {
        $info['status'] = 'error';
        $info['message'] = 'Verifikasi reCAPTCHA gagal, coba lagi.';
        print_r(json_encode($info));
        exit;
    }

    $user = wp_authenticate($email, $password);
    if (is_wp_error($user)) {
        $info['status'] = 'error';
        $info['message'] = 'failed';
    } else {
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);
        $info['status'] = 'success';
        $info['message'] = 'done';
        $info['user_id'] = $user->ID;
    }
}

/*---------- 7. 檢查密碼重置驗證碼 check_key 是否有效 ------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "check_key")) {
    $key = isset($_POST['key']) ? $_POST['key'] : '';
    $user = $website_db->get_row('wp_bvg_reset_password', array('id' => $key));
    if (!$user) {
        $info['status'] = 'error';
        $info['message'] = 'invalid_key';
    } else {
        $info['status'] = 'success';
        $info['message'] = 'done';
    }
}

/*---------- 8. 重置密碼 ------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "password_change")) {
    $key = isset($_POST['key']) ? $_POST['key'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $user = $website_db->get_row('wp_bvg_reset_password', array('id' => $key));
    if (!$user) {
        $info['status'] = 'error';
        $info['message'] = 'invalid_key';
    } else {
        wp_set_password($password, $user['user_id']);
        $website_db->delete('wp_bvg_reset_password', array('id' => $key));
        $info['status'] = 'success';
        $info['message'] = 'done';
    }
}

/*---------- 9. 社群帳號註冊會員 ------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "register-social")) {
    // 取得目前已登入的用戶ID
    $user_id = get_current_user_id();
    // 獲取POST數據
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $sports = isset($_POST['sports']) ? $_POST['sports'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';

    $recaptcha_response = isset($_POST['recaptcha_response']) ? $_POST['recaptcha_response'] : '';

    // reCAPTCHA 驗證
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secret_key . "&response=" . $recaptcha_response);
    $response_data = json_decode($response);
    if (!$response_data->success) {
        $info['status'] = 'error';
        $info['message'] = 'reCAPTCHA 驗證失敗，請重試。';
        $info['response'] = $response_data;
        print_r(json_encode($info));
        exit;
    }


        // 更新用戶資料: first_name, last_name, phone, sports, gender
        $userdata = array(
            'ID' => $user_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
        );

        wp_update_user($userdata);

        update_user_meta($user_id, 'phone', $phone);
        update_user_meta($user_id, 'sports', $sports);
        update_user_meta($user_id, 'gender', $gender);


        if (is_wp_error($user_id)) {
            $info['status'] = 'error';
            $info['message'] = $user_id->get_error_message();
        } else {

            

            $info['status'] = 'success';
            $info['message'] = 'Thank you for registering.';
            $info['user_id'] = $user_id;

        }
    
}

$serialized_info = json_encode($info);
print_r($serialized_info);
