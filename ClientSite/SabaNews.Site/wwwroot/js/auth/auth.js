var auth = (function ($) {
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

    function HandleLoginAcc(returnUrl) {
        var email = document.querySelector("#form-login > div:nth-child(2) > input").value;
        var mailFormat = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var password = document.querySelector("#form-login > div:nth-child(3) > input").value;

        if (!mailFormat.test(email) || email === ""){
            flag = false;
            var aTag = document.createElement("label");
            aTag.id = "email-error";
            aTag.htmlFor = "email";
            aTag.classList.add("error");
            aTag.innerHTML = email === "" ? "Hãy nhập email" : "Email không có thật";
            document.querySelector("#form-login > div:nth-child(2)").append(aTag);
        }

        var check = $("#password-error").length;
        if (password === "" && check == 0){
            var aTag = document.createElement("label");
            aTag.id = "password-error";
            aTag.htmlFor = "password";
            aTag.classList.add("error");
            aTag.innerHTML = "Hãy nhập mật khẩu";
            document.querySelector("#form-login > div:nth-child(3)").append(aTag);
        }

        if(mailFormat.test(email) && email != "" || password != ""){
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
        var name = document.querySelector("#form-register > div:nth-child(2) > input").value;
        var email = document.querySelector("#form-register > div:nth-child(3) > input").value;
        var password = document.querySelector("#password").value;
        var confirmPassword = document.querySelector("#form-register > div:nth-child(5) > input").value;
        var mailFormat = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

        if(name === ""){
            $("#name-error").remove();
            var aTag = document.createElement("label");
            aTag.id = "name-error";
            aTag.htmlFor = "name";
            aTag.classList.add("error");
            aTag.innerHTML = "Hãy nhập tên";
            document.querySelector("#form-register > div:nth-child(2)").append(aTag);
        }

        if (!mailFormat.test(email) || email === ""){
            $("#email-error").remove();
            var aTag = document.createElement("label");
            aTag.id = "email-error";
            aTag.htmlFor = "email";
            aTag.classList.add("error");
            aTag.innerHTML = email === "" ? "Hãy nhập email" : "Email không có thật";
            document.querySelector("#form-register > div:nth-child(3)").append(aTag);
        }

        if (password === ""){
            $("#password-error").remove();
            var aTag = document.createElement("label");
            aTag.id = "password-error";
            aTag.htmlFor = "password";
            aTag.classList.add("error");
            aTag.innerHTML = "Hãy nhập mật khẩu";
            document.querySelector("#form-register > div:nth-child(4)").append(aTag);
        }

        if (confirmPassword === "" || password != confirmPassword){
            $("#confirmPassword-error").remove();
            var aTag = document.createElement("label");
            aTag.id = "confirmPassword-error";
            aTag.htmlFor = "confirmPassword";
            aTag.classList.add("error");
            aTag.innerHTML = password === confirmPassword ? "Hãy xác thực mật khẩu" : "Xác thực mật khẩu sai";
            document.querySelector("#form-register > div:nth-child(5)").append(aTag);
        }

        if(name != "" && mailFormat.test(email) && email != "" && password != "" && confirmPassword != "" && password === confirmPassword){
            $(element + " .btn-register").prop('disabled', true);
            $(element + " .btn-register").addClass('btn-disabled');
            $.ajax({
                url: '/Auth/Register',
                method: "POST",
                data: {name, email, password, confirmPassword},
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
                        $(element + " .btn-register").prop('disabled', false);
                        $(element + " .btn-register").removeClass('btn-disabled');
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
        var mailFormat = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if (!mailFormat.test(email) || email === ""){
            $("#email-error").remove();
            flag = false;
            var aTag = document.createElement("label");
            aTag.id = "email-error";
            aTag.htmlFor = "email";
            aTag.classList.add("error");
            aTag.innerHTML = email === "" ? "Hãy nhập email" : "Email không có thật";
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
        var mailFormat = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if (!mailFormat.test(email)){
            $("#email-error").remove();
            flag = false;
            var aTag = document.createElement("label");
            aTag.id = "email-error";
            aTag.htmlFor = "email";
            aTag.classList.add("error");
            aTag.innerHTML = "Email không có thật";
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