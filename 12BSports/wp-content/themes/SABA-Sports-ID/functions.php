<?php /*

  This file is part of a child theme called SABA Sports TH.
  Functions in this file will be loaded before the parent theme's functions.
  For more information, please read
  https://developer.wordpress.org/themes/advanced-topics/child-themes/

*/

// this code loads the parent's stylesheet (leave it in place unless you know what you're doing)
 
function your_theme_enqueue_styles() {

    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, 
      get_template_directory_uri() . '/style.css'); 

    wp_enqueue_style( 'child-style', 
      get_stylesheet_directory_uri() . '/style.css', 
      array($parent_style), 
      wp_get_theme()->get('Version') 
    );
}

add_action('wp_enqueue_scripts', 'your_theme_enqueue_styles');


function my_custom_scripts() {
  $version = date('YmdHis'); // 获取当前时间作为版本号
  wp_enqueue_script('my-custom-js', get_template_directory_uri() . '../../../../bvg/bvg.js', array('jquery'), $version, true);
}
add_action('wp_enqueue_scripts', 'my_custom_scripts');
 
/*  Add your own functions below this line.
    ======================================== */ 

/** 240301 子 登入狀態桌機 **/

function toggle_menu_items_based_on_login_status() {
    ?>
    <style type="text/css">
        <?php if ( is_user_logged_in() ): ?>
        .user-logged-out { display: none !important; }
        .user-logged-in { display: block !important; }
        <?php else: ?>
        .user-logged-in { display: none !important; }
        .user-logged-out { display: block !important; }
        <?php endif; ?>
    </style>
    <?php
}
add_action( 'wp_head', 'toggle_menu_items_based_on_login_status' );
/** 240301 子 登入頭像手機 **/

function yh_add_avatar_to_menu( $menu_items ) {
    global $current_user;
    get_currentuserinfo();

    foreach ( $menu_items as $menu_item ) {
        if ( strpos( $menu_item->title, '{{user_avatar}}' ) !== false ) {
            if ( is_user_logged_in() ) {
                // Get user avatar and add custom class 'user-avatar'
                $avatar = get_avatar( $current_user->ID, 30, '', '', array('class' => 'user-avatar') );
                // Add CSS style directly to the image tag
                $avatar_with_style = str_replace( '<img', '<img style="border-radius: 50%;"', $avatar );
                $menu_item->title = str_replace( '{{user_avatar}}', $avatar_with_style, $menu_item->title );
            } else {
                // If user is not logged in, don't display any avatar
                $menu_item->title = str_replace( '{{user_avatar}}', '', $menu_item->title );
            }
        }
    }
    return $menu_items;
}
add_filter( 'wp_nav_menu_objects', 'yh_add_avatar_to_menu' );

/** 240301 子 登入頭像桌機 **/
function yh_get_user_avatar_html() {
    // 獲取當前使用者的 ID
    $current_user_id = get_current_user_id();

    // 檢查使用者是否已登入
    if ( is_user_logged_in() && $current_user_id ) {
        // 獲取使用者頭像 HTML
        $avatar = get_avatar( $current_user_id, 30, '', '', array('class' => 'user-avatar') );

        // 返回使用者頭像 HTML
        return $avatar;
    }

    // 如果使用者未登入或者未找到頭像，返回空字符串
    return '';
}

// 將函式應用於一般 HTML 代碼
add_shortcode( 'user_avatar', 'yh_get_user_avatar_html' );

/* 如果文章有 SabaVideoContent 這個 meta_key，而且 meta_key 的值不是空的，就在post content的最後面顯示以下html(模板)

        並且帶入 video.js 的初始化程式碼與css
*/
function saba_video_content($content) {
    /*  根據網站網址轉換各站的影片網址
        以下各站使用 chcmbi.accordde.com:
        www.thesabanewscn.com
        www.sabanews-th.com
        www.thesabasportsindo.com
        以下各站使用 glocmbi.accordde.com:
        www.thesabamynews.com
        www.cricsabasportsin.com
    */
    $video_url = '';
    if(strpos($_SERVER['HTTP_HOST'], 'thesabanewscn.com') !== false) {
        $video_url = 'https://chcmbi.accordde.com';
    } else if(strpos($_SERVER['HTTP_HOST'], 'sabanews-th.com') !== false) {
        $video_url = 'https://chcmbi.accordde.com';
    } else if(strpos($_SERVER['HTTP_HOST'], 'thesabasportsindo.com') !== false) {
        $video_url = 'https://chcmbi.accordde.com';
    } else if(strpos($_SERVER['HTTP_HOST'], 'thesabamynews.com') !== false) {
        $video_url = 'https://glocmbi.accordde.com';
    } else if(strpos($_SERVER['HTTP_HOST'], 'cricsabasportsin.com') !== false) {
        $video_url = 'https://glocmbi.accordde.com';
    }
    

    global $post;
    $SabaVideoContent = get_post_meta($post->ID, 'SabaVideoContent', true);
    // 取得文章封面
    $post_thumbnail_id = get_post_thumbnail_id($post->ID);
    $post_thumbnail_url = get_the_post_thumbnail_url($post->ID, 'full');

    if ($SabaVideoContent) {
        $video_url = $video_url . $SabaVideoContent; // m3u8 格式的影片網址
        $content .= '<div class="article-video" v-if="isShowSabaVideo">
        <video
            id="saba-video"
            class="video-js"
            controls
            preload="auto"
            width="640"
            height="264"
            poster="' . $post_thumbnail_url . '"
            data-setup="{}"
        >
            <source src="' . $video_url . '" type="application/x-mpegURL" />
            <p class="vjs-no-js">
            To view this video please enable JavaScript, and consider upgrading to a
            web browser that
            <a href="https://videojs.com/html5-video-support/" target="_blank"
                >supports HTML5 video</a
            >
            </p>
        </div>
        <input type="hidden" id="SabaVideoContent" value="' . $video_url.'">
        ';
        wp_enqueue_script('videojs', 'https://vjs.zencdn.net/8.10.0/video.min.js', array(), false, true);
        wp_enqueue_style('videojs', 'https://vjs.zencdn.net/8.10.0/video-js.css');
    }
    return $content;
}
add_filter('the_content', 'saba_video_content');


