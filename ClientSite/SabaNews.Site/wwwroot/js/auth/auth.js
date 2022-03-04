var auth = (function ($) {
    function GetModalLogin(returnUrl, openForm) {
        $.ajax({
            url: '/Auth/Index',
            type: 'GET',
            dataType: 'html',
            success: function (response) {
                $('#auth-form').html(response);
                $('#authModal').modal('show');
                AddEmailValidation();
                HandleLogin(returnUrl);
                HandleRegister();
                HandleForgotPassword();
                HandleModal(openForm);
            }
        });
    }

    function HandleAuth() {
        $('.auth-input .auth-login').on('click', function () {
            var returnUrl = $(this).data('returnurl');
            var openForm = $(this).data('form');
            GetModalLogin(returnUrl, openForm);
        });
    }

    function HandleLogin(returnUrl) {
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
                    required: _viewResource.EmailRequired,
                    email: _viewResource.EmailInvalid
                },
                password: _viewResource.PasswordRequired
            },
            submitHandler: function (form) {
                $.ajax({
                    url: '/Auth/Login',
                    method: "POST",
                    data: $(form).serialize(),
                    success: function (res) {
                        if (res.isSuccessful) {
                            if (typeof returnUrl === "undefined") {
                                location.reload();
                            } else {
                                location.href = returnUrl;
                            }
                            
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
                name: _viewResource.NameRequired,
                email: {
                    required: _viewResource.EmailRequired,
                    email: _viewResource.EmailInvalid
                },
                password: {
                    required: _viewResource.PasswordRequired,
                    minlength: _viewResource.PasswordMinLength
                },
                confirmPassword: {
                    required: _viewResource.ConfirmPasswordRequired,
                    equalTo: _viewResource.ConfirmPasswordEqualTo
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
                            const html = _htmlResource.ButtonCloseModal + _htmlResource.RegisterSuccessfully + _htmlResource.RemindCheckMail;
                            const bodyModal = formatString(html, email);
                            $('#authModal').css("max-width", "63rem");
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
                    required: _viewResource.EmailRequired,
                    email: _viewResource.EmailInvalid
                }
            },
            submitHandler: function (form) {
                $("#form-forgot-password .btn-resetpass").prop('disabled', true);
                $("#form-forgot-password .btn-resetpass").addClass('btn-disabled');
                $.ajax({
                    url: '/Auth/ForgotPassword',
                    method: "POST",
                    data: $(form).serialize(),
                    success: function (res) {
                        if (res.isSuccessful) {
                            const email = $(form).find('input[name="email"]').val();
                            const html = _htmlResource.ForgotPassword + _htmlResource.RemindCheckMail;
                            const bodyModal = formatString(html, email);
                            $('#authModal .modal-body').html(bodyModal);
                        } else {
                            $(form).find('.summary-eror').text(res.message);
                            $("#form-forgot-password .btn-resetpass").prop('disabled', false);
                            $("#form-forgot-password .btn-resetpass").removeClass('btn-disabled');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ': ' + errorThrown);
                    }
                });
            }
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

    function AddEmailValidation() {
        $.validator.addMethod("validEmail", function (value, element) {
            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            return emailReg.test(value);
        }, _viewResource.EmailInvalid);
    }

    function init() {
        HandleAuth();
    }

    return {
        init,
        GetModalLogin,
        HandleForgotPassword
    }
})(jQuery);

$(document).ready(function () {
    auth.init();
})