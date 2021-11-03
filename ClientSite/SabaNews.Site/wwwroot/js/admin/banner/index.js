var banner = (function ($) {

    function ValidateForm() {
        $('input[name=NavigationUrl]').on('keyup', function () {
            var val = $(this).val();
            if (val.trim() === "") {
                $('.btn-save-navigation').prop('disabled', true);
                $('.btn-save-navigation').addClass('btn-disabled');
            } else {
                $('.btn-save-navigation').prop('disabled', false);
                $('.btn-save-navigation').removeClass('btn-disabled');
            }
        });
    }

    function UpdateBannerInfo() {
        $('.btn-save-navigation').on('click', function () {
            var value = $('input[name=NavigationUrl]').val();
            if (value.trim() === "") {
                $('.navagation-error').show();
                return false;
            }
            var current = $(this).data('current');
            if (value === current) {
                $('#form_update_phone').removeClass('toggle-active-form');
                return false;
            }

            $.ajax({
                type: 'POST',
                url: '/Admin/BannerManagement/UpdateInfo',
                data: { navigationUrl: value },
                success: function (res) {
                    if (res.isSuccessful) {
                        var msg = 'Update Info Succesfully'
                        GetSuccessPopup('#NoticeModal', msg);
                    } else {
                        GetErrorPopup('#NoticeModal', res.message);
                    }
                    $('#NoticeModal').bind('hidden.bs.modal', function () {
                        location.reload();
                    });
                }
            });
        });
    }

    function validate_photo(files, self) {
        var isValid = true;
        var errorSection = self.parent().find('.photo-error');
        if (typeof files === "undefined") {
            errorSection.html("Please choose file first!");
            errorSection.show();
            return false;
        }
        var type_reg = /^image\/(jpg|png|jpeg|bmp|gif|ico)$/;
        var fsize = files.size,
            ftype = files.type,
            fname = files.name,
            fextension = fname.substring(fname.lastIndexOf('.') + 1);

        if (!type_reg.test(ftype)) {
            errorSection.html("Sorry, this file type is not permitted for security reasons!");
            errorSection.show();
            return false;
        } else if (fsize > 3145728) {
            errorSection.html("Exceeds the maximum upload size for this site.");
            errorSection.show();
            return false;
        }

        return isValid;
    }

    function UploadBannerProcess() {
        $('#uploadDesktop').on('submit', function (e) {
            e.preventDefault();
            $('.photo-error').hide();
            var files = $(this).parent().parent().find('.photo')[0].files[0];
            UploadBanner(files, "desktop", $(this));
        });

        $('#uploadMobile').on('submit', function (e) {
            e.preventDefault();
            $('.photo-error').hide();
            var files = $(this).parent().parent().find('.photo')[0].files[0];
            UploadBanner(files, "mobile", $(this));
        });

        $('.photo').on('change', function (e) {
            var self = $(this).parent();
            self.find('.review_photo').attr('src', '');
            self.find('.review_photo').hide();
            self.find('.cancel-upload').show();
            var files = $(this)[0].files[0];
            var isValid = validate_photo(files, $(this));
            if (!isValid) {
                return false;
            }
            self.find('.save-img').css('display', 'block');
            self.find('.txt-img').css('display', 'none');
            var reader = new FileReader();
            reader.readAsDataURL(files);
            reader.onload = function (e) {
                self.find('.review_photo').attr('src', e.target.result);
                self.find('.review_photo').show();
                self.find('.photo-error').hide();
            };
        });

        $('.cancel-upload').on('click', function () {
            var self = $(this).parent();
            self.parent().find('.photo').val('');
            self.find('.review_photo').attr('src', '');
            self.find('.review_photo').hide();
            $(this).show();
            self.find('.save-img').hide();
            self.parent().find('.txt-img').css('display', 'flex');
            $(this).hide();
        });
    };

    function UploadBanner(files, type, self) {
        var isValid = validate_photo(files, self);
        if (!isValid) {
            return false;
        }

        var formData = new FormData();
        formData.append('file', files);
        formData.append('type', type);
        $.ajax({
            type: 'POST',
            url: "/Admin/BannerManagement/UploadBanner",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.isSuccessful) {
                    var msg = 'upload new banner Succesfully'
                    GetSuccessPopup('#NoticeModal', msg);
                } else {
                    GetErrorPopup('#NoticeModal', res.message);
                }
                $('#NoticeModal').bind('hidden.bs.modal', function () {
                    location.reload();
                });
            }
        });
    }
    function GetErrorPopup(popupId, msg) {
        $(popupId + ' .modal-title').removeClass('title-success').addClass('title-error');
        $(popupId + ' .title').html('<i class="icon-title icon-error-admin"></i> Error');
        $(popupId + ' .modal-body').html(msg);
        $(popupId).modal('show');
    }

    function GetSuccessPopup(popupId, msg) {
        $(popupId + ' .modal-title').removeClass('title-error').addClass('title-success');
        $(popupId + ' .title').html('<i class="icon-title icon-success-admin"></i> Successful');
        $(popupId + ' .modal-body').html(msg);
        $(popupId).modal('show');
    }

    function init() {
        ValidateForm();
        UpdateBannerInfo();
        UploadBannerProcess();
    }
    return {
        init
    }
})(jQuery);


$(document).ready(function () {
    banner.init();
});
