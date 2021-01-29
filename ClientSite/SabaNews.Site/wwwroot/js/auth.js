var auth = (function ($) {
    function HandleAuth() {
        $('.auth-input .icon-auth').on('click', function () {
            $.ajax({
                url: '/Auth/Index',
                type: 'GET',
                dataType: 'html',
                success: function (response) {
                    $('#auth-form').html(response);
                    $('#authModal').modal('show');
                    HandleLogin();
                    HandleRegister();
                    HandleForgotPassword();
                }
            });
        });
    }

    function HandleLogin() {
        const element = '#authModal form#form-login';

        $(element).validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: 'required'
            },
            messages: {
                email: {
                    required: 'Hãy nhập email',
                    email: 'Email không có thật'
                },
                password: 'Hãy nhập mật khẩu'
            },
            submitHandler: function (form) {
                $.ajax({
                    url: 'Auth/Login',
                    method: "POST",
                    data: $(form).serialize(),
                    success: function (res) {
                        if (res.isSuccessful) {
                            $('#authModal').modal('hide');
                        } else {
                            // show error res.Message
                            console.log(res);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ': ' + errorThrown);
                    }
                });
            }
        });
    }

    function HandleRegister() {
        const element = '#authModal form#form-register';
        $(element).validate({
            rules: {
                name: "required",
                email: {
                    required: true,
                    email: true
                },
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
                name: 'Hãy nhập tên',
                email: {
                    required: 'Hãy nhập email',
                    email: 'Email không có thật'
                },
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
                    url: 'Auth/Register',
                    method: "POST",
                    data: $(form).serialize(),
                    success: function (res) {
                        if (res.isSuccessful) {
                            const email = $(form).find('input[name="email"]').val();
                            let bodyModal = `<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                                <h3>Đăng ký thành công</h3>
                                <div>Hãy kiểm tra email <b>${email}</b> để xác thực email.</div>
                                <div>Nhớ kiểm tra thư rác nhé</div>`;
                            $('#authModal .modal-body').html(bodyModal);
                        } else {
                            // show error res.Message
                            console.log(res);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ': ' + errorThrown);
                    }
                });
            }
        });
    }

    function HandleForgotPassword() {
        $('#forgot-password').on('click', function () {
            $.ajax({
                url: '/Auth/ForgotPassword',
                type: 'GET',
                dataType: 'html',
                success: function (response) {
                    $('#authModal').html(response);
                    HandleSubmitForgotPassword();
                }
            });
        });
    }

    function HandleSubmitForgotPassword() {
        const element = '#authModal form#form-forgot-password';
        $(element).on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: 'Auth/ForgotPassword',
                method: "POST",
                data: $(element).serialize(),
                success: function (res) {
                    $('#authModal').modal('hide');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                }
            });
        });
    }

    function init() {
        HandleAuth();
    }

    return {
        init
    }

})(jQuery);

$(document).ready(function () {
    auth.init();
})