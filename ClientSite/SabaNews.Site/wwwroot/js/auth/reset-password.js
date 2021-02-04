var authReset = (function ($) {
    function HandleResetPassword() {
        const element = 'form#form-reset-password';
        $(element).validate({
            rules: {
                password: {
                    required: true,
                    minlength: 6
                },
                confirmPassword: {
                    required: true,
                    equalTo: '#password'
                }
            },
            messages: {
                password: {
                    required: 'Hãy nhập mật khẩu',
                    minlength: 'Mật khẩu ít nhất 6 kí tự'
                },
                confirmPassword: {
                    required: 'Hãy xác thực mật khẩu',
                    equalTo: 'Xác thực mật khẩu sai'
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: '/Auth/ResetPassword',
                    method: "POST",
                    data: $(form).serialize(),
                    success: function (res) {
                        if (res.isSuccessful) {
                            let bodyModal = '<h3>Đổi mật khẩu thành công</h3>';
                            $('.reset-page').html(bodyModal);
                        } else {
                            $(form).find('.summary-eror').text(res.message);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ': ' + errorThrown);
                    }
                });
            }
        });
    }

    function init() {
        HandleResetPassword();
    }

    return {
        init
    }

})(jQuery);

$(document).ready(function () {
    authReset.init();
})