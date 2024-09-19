jQuery(document).ready(function($) {

	// 如果網址有 key 參數，而且 key 有效，就顯示更改密碼的表單
	var urlParams = new URLSearchParams(window.location.search);
	var key = urlParams.get('key');
	if (key) {
        // 延後一秒後執行
        setTimeout(function() {
            jQuery('#key').val(key);
        }, 1000);
        
		// 檢查 key 是否有效
		jQuery.ajax({
		url: '/bvg/api.php', // API 的 URL
		type: 'POST',
		dataType: 'json',
		data: {
			act: 'check_key',
			key: key
		},
		success: function(Jdata) {
			// 解析響應
			if (Jdata.message === 'done') {
                jQuery('#forgot_password_form').hide();
				jQuery('#forgot_password_key').hide();
				jQuery('#forgot_password_change').show();
                jQuery('.password-reset-info').hide();
                
			} else if (Jdata.message === 'no_key') {
				alert('Kesalahan kode verifikasi');
			} else {
				alert('Terjadi kesalahan, coba lagi nanti.');
			}
		},
		error: function() {
			// 處理錯誤
			alert('Terjadi kesalahan, coba lagi nanti.');
		}
	});
	}



    // 函數：提交忘記密碼表單
    function submitForgotPasswordForm() {
        // 獲取 reCAPTCHA token
        grecaptcha.ready(function() {
            grecaptcha.execute('6LdPTJcpAAAAAE8x28uDUOzV_Is3boVMcCMG_zj_', { action: 'submit' }).then(function(token) {
                // 將 token 添加到隱藏的 input 欄位
                jQuery('#recaptchaResponse').val(token);

                // 獲取電子郵件地址
                var email = jQuery('#email').val();

                // 使用 AJAX 發送數據
                jQuery.ajax({
                    url: '/bvg/api.php', // API 的 URL
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        act: 'forgetpassword',
                        email: email,
                        recaptcha_response: token // 將 reCAPTCHA token 發送到後端
                    },
                    success: function(Jdata) {
                        // 解析響應
                        if (Jdata.message === 'done') {
                            alert('Email pengaturan ulang kata sandi telah dikirim, silakan periksa kotak masuk Anda.'); // 或其他成功消息的處理
                            // 跳轉到登錄頁面
                            //window.location.href = '/login';
                            jQuery('#forgot_password_form').hide();
                            jQuery('#forgot_password_key').show();
                        } else if (Jdata.message === 'no_user') {
                            alert('Email ini tidak terdaftar.'); // 該Email未註冊。
                        } else if (Jdata.message === 'no_email') {
                            alert('Silakan isi email Anda.'); // 請填寫Email。
                        } else {
                            alert('Terjadi kesalahan, coba lagi nanti.');
                        }
                    },
                    error: function() {
                        // 處理錯誤
                        alert('Terjadi kesalahan, coba lagi nanti.');
                    }
                });
            });
        });
    }

    // 監聽按鈕點擊事件
    jQuery('#forgot_password_form .elementor-button').click(function(e) {
        e.preventDefault(); // 防止表單默認提交
        submitForgotPasswordForm(); // 調用上面定義的函數
    });
});


function check_key() {
	var key = jQuery('#key').val();
	jQuery.ajax({
		url: '/bvg/api.php', // API 的 URL
		type: 'POST',
		dataType: 'json',
		data: {
			act: 'check_key',
			key: key
		},
		success: function(Jdata) {
			// 解析響應
			if (Jdata.message === 'done') {
				jQuery('#forgot_password_key').hide();
				jQuery('#forgot_password_change').show();
			} else if (Jdata.message === 'no_key') {
				alert('Kesalahan kode verifikasi');
			} else {
				alert('Terjadi kesalahan, coba lagi nanti.');
			}
		},
		error: function() {
			// 處理錯誤
			alert('Terjadi kesalahan, coba lagi nanti.');
		}
	});

}

function password_change() {
	var password = jQuery('#password').val();
	var password2 = jQuery('#password2').val();

	// 檢查密碼是否有填寫與是否一致
	if (password === '' || password2 === '') {
		alert('Silakan isi kata sandinya'); // 請填寫密碼
		return;
	}
	if (password !== password2) {
		alert('Kata sandi tidak konsisten'); // 密碼不一致
		return;
	}

	jQuery.ajax({
		url: '/bvg/api.php', // API 的 URL
		type: 'POST',
		dataType: 'json',
		data: {
			act: 'password_change',
			key: jQuery('#key').val(),
			password: password
		},
		success: function(Jdata) {
			// 解析響應
			if (Jdata.message === 'done') {
				alert('Kata sandi diubah'); // 密碼已更改
				window.location.href = '/login';
			} else {
				alert('Terjadi kesalahan, coba lagi nanti.');
			}
		},
		error: function() {
			// 處理錯誤
			alert('Terjadi kesalahan, coba lagi nanti.');
		}
	});
}