/* 240315 大吳 會員註冊後發送驗證信 */
function custom_wp_new_user_notification_email( $wp_new_user_notification_email, $user, $blogname ) {
    $user_data = get_userdata($user->ID);
    $verification_code = get_user_meta($user->ID, 'verification_code', true);
    // 設定郵件標題
    $wp_new_user_notification_email['subject'] = 'Aktifkan akun Anda di ' . $blogname;

    $activate_url = 'https://www.thesabasportsindo.com/user-verification.php?code=' . $verification_code;

    $message = 'nomor akun: ' . $user_data->user_login . "\r\n\r\n";
    $message .= 'Klik URL di bawah untuk mengaktifkan akun Anda:\r\n';
    $message .= $activate_url . "\r\n\r\n";

    $wp_new_user_notification_email['message'] = $message;

    return $wp_new_user_notification_email;
}
add_filter( 'wp_new_user_notification_email', 'custom_wp_new_user_notification_email', 10, 3 );

/* 240315 大吳 忘記密碼重通知信 */
function custom_retrieve_password_message( $message, $key, $user_login, $user_data ) {
    // $key 是用戶的重置密碼鍵
    // $user_login 是用戶名
    // $user_data 是 WP_User 對象

    // 創建新的郵件內容
    $site_name = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    $reset_link = "https://www.thesabasportsindo.com/forget-password/?key=$key";

    $message = "Kami menerima permintaan pengaturan ulang kata sandi dari Anda di {$site_name}.\n\n";
    $message .= "Silakan klik tautan di bawah ini untuk mengatur ulang kata sandi Anda:\n";
    $message .= "{$reset_link}\n\n";
    $message .= "Jika ini bukan tanggung jawab Anda, harap abaikan email ini dan kata sandi Anda tidak akan diubah.\n";

    return $message;
}
add_filter( 'retrieve_password_message', 'custom_retrieve_password_message', 10, 4 );


/* 240315 大吳 停用一般用戶的 WordPress 工具欄 */
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}


/* 240403 大吳: 社群帳號登入 */
function social_login_enqueue_styles() {
    wp_enqueue_style('social-login-css', '/bvg/social_login.css?c=' . time());
}
add_action('wp_enqueue_scripts', 'social_login_enqueue_styles');


function bvg_js() {
    //wp_enqueue_script( 'bvg-js', '/wp-content/themes/hello-elementor/assets/js/bvg.js', array(), time(), true );
	$url = home_url( add_query_arg( null, null ) );
    $parsed_url = wp_parse_url( $url );

    wp_enqueue_script( 'bvg-social-login1', '/bvg/social_login.js?c=' . time(), array('jquery'), '230418', true );
	
    if ( $parsed_url['path'] === '/user/') {
        wp_enqueue_script( 'user-js', '/bvg/user.js', array('jquery'), time(), true );
    }

}
add_action( 'wp_enqueue_scripts', 'bvg_js' );



/** 230205不同登入狀態，切換顯示選單 **/

function toggle_logged_in_menu_items() {
  if ( is_user_logged_in() ) {
    $display = 'block';
    $hide = 'none';
  } else {
    $display = 'none';
    $hide = 'block';
  }
  ?>
  <style>
    #menu-1-b420636 .menu-item-6228, #menu-1-ab0491a .menu-item-6228 {
      display: <?php echo $hide; ?>;
    }
    #menu-1-b420636 .menu-item-6231, #menu-1-ab0491a .menu-item-6231 {
      display: <?php echo $display; ?>;
    }
  </style>
  <?php
}
add_action( 'wp_head', 'toggle_logged_in_menu_items' );


/** 230205顯示使用者名稱 **/
function yh_display_display_name_wp_menu( $menu_items ) {
    global $current_user;
    foreach ( $menu_items as $menu_item ) {
        if ( strpos( $menu_item->title, '{{display_name}}' ) !== false ) {
            // Get display_name, otherwise set it to blank.
            if ( $current_user->user_login ) {
                $display_name = esc_html($current_user->display_name);
            } else {
                $display_name = '';
            }
                $menu_item->title =  str_replace( '{{display_name}}',  $display_name, $menu_item->title );
        }
    }
    return $menu_items;
}
add_filter( 'wp_nav_menu_objects', 'yh_display_display_name_wp_menu' );





function bvg_handle_line_login() {
    if (isset($_GET['code']) && isset($_GET['state']) && $_GET['state'] === 'line_login') {
        $code = $_GET['code'];

        // Replace the following values with your LINE Channel ID and Channel Secret
        $client_id = '{your_line_channel_id}';
        $client_secret = '{your_line_channel_secret}';
        $redirect_uri = home_url('/?line-login=1'); // Redirect URI for your website

        // Request access token
        $url = 'https://api.line.me/oauth2/v2.1/token';
        $data = array(
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirect_uri,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            );


            $options = array(
                'http' => array(
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data),
                ),
            );
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            $response = json_decode($result, true);
        
            // Get LINE user profile
            $access_token = $response['access_token'];
            $headers = array(
                'http' => array(
                    'header' => "Authorization: Bearer $access_token\r\n",
                    'method' => 'GET',
                ),
            );
            $context = stream_context_create($headers);
            $profile_data = file_get_contents('https://api.line.me/v2/profile', false, $context);
            $profile = json_decode($profile_data, true);
        
            // Use the $profile array to create or login a user on your WordPress site
            // ...
        }

    }
    add_action('init', 'bvg_handle_line_login');


    function bvg_handle_social_login() {
        if (isset($_POST['social_network']) && isset($_POST['user_data'])) {
            $social_network = $_POST['social_network'];
            $user_data = $_POST['user_data'];
    
            // Get email address from user data
            $email = $user_data['email'];
    
            // Check if user already exists in WordPress by email
            $user = get_user_by('email', $email);
    
            if ($user) {
                // User already exists, update the profile
                // ...
    
                // Log the user in
                wp_set_auth_cookie($user->ID);
            } else {
                // User does not exist, create a new account
                $username = $social_network . '_' . $user_data['id'];
                $password = wp_generate_password();
                $name = $user_data['name'];
    
                // Create new user and get the user ID
                $user_id = wp_create_user($username, $password, $email);
    
                // Update user's display name and first/last name
                wp_update_user(array(
                    'ID' => $user_id,
                    'display_name' => $name,
                    'first_name' => $name,
                    'last_name' => '',
                ));
    
                // Log the new user in
                wp_set_auth_cookie($user_id);
            }
    
            // Send a JSON response back to the front-end
            header('Content-Type: application/json');
            echo json_encode(array(
                'success' => true,
                'message' => 'User logged in successfully.',
            ));
            exit;
        }
    }
    add_action('wp_ajax_nopriv_bvg_social_login', 'bvg_handle_social_login');
    
    
    // 自動登出
    function auto_logout_and_redirect() {
        if ( isset( $_GET['action'] ) && $_GET['action'] == 'logout' && isset( $_GET['auto'] ) ) {
            $redirect_to = home_url() . '?loggedout=true';
            if ( isset( $_GET['redirect_to'] ) && ! empty( $_GET['redirect_to'] ) ) {
                $redirect_to = $_GET['redirect_to'] . '?loggedout=true';
            }
            session_start();
            unset( $_SESSION['username'] );
            unset( $_SESSION['uid'] );
            unset( $_SESSION['avatar'] );
            unset( $_SESSION['role'] );
            wp_logout();
            wp_safe_redirect( $redirect_to );
            exit();
        }
    }
    add_action( 'init', 'auto_logout_and_redirect' );
    
    function modify_logout_url( $logout_url, $redirect ) {
        $logout_url = add_query_arg( 'auto', 'true', $logout_url );
        return $logout_url;
    }
    add_filter( 'logout_url', 'modify_logout_url', 10, 2 );

