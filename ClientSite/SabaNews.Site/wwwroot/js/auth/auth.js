var auth = (function ($) {
    function HandleAuth() {
        $('.auth-input .auth-login').on('click', function () {
            $.ajax({
                url: '/Auth/Index',
                type: 'GET',
                dataType: 'html',
                success: function (response) {
                    $('#auth-form').html(response);
                    $('#authModal').modal('show');
                    AddEmailValidation();
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
                    validEmail: true
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
                    url: '/Auth/Login',
                    method: "POST",
                    data: $(form).serialize(),
                    success: function (res) {
                        if (res.isSuccessful) {
                            location.reload();
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

    function HandleRegister() {
        const element = '#authModal form#form-register';
        $(element).validate({
            rules: {
                name: "required",
                email: {
                    required: true,
                    validEmail: true
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
                    url: '/Auth/Register',
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
                            HandleCloseModal();
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

    function HandleCloseModal() {
        $('#authModal').on('hidden.bs.modal', function () {
            location.reload();
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
        $(element).validate({
            rules: {
                email: {
                    required: true,
                    validEmail: true
                }
            },
            messages: {
                email: {
                    required: 'Hãy nhập email',
                    email: 'Email không có thật'
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: '/Auth/ForgotPassword',
                    method: "POST",
                    data: $(form).serialize(),
                    success: function (res) {
                        if (res.isSuccessful) {
                            const email = $(form).find('input[name="email"]').val();
                            let bodyModal = `<div>Hãy kiểm tra email <b>${email}</b></div>
                                <div>Để xem hướng dẫn lấy lại mật khẩu.</div>
                                <div>Nhớ kiểm tra thư rác nhé</div>`;
                            $('#authModal .modal-body').html(bodyModal);
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

    function HandleLogout() {
        $('.auth-input .auth-logout').on('click', function () {
            $.ajax({
                url: '/Auth/Logout',
                type: 'GET',
                dataType: 'html',
                success: function (response) {
                    location.reload();
                }
            });
        });
    }

    function AddEmailValidation() {
        $.validator.addMethod("validEmail", function (value, element) {
            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            return emailReg.test(value);
        }, 'Email không có thật');
    }

    function init() {
        HandleAuth();
        HandleLogout();
    }

    return {
        init
    }

})(jQuery);

$(document).ready(function () {
    auth.init();
})