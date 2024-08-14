var onloadCallback = function() {
    grecaptcha.render('register-captcha', {
      'sitekey' : '6Lc_0iIqAAAAABwLiZGn7mLKk955WasDv7nf4sT4'
    });

    grecaptcha.render('login-captcha', {
        'sitekey' : '6Lc_0iIqAAAAABwLiZGn7mLKk955WasDv7nf4sT4'
      });
  };

var auth = (function ($) {
    var mailFormat = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    var passwordFormat = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$@!%&*?])[A-Za-z\d#$@!%&*?]{8,30}$/;
    const EmailEmptyErr = "Hãy nhập email";
    const EmailIsValidErr = "Email không hợp lệ";
    const EmailExistedErr = "Email này đã tồn tại";
    const NameEmptyErr = "Hãy nhập tên";
    const PasswordEmptyErr = "Hãy nhập mật khẩu";
    const PasswordIsvalidErr = "Mật khẩu phải dài ít nhất 8 ký tự và bao gồm chữ cái viết hoa, số và ký tự đặc biệt";
    const ConfirmPasswordEmptyErr = "Hãy xác thực mật khẩu";
    const ConfirmPasswordIsvalidErr = "Mật khẩu không trùng khớp";
    const PhoneEmptyErr = "Hãy nhập số điện thoại";
    const PhoneIsvalidErr = "Số điện thoại không hợp lệ";
    var modal = "#modal-login";
    var returnUrl = "";

    function GetModalLogin(Url, openForm) {
        returnUrl = Url;
        $('.authModal').modal('hide');
        modal = openForm == 'login' ? '#modal-login' : '#modal-register';
        $('.summary-eror').hide();
        $(modal).modal('show');
    }

    function HandleAuth() {
        $(document).on('click', '.auth-input .auth-login', function (e) {
            e.preventDefault();
            returnUrl = $(this).data('returnurl');
            GetModalLogin(returnUrl, 'login');
            $('.summary-eror').hide();
        });
        $(document).on('click', '.register-form-open', function (e) {
            e.preventDefault();
            returnUrl = $(this).data('returnurl');
            GetModalLogin(returnUrl, 'register');
        })

    }

    function HandleValidate() {
        $('input[name=email]').on('keyup', function () {
            let email = $(this).val();
            let err = GetErrorElement($(this));
            if (email === "") {
                SetErrorElement(err, EmailEmptyErr)
            }
        });

        $('#form-register input[name=phone]').on('keypress', function (e) {
            let val = $(this).val();
            $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
            if ((e.which != 46 || val.indexOf('.') != -1 || val > max) && (e.which < 48 || e.which > 57)) {
                e.preventDefault();
            }
        });

        $('#form-register input[name=phone]').on('keyup', function () {
            let err = GetErrorElement($(this));
            let val = $(this).val();
            if (val === "") {
                let message = PhoneEmptyErr;
                SetErrorElement(err, message)
            }
        });


        $('#form-login input[name=password]').on('keyup', function () {
            let err = GetErrorElement($(this));
            let val = $(this).val();
            if (val === "") {
                let message = PasswordEmptyErr;
                SetErrorElement(err, message)
            }
        });

        $('#form-register input[name=name]').on('keyup', function () {
            let err = GetErrorElement($(this));
            let val = $(this).val();
            if (val === "") {
                let message = NameEmptyErr;
                SetErrorElement(err, message)
            }
        });

        $('#form-register input[name=password]').on('keyup', function () {
            let err = GetErrorElement($(this));
            let val = $(this).val();
            var resetPassword = $('#form-register input[name=confirmPassword]').val();
            var message = "";
            if (val === "") {
                message = PasswordEmptyErr;
            }
            if (message !== "") {
                SetErrorElement(err, message)
            }
        });

        $('#form-register input[name=confirmPassword]').on('keyup', function () {
            let err = GetErrorElement($(this));
            let val = $(this).val();
            let password = $('#form-register .password-group').find('input[name=password]').val();
            let message = "";
            if (val === "") {
                message = ConfirmPasswordEmptyErr;
            }
            if (message !== "") {
                SetErrorElement(err, message);
            }
        });

        function GetErrorElement(self) {
            let err = self.parent().parent();
            err.removeClass('error');
            err.find('.validate-error').html('');
            return err;
        }

        function SetErrorElement(err, mess) {
            err.addClass('error');
            err.find('.validate-error').html(mess);
        }
    }


    function HandleLogin() {
        $('#form-login .submit-login').on('click', function (e) {
            e.preventDefault();
            var email = $("#form-login  input[name=email]").val();
            var password = $("#form-login  input[name=password]").val();
            var isValid = true;
            console.log(grecaptcha);
            $("#form-login .form-group-auth").removeClass('error');
            $("#form-login .validate-error").html('');
            var self = $(this);

            if (!mailFormat.test(email) || email === "") {
                isValid = false
                var message = email === "" ? EmailEmptyErr : EmailIsValidErr;
                $("#form-login .email .validate-error").html(message);
                $("#form-login .email").addClass("error");
            }

            if (password === "" && $("#password-error").length == 0) {
                isValid = false
                $("#form-login .password .validate-error").html(PasswordEmptyErr);
                $("#form-login .password").addClass("error");
            }
            if (!isValid) {
                return false;
            }
            self.prop('disabled', true);

            $.ajax({
                url: '/Auth/Login',
                method: "POST",
                data: { email, password },
                success: function (res) {
                    if (res.isSuccessful) {
                        if (typeof returnUrl === "undefined") {
                            location.reload();
                        } else {
                            location.href = returnUrl;
                        }
                    }
                    else {
                        $('.summary-eror').show();
                        $('.summary-eror').text(res.message);
                        self.prop('disabled', false);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                }
            });
        })
    }

    function HandleRegister() {
        $('#form-register .submit-register').on('click', function () {
            var name = $("#form-register input[name=name]").val();
            var email = $("#form-register input[name=email]").val();
            var password = $("#form-register input[name=password]").val();
            var confirmPassword = $("#form-register input[name=confirmPassword]").val();
            var yearOfBirth = $('#form-register select[name=yearOfBirth]').val();
            var phone = $('#form-register input[name=phone]').val();
            var captcha = grecaptcha.getResponse();
            var self = $(this);
            let isValid = true;


            $('#form-register .form-group-auth').removeClass('error');
            $('#form-register  .validate-error').html('');
            if (name === "") {
                isValid = false;
                ShowErrHandle($("#form-register .name-group"), NameEmptyErr);
            }

            if (phone === '') {
                isValid = false;
                ShowErrHandle($("#form-register .phone-group"), PhoneEmptyErr);
            } else if (!phone.startsWith("0") || phone.length != 10) {
                isValid = false;
                ShowErrHandle($("#form-register .phone-group"), PhoneIsvalidErr);
            }

            if (!mailFormat.test(email) || email === "") {
                isValid = false;
                var msg = email === "" ? EmailEmptyErr : EmailIsValidErr;
                ShowErrHandle($("#form-register .email-group"), msg);
            }

            if (password === "" || !passwordFormat.test(password)) {
                isValid = false;
                var msg = password == "" ? PasswordEmptyErr : PasswordIsvalidErr;
                ShowErrHandle($("#form-register .password-group"), msg);
            }
            if (confirmPassword === "" || password != confirmPassword) {
                isValid = false;
                var msg = confirmPassword === "" ? ConfirmPasswordEmptyErr : ConfirmPasswordIsvalidErr;
                ShowErrHandle($("#form-register .confirm-password-group"), msg);
            }
            if(captcha == "") {
                isValid = false;
                $('.captcha .validate-error').html('xác nhận captcha');
            }
            if (isValid) {
                self.prop('disabled', true);
                self.addClass('btn-disabled');
                $.ajax({
                    url: '/Auth/Register',
                    method: "POST",
                    data: { name, phone, yearOfBirth, email, password, confirmPassword },
                    success: function (res) {
                        if (res.isSuccessful) {
                            const html = _htmlResource.ButtonCloseModal + _htmlResource.RegisterSuccessfully + _htmlResource.RemindCheckMail;
                            const bodyModal = formatString(html, email, window.location.origin);
                            $(modal).css("max-width", "63rem");
                            $(modal + ' .modal-body').html(bodyModal);
                            HandleCloseModal();
                        } else {
                            $('.summary-eror').show();
                            $('.summary-eror').text(res.message);
                            self.prop('disabled', false);
                            self.removeClass('btn-disabled');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ': ' + errorThrown);
                    }
                });
            }
        })

        function ShowErrHandle(group, mess) {
            group.addClass('error');
            group.find('.validate-error').html(mess);
        }

    }

    function HandleCloseModal() {
        $('.authModal').on('hidden.bs.modal', function () {
            location.reload();
        });
    }

    function HandleForgotPassword() {
        $('#forgot-password').on('click', function (e) {
            e.preventDefault();
            $('.authModal').modal('hide');
            $('#modal-forgotpassword').modal('show');
            console.log(123);
            $("#form-forgot-password  .btn-resetpass").on('click', (e) => {
                e.preventDefault();
                HandleSubmitForgotPassword();
            });
        });
    }

    function HandleSubmitForgotPassword() {
        var email = $("#form-forgot-password input[name=email]").val();
        var isValid = true
        if (!mailFormat.test(email) || email == "") {
            isValid = false;
            var msg = email === "" ? EmailEmptyErr : EmailIsValidErr;
            $('#form-forgot-password .forgot-email').addClass('error');
            $('#form-forgot-password .validate-error').html(msg);
        }

        if(!isValid) {
            return false;
        }
        $("#form-forgot-password .btn-resetpass").prop('disabled', true);
        $("#form-forgot-password .btn-resetpass").addClass('btn-disabled');
        $.ajax({
            url: '/Auth/ForgotPassword',
            method: "POST",
            data: { email },
            success: function (res) {
                if (res.isSuccessful) {
                    const html = _htmlResource.ForgotPassword + _htmlResource.RemindCheckMail;
                    const bodyModal = formatString(html, email, window.location.origin);
                    $('#modal-forgotpassword .modal-body').html(bodyModal);
                } else {
                    $('.summary-eror').show();
                    $('.summary-eror').text(res.message);
                    $("#modal-forgotpassword .btn-resetpass").prop('disabled', false);
                    $("#modal-forgotpassword .btn-resetpass").removeClass('btn-disabled');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus + ': ' + errorThrown);
            }
        });
    }

    function HandleResendConfirmEmail() {
        $('#resend-email').on('click', function () {
            $.ajax({
                url: '/Auth/SendEmailConfirm',
                type: "GET",
                dataType: 'html',
                success: function (response) {
                    $(modal).html(response);
                    HandleSubmitConfirmEmail();
                }
            });
        });
    }

    function HandleSubmitConfirmEmail() {
        $("#form-confirm-email button").on('click', (e) => {
            e.preventDefault();
            var email = $('#form-confirm-email input[name="email"]').val();
            if (!mailFormat.test(email) || email == '') {
                var err_message = email == '' ? EmailIsValidErr : EmailExistedErr;
                $(this).parent().find('.error').text(err_message);
                return false;
            }
            var self = $(this);
            self.prop('disabled', true);
            self.addClass('btn-disabled');

            $.ajax({
                url: '/Auth/SendEmailConfirm',
                method: 'POST',
                data: { email },
                success: function (res) {
                    if (res.isSuccessful) {
                        const html = "Chúng tôi đã gửi email đến địa chỉ <b>{0}</b> để xác thực và kích hoạt tài khoản.</div>',";
                        const bodyModal = formatString(html, email);
                        $(modal + ' .modal-body').html(bodyModal);
                    } else {
                        $(modal +  ' form#form-confirm-email').find('.summary-eror').text(res.message);
                        self.prop('disabled', false);
                        self.removeClass('btn-disabled');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                }
            });
        });
    }

    function HandleModal(openForm) {
        if (openForm === 'register') {
            $('.nav-title').removeClass('active');
            $('#nav-register-tab').addClass('active');
            $('#nav-register').addClass('active');
            $('#nav-login').removeClass('active')
        } else {
            $('.nav-title').removeClass('active');
            $('#nav-register').removeClass('active');
            $('#nav-login').addClass('active')
            $('#nav-login-tab').addClass('active');
        }
        $('.nav-title').on('click', function () {
            var self = $(this);
            if (!self.hasClass('active')) {
                $('.nav-title').removeClass('active');
                $(this).addClass('active');
                if (self.attr('id') === 'nav-login-tab') {
                    $('#nav-login').addClass('active')
                    $('#nav-register').removeClass('active')
                } else {
                    $('#nav-register').addClass('active')
                    $('#nav-login').removeClass('active')
                }
            }
        });
    }

    $('.select-auth').on('change' , function () {
        $(this).addClass('change-color');
        if($(this).val()){
            $(this).addClass('change-color');
        }else{
            $(this).removeClass('change-color');
        }
    })


    function init() {
        HandleAuth();
        HandleLogin();
        HandleRegister();
        HandleValidate();
        HandleForgotPassword();
        HandleResendConfirmEmail();
        HandleModal();
    }

    return {
        init,
        GetModalLogin
    }
})(jQuery);

$(document).ready(function () {
    auth.init();
})

