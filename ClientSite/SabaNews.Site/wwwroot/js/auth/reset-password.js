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
                    url: '/Auth/ResetPassword',
                    method: "POST",
                    data: $(form).serialize(),
                    success: function (res) {
                        if (res.isSuccessful) {
                            $('.reset-success').show();
                            $('.reset-action').hide();
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