// uefa-2024隱藏header
function custom_css_for_uefa_2024() {
    // 檢查當前請求的URI是否包含特定子路徑
    if (strpos($_SERVER['REQUEST_URI'], '/uefa2024') !== false) {
        echo '<style>
                .smart-head-main .smart-head-mid, .smart-head { display: none; }
              </style>';
    }
}
add_action('wp_head', 'custom_css_for_uefa_2024');


// uefa-2024 menu
function add_custom_scripts() {
    ?>
    <script>
        var scrollmenu_eu24 = document.getElementById('scrollmenu_eu24');
        var scrollButton = document.getElementById('scroll-button24');

        window.onload = function() {
            updateScrollButton();  // 確保載入時執行一次檢查
        }

        function updateScrollButton() {
            if (scrollmenu_eu24 && scrollmenu_eu24.scrollWidth > scrollmenu_eu24.clientWidth) {
                scrollButton.style.display = 'block';
            } else if (scrollButton) {
                scrollButton.style.display = 'none';
            }

            if (scrollmenu_eu24 && scrollmenu_eu24.scrollLeft + scrollmenu_eu24.clientWidth >= scrollmenu_eu24.scrollWidth) {
                scrollButton.style.display = 'none';
            }
        }

        if (scrollmenu_eu24) {
            scrollmenu_eu24.addEventListener('scroll', updateScrollButton);
        }

        function scrollRight() {
            if (scrollmenu_eu24) {
                scrollmenu_eu24.scrollLeft += 150;
            }
        }
    </script>
    <?php
}
add_action('wp_footer', 'add_custom_scripts');

// uefa-2024 stadium
function add_custom_js_to_stadium_page() {
    if (is_page('uefa2024/stadium')) {
        ?>
        <script>
        function changeContent(image, name, info, description) {
            var display = document.getElementById('stadium_mainDisplay');
            var stadiumName = document.getElementById('stadiumName');
            var stadiumInfo = document.getElementById('stadiumInfo');
            var stadiumDescription = document.getElementById('stadiumDescription');

            display.style.backgroundImage = 'url(' + image + ')';
            stadiumName.textContent = name;
            stadiumInfo.innerHTML = info;
            stadiumDescription.innerHTML = description;

            var thumbnails = document.querySelectorAll('.thumbnail');
            thumbnails.forEach(function(thumbnail) {
                thumbnail.classList.remove('active');
            });

            event.currentTarget.classList.add('active');
        }

        let scrollLeft;
        const slider = document.querySelector('.stadium_thumbnail-slider');

        slider.addEventListener('mousedown', (e) => {
          isDown = true;
          slider.classList.add('active');
          startX = e.pageX - slider.offsetLeft;
          scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', () => {
          isDown = false;
          slider.classList.remove('active');
        });

        slider.addEventListener('mouseup', () => {
          isDown = false;
          slider.classList.remove('active');
        });

        slider.addEventListener('mousemove', (e) => {
          if (!isDown) return;
          e.preventDefault();
          const x = e.pageX - slider.offsetLeft;
          const walk = x - startX;
          slider.scrollLeft = scrollLeft - walk;
        });
        </script>
        <?php
    }
}

add_action('wp_footer', 'add_custom_js_to_stadium_page');

//uefa-2024 special record
function add_custom_js_to_special_record_page() {
    if (is_page('uefa2024/special-record')) {
        ?>
        <script>
        let isDown = false; // 是否按下鼠标
        let startX; // 开始拖动时的 X 坐标
        let scrollLeft; // 开始拖动时的滚动位置

        const slider = document.querySelector('.eusc_thumbnail-slider');

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('active');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('active');
        });

        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('active');
        });

        slider.addEventListener('mousemove', throttle((e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 3; // 调整拖动的速度
            slider.scrollLeft = scrollLeft - walk;
        }, 10)); // 使用节流技术控制事件触发频率

        function throttle(callback, limit) {
            let waiting = false;
            return function () {
                if (!waiting) {
                    callback.apply(this, arguments);
                    waiting = true;
                    setTimeout(() => {
                        waiting = false;
                    }, limit);
                }
            }
        }

        function changeContent(image, name, info, description) {
            var display = document.getElementById('eusc_mainDisplay');
            var euscName = document.getElementById('euscName');
            var euscInfo = document.getElementById('euscInfo');
            var euscDescription = document.getElementById('euscDescription');

            display.style.backgroundImage = 'url(' + image + ')';
            euscName.textContent = name;
            euscInfo.innerHTML = info;
            euscDescription.innerHTML = description;

            var thumbnails = document.querySelectorAll('.thumbnail');
            thumbnails.forEach(function (thumbnail) {
                thumbnail.classList.remove('active');
            });

            event.currentTarget.classList.add('active');
        }
        </script>
        <?php
    }
}

