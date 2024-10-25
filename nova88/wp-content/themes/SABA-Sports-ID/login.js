jQuery(document).ready(function($) {

    // Nếu có tham số redirect_to trong URL, đặt nó làm trang chuyển hướng sau khi đăng nhập
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
    // Thực hiện xác thực đầu vào cơ bản
    if (!email || !/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
        alert('Vui lòng nhập một địa chỉ email hợp lệ.');
        return;
    }
    if (!password) {
        alert('Vui lòng nhập mật khẩu của bạn.');
        return;
    }
    // Chuẩn bị dữ liệu biểu mẫu
    var formData = new FormData();
    formData.append('act', 'login');
    formData.append('email', email);
    formData.append('password', password);
    formData.append('recaptcha_response', recaptchaToken);
    // Gửi yêu cầu đăng nhập
    jQuery.ajax({
        url: "/bvg/api.php",
        type: "POST",
        dataType: 'json',
        data: formData,
        processData: false,
        contentType: false,
        success: function (Jdata) {
            if (Jdata.status == 'success') {
                // Đăng nhập thành công, chuyển hướng đến trang redirect_to
                var redirect_to = jQuery('#redirect_to').val();
                window.location.href = redirect_to;
            } else {
                alert('Tài khoản hoặc mật khẩu không đúng');
            }
        },
        error: function () {
            alert('Đăng nhập thất bại. Vui lòng thử lại sau.');
        }
    });
}

// Xử lý khi nhấn nút đăng nhập
jQuery('#login_btn').click(function() {
    console.log('Đã nhấn nút đăng nhập');
  grecaptcha.ready(function() {
    grecaptcha.execute('6LcdoWkqAAAAAAGSJtvUPr2Z2Jq0oAMIj_JsTXXl', { action: 'submit' }).then(function(token) {
      // Sử dụng token reCAPTCHA để đăng nhập người dùng
      login(token);
    });
  });
});

});
