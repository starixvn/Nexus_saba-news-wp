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
    const ConfirmPasswordIsvalidErr = "Mật khẩu không hợp lệ";
    const PhoneEmptyErr = "Hãy nhập số điện thoại";
    const PhoneIsvalidErr = "Số điện thoại không hợp lệ";
    function GetModalLogin(returnUrl, openForm) {
        $.ajax({
            url: '/Auth/Index',
            type: 'GET',
            dataType: 'html',
            success: function (response) {
                $('#auth-form').html(response);
                $('#authModal').modal('show');

                document.querySelector("#form-login > div:nth-child(2) > input").addEventListener('blur', (e) => {
                    e.preventDefault();
                    AddEmailValidation();
                });

                document.querySelector("#form-login > div:nth-child(3) > input").addEventListener('blur', (e) => {
                    e.preventDefault();
                    AddPasswordValidation();
                });

                document.querySelector("#form-login > button").addEventListener('click', (e) => {
                    e.preventDefault();
                    HandleLoginAcc(returnUrl);
                });
                
                document.querySelector("#form-register > button").addEventListener('click', (e) => {
                    e.preventDefault();
                    HandleRegister();
                });
                HandleValidate();
                HandleForgotPassword();
                HandleConfirmEmail();
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

    function HandleValidate() {
        $('input[name=email]').on('keyup', function(){
            let err = $(this).parent().find("#email-error");
            if(err.length === 0) return false;
            let email = $(this).val();
            err.hide();
            if(!mailFormat.test(email) || email === "") {
                let message = email ==="" ? EmailEmptyErr : EmailIsValidErr
                err.html(message)
                err.show();
            }
        });

        $('#form-register input[name=phone]').on('keypress', function (e) {
            let val = $(this).val();
            $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
            if ((e.which != 46 || val.indexOf('.') != -1 || val > max) && (e.which < 48 || e.which > 57)) {
                e.preventDefault();
            }
        });

        $('#form-register input[name=phone]').on('keyup', function() {
            let err = $(this).parent().find("#phone-error");
            let val = $(this).val();
            err.html('');
            if(val === "") {
                let message =  PhoneEmptyErr;
                err.html(message)
            }
        });


        $('#form-login input[name=password]').on('keyup', function(){
            let err = $(this).parent().find("#password-error");
            if(err.length === 0) return false;
            let val = $(this).val();
            err.hide();
            if(val === "") {
                let message = PasswordEmptyErr;
                err.html(message)
                err.show();
            }
        });

        $('#form-register input[name=name]').on('keyup', function(){
            let err = $(this).parent().find("#name-error");
            if(err.length === 0) return false;
            let val = $(this).val();
            err.hide();
            if(val === "") {
                let message = NameEmptyErr;
                err.html(message)
                err.show();
            }
        });

        $('#form-register input[name=password]').on('keyup', function(){
            let err = $(this).parent().find("#password-error");
            if(err.length === 0) return false;
            let val = $(this).val();
            err.hide();
            let message = "";
            if(val === "") {
                message = PasswordEmptyErr;
            } else if(!passwordFormat.test(val)) {
                message = PasswordIsvalidErr;
            }
            if(message !== "") {
                err.html(message)
                err.show();
            }
        });

        $('#form-register input[name=confirmPassword]').on('keyup', function(){
            let err = $(this).parent().find("#confirmPassword-error");
            if(err.length === 0) return false;
            let val = $(this).val();
            let password = $(this).parent().parent().find('input[name=password]').val();
            let message = "";
            err.hide();
            if(val === "") {
                message = ConfirmPasswordEmptyErr;
            } else if(password !== val) {
                message = ConfirmPasswordIsvalidErr;
            }
            if(message !== "") {
                err.html(message)
                err.show();
            }
        });
    }


    function HandleLoginAcc(returnUrl) {
        var email = document.querySelector("#form-login > div:nth-child(2) > input").value;
        var password = document.querySelector("#form-login > div:nth-child(3) > input").value;

        $("#email-error").remove();
        $("#password-error").remove();

        if (!mailFormat.test(email) || email === ""){
            flag = false;
            var aTag = document.createElement("label");
            aTag.id = "email-error";
            aTag.htmlFor = "email";
            aTag.classList.add("error");
            aTag.innerHTML = email === "" ? EmailEmptyErr : EmailIsValidErr;
            document.querySelector("#form-login > div:nth-child(2)").append(aTag);
        }

        var check = $("#password-error").length;
        if (password === "" && check == 0){
            var aTag = document.createElement("label");
            aTag.id = "password-error";
            aTag.htmlFor = "password";
            aTag.classList.add("error");
            aTag.innerHTML = PasswordEmptyErr;
            document.querySelector("#form-login > div:nth-child(3)").append(aTag);
        }

        if(mailFormat.test(email) && email != "" && password != ""){
            $.ajax({
                url: '/Auth/Login',
                method: "POST",
                data: {email, password},
                success: function (res) {
                    if (res.isSuccessful) {
                        if (typeof returnUrl === "undefined") {
                            location.reload();
                        } else {
                            location.href = returnUrl;
                        }
                    }
                    else{
                        $('#authModal form#form-login').find('.summary-eror').text(res.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                }
            });
        }
    }

    function HandleRegister() {
        var name = $("#form-register input[name=name]").val();
        var email = $("#form-register input[name=email]").val();
        var password = $("#form-register input[name=password]").val();
        var confirmPassword = $("#form-register input[name=confirmPassword]").val();
        var yearOfBirth = $('#form-register select[name=yearOfBirth]').val();
        var phone = $('#form-register input[name=phone]').val();
        
        let isValid = true;

        $('.error').html('');
        if(name === ""){
            isValid = false;
            $("#form-register #name-error").text(NameEmptyErr);
        }

        if(phone === '') {
            isValid = false;
            $("#form-register #phone-error").text(PhoneEmptyErr);
        }
        if(!phone.startsWith("0") || phone.length != 10 ) {
            $("#form-register #phone-error").text(PhoneIsvalidErr);
        }

        if (!mailFormat.test(email) || email === ""){
            isValid = false;
            var msg = email === "" ? EmailEmptyErr : EmailIsValidErr;
            $("#form-register #email-error").text(msg);
        }

        if (password === "" || !passwordFormat.test(password)){
            isValid = false;
            var msg = password == "" ? PasswordEmptyErr : PasswordIsvalidErr;
            $("#form-register #password-error").text(msg);
        }

        if (confirmPassword === "" || password != confirmPassword){
            isValid = false;
            var msg = confirmPassword === "" ? ConfirmPasswordEmptyErr : ConfirmPasswordIsvalidErr;
            $("#form-register #confirmPassword-error").text(msg);
        }
        if(isValid){
            $('#authModal form#form-register' + " .btn-register").prop('disabled', true);
            $('#authModal form#form-register' + " .btn-register").addClass('btn-disabled');
            $.ajax({
                url: '/Auth/Register',
                method: "POST",
                data: {name, phone, yearOfBirth, email, password, confirmPassword},
                success: function (res) {
                    if (res.isSuccessful) {
                        const email = $('#authModal form#form-register').find('input[name="email"]').val();
                        const html = _htmlResource.ButtonCloseModal + _htmlResource.RegisterSuccessfully + _htmlResource.RemindCheckMail;
                        const bodyModal = formatString(html, email, window.location.origin);
                        $('#authModal').css("max-width", "63rem");
                        $('#authModal .modal-body').html(bodyModal);
                        HandleCloseModal();
                    } else {
                        $('#authModal form#form-register').find('.summary-eror').text(res.message);
                        $('#authModal form#form-register' + " .btn-register").prop('disabled', false);
                        $('#authModal form#form-register' + " .btn-register").removeClass('btn-disabled');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                }
            });
        }
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
                    document.querySelector("#form-forgot-password > button").addEventListener('click', (e) => {
                        e.preventDefault();
                        HandleSubmitForgotPassword();
                    });
                }
            });
        });
    }

    function HandleSubmitForgotPassword() {
        var email = document.querySelector("#form-forgot-password > div > input").value;
        if (!mailFormat.test(email) || email === ""){
            $("#email-error").remove();
            flag = false;
            var aTag = document.createElement("label");
            aTag.id = "email-error";
            aTag.htmlFor = "email";
            aTag.classList.add("error");
            aTag.innerHTML = email === "" ? EmailEmptyErr : EmailIsValidErr;
            document.querySelector("#form-forgot-password > div").append(aTag);
        }

        if(mailFormat.test(email) && email != ""){
            $("#form-forgot-password .btn-resetpass").prop('disabled', true);
            $("#form-forgot-password .btn-resetpass").addClass('btn-disabled');
            $.ajax({
                url: '/Auth/ForgotPassword',
                method: "POST",
                data: {email},
                success: function (res) {
                    if (res.isSuccessful) {
                        const emailForget = $('#authModal form#form-forgot-password').find('input[name="email"]').val();
                        const html = _htmlResource.ForgotPassword + _htmlResource.RemindCheckMail;
                        const bodyModal = formatString(html, emailForget, window.location.origin);
                        $('#authModal .modal-body').html(bodyModal);
                    } else {
                        $('#authModal form#form-forgot-password').find('.summary-eror').text(res.message);
                        $("#form-forgot-password .btn-resetpass").prop('disabled', false);
                        $("#form-forgot-password .btn-resetpass").removeClass('btn-disabled');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                }
            });
        }
    }

    function HandleConfirmEmail() {
        $('#confirm-email').on('click', function () {
            $.ajax({
                url: '/Auth/SendEmailConfirm',
                type: "GET",
                dataType: 'html',
                success: function (response) {
                    $('#authModal').html(response);
                    HandleSubmitConfirmEmail();
                }
            });
        });
    }

    function HandleSubmitConfirmEmail() {
        $("#form-confirm-email button").on('click', (e) => {
            e.preventDefault();
            var email = $('#form-confirm-email input[name="email"]').val();
            if(!mailFormat.test(email) || email == '') {
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
                        $('#authModal .modal-body').html(bodyModal);
                    } else {
                        $('#authModal form#form-confirm-email').find('.summary-eror').text(res.message);
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

    function AddEmailValidation() {
        var email = document.querySelector("#form-login > div:nth-child(2) > input").value;
        if (!mailFormat.test(email)){
            $("#email-error").remove();
            flag = false;
            var aTag = document.createElement("label");
            aTag.id = "email-error";
            aTag.htmlFor = "email";
            aTag.classList.add("error");
            aTag.innerHTML = "Email không hợp lệ";
            document.querySelector("#form-login > div:nth-child(2)").append(aTag);
        }
    }

    function AddPasswordValidation() {
        var password = document.querySelector("#form-login > div:nth-child(3) > input").value;
        var check = $("#password-error").length;
        if (password === "" && check === 0){
            $("#password-error").remove();
            var aTag = document.createElement("label");
            aTag.id = "password-error";
            aTag.htmlFor = "password";
            aTag.classList.add("error");
            aTag.innerHTML = "Hãy nhập mật khẩu";
            document.querySelector("#form-login > div:nth-child(3)").append(aTag);
        }
    }

    function init() {
        HandleAuth();
    }

    return {
        init,
        GetModalLogin
    }
})(jQuery);

$(document).ready(function () {
    auth.init();
})