add_action('wp_footer', 'add_custom_js_to_special_record_page');

// uefa-2024 star player
function add_custom_js_to_star_player_page() {
    if (is_page('uefa2024/star-player')) {
        ?>
        <script>
        function searchPlayer() {
            var selectedName = document.getElementById('eu24spSelect').value;
            var cards = document.querySelectorAll('.eu24sp_photo-card');
            var visibleCardsCount = 0;
            var visibleCard;

            cards.forEach(card => {
                if (selectedName === "" || card.getAttribute('data-name') === selectedName) {
                    card.style.display = '';
                    visibleCardsCount++;
                    visibleCard = card;
                } else {
                    card.style.display = 'none';
                }
            });

            // 如果只剩一个可见卡片，则在手機上设置宽度为100%
            if (visibleCardsCount === 1 && visibleCard && window.innerWidth < 768) {
                visibleCard.style.width = '100%';
            }

            // 添加样式
            cards.forEach(card => {
                if (visibleCardsCount === 1 && card === visibleCard) {
                    card.style.color = '#ffc719';
                    card.style.transition = 'opacity 1s ease, visibility 0s 0s';
                } else {
                    card.style.border = '';
                    card.style.transition = '';
                }
            });

        }
        </script>
        <?php
    }
}

add_action('wp_footer', 'add_custom_js_to_star_player_page');

  

/* 240425 彭彭 For  歐洲杯 */

function games_scripts() {
    error_log(123);
	$version = date('YmdHis'); // 获取当前时间作为版本号
	wp_enqueue_script('games-js', home_url() . '/bvg/games.js', array('jquery'), $version, true);
  }
  add_action('wp_enqueue_scripts', 'games_scripts');

// 全局變量用於存儲聯賽名稱和賽季數據
$GLOBALS['league_and_season_cache1'] = array();

function get_league_and_season_data($league, $season) {
    // 使用聯盟和賽季作為緩存鍵
    $cache_key = $league . '_' . $season;

    // 如果已經獲取過資料，則從全局變量中返回結果
    if (isset($GLOBALS['league_and_season_cache1'][$cache_key])) {
        return $GLOBALS['league_and_season_cache1'][$cache_key];
    }

    // 初始化 cURL
    $curl = curl_init();

    // 設置 cURL 選項
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api-football-v1.p.rapidapi.com/v3/leagues?id=" . $league . "&season=" . $season,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: api-football-v1.p.rapidapi.com",
            "X-RapidAPI-Key: b93c579eb5msh3a862c325b248f4p197710jsn22158d3cbd15"
        ],
    ]);

    // 執行 cURL 請求
    $response = curl_exec($curl);
    $err = curl_error($curl);

    // 關閉 cURL
    curl_close($curl);

    // 檢查是否有錯誤
    if ($err) {
        return "cURL Error #:" . $err;
    } else {
        $data = json_decode($response, true);
        $league_data = array(
            'league_name' => $data['response'][0]['league']['name'],
            'season' => $data['response'][0]['season'][0]['year'],
        );

        // 將結果存儲到全局變量中
        $GLOBALS['league_and_season_cache1'][$cache_key] = $league_data;

        return $league_data;
    }
}


// 即時比分模組的樣式
function scoreboard_styles() {
    wp_enqueue_style( 'scoreboard-style', '/games2/scoreboard.css?s='. time());
    wp_enqueue_style( 'widgets-style', '/games2/api-football.css?s='. time());
}
add_action( 'wp_enqueue_scripts', 'scoreboard_styles' );



// 單場賽事短代碼
function wg_api_football_game_shortcode() {
    $html = '<div id="wg-api-football-game"
               data-host="api-football-v1.p.rapidapi.com"
               data-key="b93c579eb5msh3a862c325b248f4p197710jsn22158d3cbd15"
               data-id="' . $_GET['fixture'] . '"
               data-theme="false"
               data-refresh="90"
               data-show-errors="false"
               data-show-logos="true">
            </div>
            <script type="module" src="https://widgets.api-sports.io/2.0.3/widgets.js"></script>';

    return $html;
}
add_shortcode( 'wg_api_football_game', 'wg_api_football_game_shortcode' );

// 排行榜-隊伍 短代碼
function wg_api_football_standings_shortcode() {
    $html = '<div id="wg-api-football-standings"
				data-host="api-football-v1.p.rapidapi.com"
				data-key="b93c579eb5msh3a862c325b248f4p197710jsn22158d3cbd15"
				data-league="' . $_GET['league'] . '"
				data-team=""
				data-season="' . $_GET['season'] . '"
				data-theme="false"
				data-show-errors="false"
				data-show-logos="true"
				class="wg_loader">
			</div>
			<script
				type="module"
				src="https://widgets.api-sports.io/2.0.3/widgets.js">
			</script>';

    return $html;
}
add_shortcode( 'wg_api_football_standings', 'wg_api_football_standings_shortcode' );


// 全局變量用於存儲最佳射手數據
$GLOBALS['topscorers_cache'] = array();

