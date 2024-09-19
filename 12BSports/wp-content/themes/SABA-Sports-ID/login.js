jQuery(document).ready(function($) {

    // 如果網址參數有 redirect_to，則將其設為登入後的跳轉頁面
    var urlParams = new URLSearchParams(window.location.search);
    var redirect_to = urlParams.get('redirect_to');
    if (redirect_to) {
        jQuery('#redirect_to').val(redirect_to);
    } else {
        jQuery('#redirect_to').val('/');
    }

    
function login(recaptchaToken) {
    var email = jQuery('#email').val();
    var password = jQuery('#password').val();
    // 進行基本的輸入驗證
    if (!email || !/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
        alert('Tolong masukkan email yang benar.');
        return;
    }
    if (!password) {
        alert('Silakan masukkan kata sandi Anda.');
        return;
    }
    // 準備表單數據
    var formData = new FormData();
    formData.append('act', 'login');
    formData.append('email', email);
    formData.append('password', password);
    formData.append('recaptcha_response', recaptchaToken);
    // 發送登錄請求
    jQuery.ajax({
        url: "/bvg/api.php",
        type: "POST",
        dataType: 'json',
        data: formData,
        processData: false,
        contentType: false,
        success: function (Jdata) {
            if (Jdata.status == 'success') {
                // 登入成功，跳轉到 redirect_to 頁面
                var redirect_to = jQuery('#redirect_to').val();
                window.location.href = redirect_to;
            } else {
                alert('akun atau kata sandi salaht');
            }
        },
        error: function () {
            alert('Gagal masuk. Silakan coba lagi nanti.');
        }
    });
}

// 處理註冊按鈕點擊
jQuery('#login_btn').click(function() {
    console.log('register button clicked');
  grecaptcha.ready(function() {
    grecaptcha.execute('6LdPTJcpAAAAAE8x28uDUOzV_Is3boVMcCMG_zj_', { action: 'submit' }).then(function(token) {
      // 使用 reCAPTCHA token 進行用戶註冊
      login(token);
    });
  });
});

});

