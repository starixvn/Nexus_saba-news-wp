jQuery(document).ready(function($) {
  // 定義註冊用戶函數
  function registerUser(recaptchaToken) {
      // 停用註冊按鈕，避免用戶重複點擊
      jQuery('#register_btn').prop('disabled', true);


  console.log('registerUser called');
  var first_name = jQuery('#first_name').val();
  var last_name = jQuery('#last_name').val();
  var phone = jQuery('#phone').val();
  var email = jQuery('#email').val();
  var password = jQuery('#password').val();
  var passwordCheck = jQuery('#password2').val();
  var gender = jQuery('#gender').val();
    

    if (!last_name ) {
      alert('Silakan isi: Nama belakang'); // 提示用戶填寫姓氏
      jQuery('#register_btn').prop('disabled', false);
      return;
    }
    if (!first_name ) {
      alert('Silakan isi: nama'); //请填写：名字
      jQuery('#register_btn').prop('disabled', false);
      return;
    }
      if (!phone ) {
          alert('Silakan isi: nomor telepon'); // 请填写：电话
          jQuery('#register_btn').prop('disabled', false);
          return;
      }

    // 進行基本的輸入驗證
    if (!email || !/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
      alert('Tolong masukkan email yang benar.。'); // 请输入有效的Email。
      jQuery('#register_btn').prop('disabled', false);
      return;
    }
    if (!password || password !== passwordCheck) {
      alert('Kata sandi tidak diisi atau kedua kata sandi tidak konsisten.'); // 密码未填或两次密码不一致。
      jQuery('#register_btn').prop('disabled', false);
      return;
    }

    if(!gender){
      alert('Silakan pilih jenis kelamin'); // 请选择性别
      jQuery('#register_btn').prop('disabled', false);
      return;
    }

    // 如果有選擇喜愛的體育競賽，則獲取選擇的值
      var sports = [];
      jQuery('input[name="sports[]"]:checked').each(function() {
          sports.push(jQuery(this).val());
      });
      if(sports.length === 0){
        alert('Silakan pilih setidaknya satu olahraga');
        jQuery('#register_btn').prop('disabled', false);
        return;
    }

    

    // 準備表單數據
    var formData = new FormData();
    formData.append('act', 'register');
    formData.append('first_name', first_name); 
    formData.append('last_name', last_name);
    formData.append('phone', phone);
    formData.append('email', email);
    formData.append('password', password);
    formData.append('sports', sports.join(',')); // 將選擇的體育競賽值用逗號分隔
    formData.append('gender', gender);
    formData.append('recaptcha_response', recaptchaToken); // 添加 reCAPTCHA 驗證 token


    // 通過 AJAX 提交表單數據
    $.ajax({
      url: '/bvg/api.php',
      type: 'POST',
      data: formData,
      contentType: false, // 關閉 jQuery 的自動設置 Content-Type
      processData: false, // 關閉 jQuery 的自動處理數據功能
      dataType: 'json',
      success: function(Jdata) {

        if (Jdata.status === 'success') {
          // 註冊成功，跳轉到登錄頁面
          window.location.href = '/sign-up-success/';
        } else {
          alert(Jdata.message);
          // 啟用註冊按鈕
          jQuery('#register_btn').prop('disabled', false);
        }
      },
      error: function() {
        alert('pendaftaran gagal, silakan coba lagi nanti.'); // 注册失败，请稍后再试。
        // 啟用註冊按鈕
        jQuery('#register_btn').prop('disabled', false);
      }
    });
  }

  // 處理註冊按鈕點擊
  jQuery('#register_btn').click(function() {
      console.log('register button clicked');
    grecaptcha.ready(function() {
      grecaptcha.execute('6LdPTJcpAAAAAE8x28uDUOzV_Is3boVMcCMG_zj_', { action: 'submit' }).then(function(recaptchaToken) {
        // 使用 reCAPTCHA token 進行用戶註冊
        registerUser(recaptchaToken);
      });
    });
  });
});