// 排行榜-最佳射手 短代碼
function wg_api_football_topscorers_shortcode() {
	
	
	// 使用聯盟和賽季作為緩存鍵
    $cache_key = $league . '_' . $season;

    // 如果已經獲取過資料，則從全局變量中返回結果
    if (isset($GLOBALS['topscorers_cache'][$cache_key])) {
        return $GLOBALS['topscorers_cache'][$cache_key];
    }
	
	
	
    $curl = curl_init();

	curl_setopt_array($curl, [
		CURLOPT_URL => "https://api-football-v1.p.rapidapi.com/v3/players/topscorers?league=" . $_GET['league'] . "&season=" . $_GET['season'],
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => [
			"X-RapidAPI-Host: api-football-v1.p.rapidapi.com",
			"X-RapidAPI-Key: b93c579eb5msh3a862c325b248f4p197710jsn22158d3cbd15"
		],
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		$msg = "cURL Error #:" . $err;
	} else {
		$data = $response;
	}
	
	$all_data = json_decode($data, true);
	
	// 將結果存儲到全局變量中
    $GLOBALS['topscorers_cache'][$cache_key] = $all_data;
	
	
	
	$html = '<table class="wg-table" id="wg-football-topscorers"><thead></thead><tr>
                                    <td class="wg_header wg_text_center">Rank</td>
                                    <td class="wg_header ">Name</td>
									<td class="wg_header ">Team</td>
									<td class="wg_header wg_text_center">Goals</td>  
									<td class="wg_header wg_text_center">Assists</td>  
									<td class="wg_header wg_text_center wg_hide_xs">Shots</td>
									<td class="wg_header wg_text_center wg_hide_xs">Shots on</td>
									
                                </tr>';
								/*
								   
									                               
                                    <td class="wg_header wg_text_center wg_tooltip wg_tooltip_left" data-text="Win">W</td>
                                    <td class="wg_header wg_text_center wg_tooltip wg_tooltip_left" data-text="Draw">D</td>
                                    <td class="wg_header wg_text_center wg_tooltip wg_tooltip_left" data-text="Lose">L</td>
                                    <td class="wg_header wg_text_center wg_tooltip wg_tooltip_left wg_hide_xxs" data-text="Goals For : Goals Against">G</td>
                                    <td class="wg_header wg_text_center wg_hide_xs">+/-</td>
                                    <td class="wg_header wg_text_center wg_tooltip wg_tooltip_left" data-text="Points">P</td>
                                    <td class="wg_header wg_text_center wg_hide_xs"></td>
                                    <td class="wg_header wg_text_center wg_hide_xs"></td>
									*/
	$i = 1;
	foreach($all_data['response'] AS $player) {
		$html .= '<tr>';
		$html .= '<td class="wg_text_center wg_bolder wg_width_20">' . $i . '</td>';
		$html .= '<td class="wg_nowrap cursor-hover"><img class="wg_logo cursor-hover" src="' . $player['player']['photo']. '" >' . $player['player']['name']. '</td>';
		$html .= '<td class="wg_nowrap cursor-hover"><img class="wg_logo cursor-hover" src="' . $player['statistics'][0]['team']['logo'].'"  >'. ' ' . $player['statistics'][0]['team']['name'].  '</td>';
		$html .= '<td class="wg_text_center wg_bolder wg_width_100">' . $player['statistics'][0]['goals']['total'] . '</td>';
		$html .= '<td class="wg_text_center wg_bolder wg_width_100">' . $player['statistics'][0]['goals']['assists'] . '</td>';
		$html .= '<td class="wg_text_center wg_bolder wg_width_100 wg_hide_xs">' . $player['statistics'][0]['shots']['total'] . '</td>';
		$html .= '<td class="wg_text_center wg_bolder wg_width_100 wg_hide_xs">' . $player['statistics'][0]['shots']['on'] . '</td>';
		$html .= '</tr>';
		
		$i++;
	}
	$html .= '</table>';
	
	

    return $html;
}
add_shortcode( 'wg_api_football_topscorers', 'wg_api_football_topscorers_shortcode' );




// 全局變量用於存儲最佳助攻手數據
$GLOBALS['topassists_cache'] = array();

// 排行榜-最佳助攻 短代碼
function wg_api_football_topassists_shortcode() {
	
	
	// 使用聯盟和賽季作為緩存鍵
    $cache_key = $league . '_' . $season;

    // 如果已經獲取過資料，則從全局變量中返回結果
    if (isset($GLOBALS['topassists_cache'][$cache_key])) {
        return $GLOBALS['topassists_cache'][$cache_key];
    }
	
	
	
    $curl = curl_init();

	curl_setopt_array($curl, [ 
		CURLOPT_URL => "https://api-football-v1.p.rapidapi.com/v3/players/topassists?league=" . $_GET['league'] . "&season=" . $_GET['season'],
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => [
			"X-RapidAPI-Host: api-football-v1.p.rapidapi.com",
			"X-RapidAPI-Key: b93c579eb5msh3a862c325b248f4p197710jsn22158d3cbd15"
		],
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		$msg = "cURL Error #:" . $err;
	} else {
		$data = $response;
	}
	
	$all_data = json_decode($data, true);
	
	// 將結果存儲到全局變量中
    $GLOBALS['topassists_cache'][$cache_key] = $all_data;
	
	
	
	$html = '<table class="wg-table" id="wg-football-topassists"><thead></thead><tr>
                                    <td class="wg_header wg_text_center">Rank</td>
                                    <td class="wg_header ">Name</td>
									<td class="wg_header ">Team</td>
									<td class="wg_header wg_text_center">Assists</td>  
									<td class="wg_header wg_text_center">Goals</td>  				
									<td class="wg_header wg_text_center wg_hide_xs">Shots</td>
									<td class="wg_header wg_text_center wg_hide_xs">Shots on</td>
									
                                </tr>';
								/*
								   
									                               
                                    <td class="wg_header wg_text_center wg_tooltip wg_tooltip_left" data-text="Win">W</td>
                                    <td class="wg_header wg_text_center wg_tooltip wg_tooltip_left" data-text="Draw">D</td>
                                    <td class="wg_header wg_text_center wg_tooltip wg_tooltip_left" data-text="Lose">L</td>
                                    <td class="wg_header wg_text_center wg_tooltip wg_tooltip_left wg_hide_xxs" data-text="Goals For : Goals Against">G</td>
                                    <td class="wg_header wg_text_center wg_hide_xs">+/-</td>
                                    <td class="wg_header wg_text_center wg_tooltip wg_tooltip_left" data-text="Points">P</td>
                                    <td class="wg_header wg_text_center wg_hide_xs"></td>
                                    <td class="wg_header wg_text_center wg_hide_xs"></td>
									*/
	$i = 1;
	foreach($all_data['response'] AS $player) {
		$html .= '<tr>';
		$html .= '<td class="wg_text_center wg_bolder wg_width_20">' . $i . '</td>';
		$html .= '<td class="wg_nowrap cursor-hover"><img class="wg_logo cursor-hover" src="' . $player['player']['photo']. '" >' . $player['player']['name']. '</td>';
		$html .= '<td class="wg_nowrap cursor-hover"><img class="wg_logo cursor-hover" src="' . $player['statistics'][0]['team']['logo'].'"  >'. ' ' . $player['statistics'][0]['team']['name'].  '</td>';
		$html .= '<td class="wg_text_center wg_bolder wg_width_100">' . $player['statistics'][0]['goals']['assists'] . '</td>';
		$html .= '<td class="wg_text_center wg_bolder wg_width_100">' . $player['statistics'][0]['goals']['total'] . '</td>';
		$html .= '<td class="wg_text_center wg_bolder wg_width_100 wg_hide_xs">' . $player['statistics'][0]['shots']['total'] . '</td>';
		$html .= '<td class="wg_text_center wg_bolder wg_width_100 wg_hide_xs">' . $player['statistics'][0]['shots']['on'] . '</td>';
		$html .= '</tr>';
		
		$i++;
	}
	$html .= '</table>';
	
	

    return $html;
}
add_shortcode( 'wg_api_football_topassists', 'wg_api_football_topassists_shortcode' );



// 對陣紀錄 短代碼
function wg_api_football_h2h_shortcode() {
	
	$fixture_id = $_GET['fixture'];
	$fixture_info = get_fixture_info($fixture_id);
	if (isset($fixture_info)) {
		// 組合新的標題內容
		$away = $fixture_info['teams']['away']['id'];
		$home = $fixture_info['teams']['home']['id'];

	}
	
    $curl = curl_init();

	curl_setopt_array($curl, [
		CURLOPT_URL => "https://api-football-v1.p.rapidapi.com/v3/fixtures/headtohead?h2h=" . $away . '-' . $home,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => [
			"X-RapidAPI-Host: api-football-v1.p.rapidapi.com",
			"X-RapidAPI-Key: b93c579eb5msh3a862c325b248f4p197710jsn22158d3cbd15"
		],
	]);
 
	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		$msg = "cURL Error #:" . $err;
	} else {
		$data = $response;
	}
	
	$all_data = json_decode($data, true);
	//echo $data  ;
	
	$filtered_games = array();
	foreach ($all_data['response'] as $game) {
		if ($game['league']['id'] == 39) {
			$filtered_games[] = $game;
		}
	}

	usort($filtered_games, function($a, $b) {
		$a_time = strtotime($a['fixture']['date']);
		$b_time = strtotime($b['fixture']['date']);
		return $b_time - $a_time;
	});

	$html = '<table class="wg-table"><tr><td class="wg_header cursor-hover" colspan="3" style="text-align: center;">Head 2 Head</td></tr>';
	
	$i = 0;
	foreach($filtered_games AS $game) {
		$date = date('Y-m-d', strtotime($game['fixture']['date']));
		$html .= '<tbody><tr>';
		$html .= '<td class="wg_text_center wg_no_border cursor-hover">';
		$html .= '<a href="https://th.sytesports.com/score/?league='. $game['league']['id'] .'&season='. $game['league']['season'] .'&fixture='. $game['fixture']['id'] .'" target="_blank">';
		$html .= '<div class="wb_img_block cursor-hover"><img class="wg_logo_game cursor-hover" src="' . $game['teams']['away']['logo'].'" loading="lazy" onerror="this.style.display=&quot;none&quot;"></div>';
		$html .= '</a></td>';

		$html .= '<td class="wg_text_center wg_no_border cursor-hover" rowspan="2">';
		$html .= '<a style="color: black;" href="https://th.sytesports.com/score/?league='. $game['league']['id'] .'&season='. $game['league']['season'] .'&fixture='. $game['fixture']['id'] .'" target="_blank">';
		$html .= '<br><br><br>'. $date .'<br><br>';
		$html .= '<span id="football-game-div-score-868209" class="wg_modal_score undefined cursor-hover">' . $game['goals']['away'].' - ' . $game['goals']['home'].'</span>';
		$html .= '</a></td>';

		$html .= '<td class="wg_text_center wg_no_border cursor-hover">';
		$html .= '<a href="https://th.sytesports.com/score/?league='. $game['league']['id'] .'&season='. $game['league']['season'] .'&fixture='. $game['fixture']['id'] .'" target="_blank">';
		$html .= '<div class="wb_img_block cursor-hover"><img class="wg_logo_game cursor-hover" src="' . $game['teams']['home']['logo'].'" loading="lazy" onerror="this.style.display=&quot;none&quot;"></div>';
		$html .= '</a></td>';

		$html .= '</tr><tr>';

		$html .= '<td id="football-div-home-868209" class="wg_text_center undefined wg_no_border wg_modal_team cursor-hover">';
		$html .= '<a style="color: black;" href="https://th.sytesports.com/score/?league='. $game['league']['id'] .'&season='. $game['league']['season'] .'&fixture='. $game['fixture']['id'] .'" target="_blank">';
		$html .= $game['teams']['away']['name'];
		$html .= '</a></td>';

		$html .= '<td id="football-div-away-868209" class="wg_text_center wg_no_border wg_modal_team wg_bolder cursor-hover">';
		$html .= '<a style="color: black;" href="https://th.sytesports.com/score/?league='. $game['league']['id'] .'&season='. $game['league']['season'] .'&fixture='. $game['fixture']['id'] .'" target="_blank">';
		$html .= $game['teams']['home']['name'];
		$html .= '</a></td>';

		$html .= '</tr></tbody>';
		$i++;
		if($i==5){
			break;
		}
	}
	$html .= '</table>';

    return $html;
}
add_shortcode( 'wg_api_football_h2h', 'wg_api_football_h2h_shortcode' );



// 全局變量用於存儲get_fixture_info的結果
$GLOBALS['fixture_info_cache'] = array();

function get_fixture_info($fixture_id) {
	
	// 如果已經獲取過資料，則從全局變量中返回結果
    if (isset($GLOBALS['fixture_info_cache'][$fixture_id])) {
        return $GLOBALS['fixture_info_cache'][$fixture_id];
    }
	
	
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api-football-v1.p.rapidapi.com/v3/fixtures?id=" . $fixture_id,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "x-rapidapi-host: api-football-v1.p.rapidapi.com",
        "x-rapidapi-key: b93c579eb5msh3a862c325b248f4p197710jsn22158d3cbd15"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      return "Error:" . $err;
    } else {
      $fixture_data = json_decode($response, true);
      $fixture_info = $fixture_data['response'][0];

      // 將結果存儲到全局變量中
      $GLOBALS['fixture_info_cache'][$fixture_id] = $fixture_info;
      return $fixture_info;
    }
}

