var update_profile = (function ($) {
    function update_saved_post() {
        $('.remove_save_post').on('click', function () {
            var self = $(this);
            var pID = self.data('postid');
            $.ajax({
                type: "POST",
                url: "/Profile/SavePost",
                data: { postID: pID },
                success: function (response) {
                    if (response.isSuccessful) {
                        self.parent().html('');
                    }
                }
            });
        });
    }

    function validate() {
        $('input[name=phone]').on('keyup', function () {
            var val = $(this).val();
            if (val.trim() === "") {
                $('.btn-save-phone').prop('disabled', true);
                $('.btn-save-phone').addClass('btn-disabled');
            } else {
                $('.btn-save-phone').prop('disabled', false);
                $('.btn-save-phone').removeClass('btn-disabled');
            }
        });

        $('input[name=name]').on('keyup', function () {
            var val = $(this).val();
            if (val.trim() === "") {
                $('.btn-save-name').prop('disabled', true);
                $('.btn-save-name').addClass('btn-disabled');
            } else {
                $('.btn-save-name').prop('disabled', false);
                $('.btn-save-name').removeClass('btn-disabled');
            }
        });

        $('input[name=gender]').on('click', function () {
            var val = $(this).val();
            console.log(val);
            if (val.trim() === "") {
                $('.btn-save-gender').prop('disabled', true);
                $('.btn-save-gender').addClass('btn-disabled');
            } else {
                $('.btn-save-gender').prop('disabled', false);
                $('.btn-save-gender').removeClass('btn-disabled');
            }
        });

        $('input[name=oldpassword], input[name=newpassword').on('keyup', function () {
            var oldPassword = $('input[name = oldpassword]').val();
            var newPassword = $('input[name=newpassword]').val();
            console.log(oldPassword);
            console.log(newPassword);

            if (oldPassword === "" || newPassword === "") {
                $('.btn-save-password').prop('disabled', true);
                $('.btn-save-password').addClass('btn-disabled');
            } else {
                $('.btn-save-password').prop('disabled', false);
                $('.btn-save-password').removeClass('btn-disabled');
            }
        });
    }

    function update_profile_info() {
        $('.btn-save-phone').on('click', function () {
            var phone = $('input[name=phone]').val();
            if (phone.trim() === "") {
                $('.phone-error').show();
                return false;
            }
            var currentPhone = $(this).data('phone');
            if (phone === currentPhone) {
                $('#form_update_phone').removeClass('toggle-active-form');
                return false;
            }

            $.ajax({
                type: 'POST',
                url: 'Profile/UpdateProfile',
                data: { type: 'phone', value: phone },
                success: function (response) {
                    if (response.isSuccessful) {
                        $('#form_update_phone').removeClass('toggle-active-form');
                        var html = '<li class="txt-feild">' + phone + '</li>';
                        $('.phone-section').find('.txt-fill').html(html);
                    }
                }
            });
        });

        $('.btn-save-name').on('click', function () {
            var name = $('input[name=name]').val();
            if (name.trim() === "") {
                $('.name-error').show();
                return false;
            }
            var currentName = $(this).data('phone');
            if (name === currentName) {
                $('#form_update_name').removeClass('toggle-active-form');
                return false;
            }

            $.ajax({
                type: 'POST',
                url: 'Profile/UpdateProfile',
                data: { type: 'name', value: name },
                success: function (response) {
                    if (response.isSuccessful) {
                        $('#form_update_name').removeClass('toggle-active-form');
                        var html = '<li class="txt-feild">' + name + '</li>';
                        $('.name-section').find('.txt-fill').html(html);
                    }
                }
            });
        });

        $('.btn-save-gender').on('click', function () {
            var gender = $('input[name=gender]:checked').val();
            if (gender.trim() === "") {
                $('.gender-error').show();
                return false;
            }
            var currentGender = $(this).data('gender');
            if (gender === currentGender) {
                $('#form_update_gender').removeClass('toggle-active-form');
                return false;
            }

            $.ajax({
                type: 'POST',
                url: 'Profile/UpdateProfile',
                data: { type: 'gender', value: gender },
                success: function (response) {
                    if (response.isSuccessful) {
                        $('#form_update_gender').removeClass('toggle-active-form');
                        var html = '<li class="txt-feild">' + gender + '</li>';
                        $('.gender-section').find('.txt-fill').html(html);
                    }
                }
            });
        });

        $('.btn-save-password').on('click', function () {
            var oldPassword = $('input[name = oldpassword]').val();
            var newPassword = $('input[name=newpassword]').val();
            var check = true;
            if (oldPassword.length < 6) {
                $('.old-password-length-error').show();
                check = false;
            }

            if (newPassword.length < 6) {
                $('.new-password-length-error').show();
                check = check ? false : check;
            }
            if (!check) {
                return false;
            }

            $.ajax({
                type: 'POST',
                url: 'Profile/UpdateProfilePassword',
                data: { oldPassword: oldPassword, newPassword: newPassword },
                success: function (response) {
                    if (response.isSuccessful) {
                        $('#form_update_password').removeClass('toggle-active-form');
                        $('.err-password').show();
                    } else {
                        $('.old-password-error').show();
                        $('.old-password-error').html(response.message);
                    }
                }
            });
        });

        $('#profile-forgot-password').on('click', function () {
            $.ajax({
                url: '/Auth/ForgotPassword',
                method: "POST",
                data: { email: email },
                success: function (res) {
                    if (res.isSuccessful) {
                        const html = _htmlResource.ForgotPassword + _htmlResource.RemindCheckMail;
                        console.log(html);
                        const bodyModal = formatString(html, email);
                        $('#resetPasswordSuccesful .modal-body').html(bodyModal);
                        $('#resetPasswordSuccesful').modal('show');
                    } 
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                }
            });
        });
        
    }

    function update_photo() {
        $('#uploadPhoto').on('submit', function (e) {
            e.preventDefault();
            $('.photo-error').hide();
            var files = $('input[name=photo]')[0].files[0];
            if (typeof files === "undefined") {
                $('.photo-error').html("Vui lòng chọn file trước!");
                $('.photo-error').show();
                return false;
            }
            var type_reg = /^image\/(jpg|png|jpeg|bmp|gif|ico)$/;
            console.log(files);
            var fsize = files.size,
                ftype = files.type,
                fname = files.name,
                fextension = fname.substring(fname.lastIndexOf('.') + 1);


            if (!type_reg.test(ftype)) {
                $('.photo-error').html("Kiểu file này được được phép!");
                $('.photo-error').show();
                return false;
            } else if (fsize > 3145728) {
                $('.photo-error').html("File quá lớn! Vui lòng chọn hình nhỏ hơn 3MB");
                $('.photo-error').show();
                return false;
            }
            var formData = new FormData();
            formData.append('file', files);
            $.ajax({
                type: 'POST',
                url: "Profile/UpdatePhoto",
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    if (res.isSuccessful) {
                        $('.photo-error').hide();
                        $('input[name=photo]').val(null);
                        var html = '<img src="'+ res.linkPhoto +'"  width="300"/>';
                        $('.profile__avatar').html(html);
                    }
                }
            });
        });
    }

   

    function init() {
        update_saved_post();
        validate();
        update_profile_info();
        update_photo();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    update_profile.init();
});