function custom_title($title) {
    global $post;
	if ($post && isset($post->post_content)) {

    // 檢查網頁內容中是否包含 [wg_api_football_game] 短代碼
    if (has_shortcode($post->post_content, 'wg_api_football_game')) {
        $fixture_id = $_GET['fixture'];
        $fixture_info = get_fixture_info($fixture_id);
        if (isset($fixture_info)) {
            // 組合新的標題內容
            $new_title = $fixture_info['league']['name'] . ' - ' . $fixture_info['teams']['home']['name'] . ' vs. ' . $fixture_info['teams']['away']['name'] . ' - Support Your Team ข่าวฟุตบอล';

            // 添加新的標題
            $title = $new_title;

        }
    }
	
	
	
	// 檢查網頁內容中是否包含 [wg_api_football_standings] 短代碼
    if (has_shortcode($post->post_content, 'wg_api_football_standings')) {
        $league_info = get_league_and_season_data($_GET['league'], $_GET['season']);
		if($league_info) {
			$title = $_GET['season'] . ' ' . $league_info['league_name'] . ' อันดับ';
		} else {
			
		}
    }
	
	// 檢查網頁內容中是否包含 [wg_api_football_topscorers] 短代碼
	if (has_shortcode($post->post_content, 'wg_api_football_topscorers')) {
        $league_info = get_league_and_season_data($_GET['league'], $_GET['season']);
		if($league_info) {
			$title = $_GET['season'] . ' ' . $league_info['league_name'] . ' ผู้ทำประตูสูงสุด';
		} else {
			
		}
    }
	}

    return $title;
}


add_filter('pre_get_document_title', 'custom_title', 999);

function custom_title_output($title, $id = null) {
    if (is_singular() && has_shortcode(get_post($id)->post_content, 'wg_api_football_game')) {
        $fixture_id = $_GET['fixture'];
        $fixture_info = get_fixture_info($fixture_id);
        if (isset($fixture_info)) {
            $new_title = $fixture_info['league']['name'] . ' - ' . $fixture_info['teams']['home']['name'] . ' vs. ' . $fixture_info['teams']['away']['name'] . ' - Support Your Team ข่าวฟุตบอล';
            return $new_title;
        }
    }
	
	
	// 檢查網頁內容中是否包含 [wg_api_football_standings] 短代碼
	if (is_singular() && has_shortcode(get_post($id)->post_content, 'wg_api_football_standings')) {
        $league_info = get_league_and_season_data($_GET['league'], $_GET['season']);
		if($league_info) {
			$title = $_GET['season'] . ' ' . $league_info['league_name'] . ' อันดับ';
		} else {
			
		}
    }
    return $title;
	
	// 檢查網頁內容中是否包含 [wg_api_football_topscorers] 短代碼
	if (is_singular() && has_shortcode(get_post($id)->post_content, 'wg_api_football_topscorers')) {
		
		
        $league_info = get_league_and_season_data($_GET['league'], $_GET['season']);
		if($league_info) {
			$title = $_GET['season'] . ' ' . $league_info['league_name'] . ' ผู้ทำประตูสูงสุด';
		} else {
			
		}
		
    }
    return $title;
}

add_filter('the_title', 'custom_title_output', 10, 2);



// 修正賽事頁面的 canonical_url
add_filter( 'wpseo_canonical', 'custom_canonical_url' );
function custom_canonical_url( $canonical_url ) {
    $url = home_url( add_query_arg( null, null ) );
    $parsed_url = wp_parse_url( $url );
    $query_vars = array();
    // 確保 query 存在
if ( isset( $parsed_url['query'] ) ) {
    parse_str( $parsed_url['query'], $query_vars );
} else {
    $query_vars = array();
}
    
    // Add league and season parameters to /standings/ page canonical URL
    if ( $parsed_url['path'] === '/standings/' && isset( $query_vars['league'] ) && isset( $query_vars['season'] ) ) {
        $canonical_url = home_url( '/standings/' ) . '?league=' . $query_vars['league'] . '&season=' . $query_vars['season'];
    }
	
	// Add league and season parameters to /topscorers/ page canonical URL
    if ( $parsed_url['path'] === '/topscorers/' && isset( $query_vars['league'] ) && isset( $query_vars['season'] ) ) {
        $canonical_url = home_url( '/topscorers/' ) . '?league=' . $query_vars['league'] . '&season=' . $query_vars['season'];
    }
    
    // Add league, season, and fixture parameters to /score/ page canonical URL
    if ( $parsed_url['path'] === '/score/' && isset( $query_vars['league'] ) && isset( $query_vars['season'] ) && isset( $query_vars['fixture'] ) ) {
        $canonical_url = home_url( '/score/' ) . '?league=' . $query_vars['league'] . '&season=' . $query_vars['season'] . '&fixture=' . $query_vars['fixture'];
    }
    
    return $canonical_url;
}




add_filter( 'wpseo_opengraph_url', 'custom_opengraph_url' );
function custom_opengraph_url( $url ) {
    global $wp_query;
    $query_vars = $wp_query->query_vars;
    
    // Add league and season parameters to /standings/ page og:url
    if ( is_page( 'standings' ) && isset( $query_vars['league'] ) && isset( $query_vars['season'] ) ) {
        $url = home_url( '/standings/' ) . '?league=' . $query_vars['league'] . '&season=' . $query_vars['season'];
    }
	
	// Add league and season parameters to /topscorers/ page og:url
    if ( is_page( 'topscorers' ) && isset( $query_vars['league'] ) && isset( $query_vars['season'] ) ) {
        $url = home_url( '/topscorers/' ) . '?league=' . $query_vars['league'] . '&season=' . $query_vars['season'];
    }
    
    // Add league, season, and fixture parameters to /score/ page og:url
    if ( is_page( 'score' ) && isset( $query_vars['league'] ) && isset( $query_vars['season'] ) && isset( $query_vars['fixture'] ) ) {
        $url = home_url( '/score/' ) . '?league=' . $query_vars['league'] . '&season=' . $query_vars['season'] . '&fixture=' . $query_vars['fixture'];
    }
    
    return $url;
}




// 傷病情況 短代碼
function wg_api_football_injuries_shortcode() {
	
	$fixture_id = $_GET['fixture'];
	
	$fixture_info = get_fixture_info($fixture_id);
	if (isset($fixture_info)) {
		// 組合新的標題內容
		$away = $fixture_info['teams']['away']['id'];
		$home = $fixture_info['teams']['home']['id'];
		$away_name = $fixture_info['teams']['away']['name'];
		$home_name = $fixture_info['teams']['home']['name'];
		$away_logo = $fixture_info['teams']['away']['logo'];
		$home_logo = $fixture_info['teams']['home']['logo'];
	}
	
    $curl = curl_init();

	curl_setopt_array($curl, [
		CURLOPT_URL => "https://api-football-v1.p.rapidapi.com/v3/injuries?fixture=" .$fixture_id,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => [
			"X-RapidAPI-Host: api-football-v1.p.rapidapi.com",
			"X-RapidAPI-Key: b93c579eb5msh3a862c325b248f4p197710jsn22158d3cbd15"
		],
	]);
 
	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		$msg = "cURL Error #:" . $err;
	} else {
		$data = $response;
	}
	
	$all_data = json_decode($data, true);
	//echo $data  ;
	
	$team_home = array();
	foreach ($all_data['response'] as $player) {
		if ($player['team']['id'] == $home) {
			$team_home[] = $player;
		}
	}
	
	$team_away = array();
	foreach ($all_data['response'] as $player) {
		if ($player['team']['id'] == $away) {
			$team_away[] = $player;
		}
	}
	
	$html = '<div class="wg_header cursor-hover my-1"  style="text-align: center;">injuries</div>';
		$html .= '<div class="row" >';
			$html .= '<div class="col-6"><div style="padding: 10px 0; width: 104%;height: 35px; background-color: white; text-align: center;"><img style="max-width: 18px;" class="wg_logo_game cursor-hover" src="' . $home_logo.'" loading="lazy" onerror="this.style.display=&quot;none&quot;">'.$home_name .'</div></div>';
			$html .= '<div class="col-6"><div style="padding: 10px 0; background-color: white;height: 35px; text-align: center;"><img style="max-width: 18px;" class="wg_logo_game cursor-hover" src="' . $away_logo.'" loading="lazy" onerror="this.style.display=&quot;none&quot;">'.$away_name .'</div></div>';
	
			$html .= '</div><div class="row"><div class="col-6"><div style="background-color: white;width: 104%;">';
				$html .= '<div style="padding: 10px 0; display: flex; flex-wrap: wrap; justify-content: center;">';
					foreach($team_home AS $player) {
						$html .= '<div style="display: flex; flex-direction: column; align-items: center; padding:0 10px;">';
						$html .= '<img style="max-width: 70px; margin-bottom: 10px;" class="wg_logo_game cursor-hover" src="' . $player['player']['photo'].'" loading="lazy" onerror="this.style.display=&quot;none&quot;">';
						$html .= '<div style="text-align: center;">' . $player['player']['name'] . '</div>';
						$html .= '</div>';
					}
				$html .= '</div></div>';
				
			$html .= '</div><div class="col-6"><div style="background-color: white;">';
				$html .= '<div style="padding: 10px 0; display: flex; flex-wrap: wrap; justify-content: center; ">';
					foreach($team_away AS $player) {
						$html .= '<div style="display: flex; flex-direction: column; align-items: center;padding:0 10px;">';
						$html .= '<img style="max-width: 70px; margin-bottom: 10px;" class="wg_logo_game cursor-hover" src="' . $player['player']['photo'].'" loading="lazy" onerror="this.style.display=&quot;none&quot;">';
						$html .= '<div style="text-align: center;">' . $player['player']['name'] . '</div>';
						$html .= '</div>';
					}
			$html .= '</div></div></div> ';
		


	 
    return $html;
}
add_shortcode( 'wg_api_football_injuries', 'wg_api_football_injuries_shortcode' );


// uefa-2024 language_switcher
function language_switcher_scripts() {
    // 檢查當前請求的 URI 是否包含 '/uefa2024'
    if (strpos($_SERVER['REQUEST_URI'], '/uefa2024') !== false) {
        ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 設定每個語言對應的基本 URL 和相應的 class
    const languageSettings = [
        { class: 'eu24-language-thai', baseUrl: 'https://www.sabanews-th.com/uefa2024' },
        { class: 'eu24-language-english', baseUrl: 'https://www.thesabamynews.com/uefa2024' },
        { class: 'eu24-language-india', baseUrl: 'https://www.cricsabasportsin.com/uefa2024' },
        { class: 'eu24-language-indonesia', baseUrl: 'https://www.thesabasportsindo.com/uefa2024' },
        { class: 'eu24-language-chinese', baseUrl: 'https://www.thesabanewscn.com/uefa2024' }
	];

    // 為每種語言的選單添加點擊事件
    languageSettings.forEach(setting => {
        // 獲取所有具有該語言 class 的元素
        const menuElements = document.getElementsByClassName(setting.class);
        Array.from(menuElements).forEach(menuElement => {
            menuElement.addEventListener('click', function() {
                // 獲取當前完整的 URL 路徑
                const currentPath = window.location.pathname;
                // 找到 "/uefa2024" 及其後面的所有內容
                const newPath = currentPath.match(/\/uefa2024(\/.*)?$/);
                // 計算新的 URL
                const newUrl = setting.baseUrl + (newPath ? newPath[1] : '');
                // 跳轉到新的 URL
                window.location.href = newUrl;
            });
        });
    });
});


</script>
        <?php
    }
}
add_action('wp_footer', 'language_switcher_scripts');
