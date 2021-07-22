
var sendmailevent = (function ($) {
    configCkeditor.configContentForm('#emailContent', '/Profile/UploadImageSendMail');

    function HandleValidate() {
        var form = 'form#form-sendmail';
       // $(form).validate({
       //     ignore: ":hidden:not(#emailContent)",
       //     rules: {
       //         emailTitle: 'required',
       //         emailReturnLink: 'required'
       //     },
       //     messages: {
       //         emailTitle: "Vui lòng nhập email title",
       //         emailReturnLink: "Vui lòng nhập return link"
       //     },
       //     submitHandler: function (form) {
       //         console.log(editorValue.getData());
       //         if (editorValue.getData().trim() === "") {
       //             $(form).find('.summary-eror').text('vui lòng nhập content');
       //             return false;
       //         }
       //         console.log($(form).serialize());
       //         $('.btn-send-mail').prop('disabled', true);
       //         $.ajax({
       //             url: 'SaveDataAndSendEmail',
       //             type: "POST",
       //             data: $(form).serialize(),
       //             success: function (res) {
       //                 result = res.result;
       //                 if (result.isSuccessful) {
       //                     $(form).find('.summary-eror').text("Gửi email thành công");
							//$(form).trigger('reset');
       //                     editorValue.setData('');
       //                     location.reload();
       //                 } else {
       //                     $(form).find('.summary-eror').text(result.message);
       //                 }
       //                 $('.btn-send-mail').prop('disabled', false);
       //                 $('.main').scrollTop();
       //             },

       //         });
       //     }
       // });

        function ValidateForm() {
            var error = "";
            var isValid = true;
            if (editorValue.getData().trim() === "") {
                error += "<li>Vui lòng nhập nội dung</li>"
                isValid = false;
            }
            if ($('input[name="emailTitle"]').val() === "") {
                error += "<li>Vui lòng nhập tiêu đề</li>";
                isValid = false;
            }
            if ($('input[name="emailReturnLink"]').val() === "") {
                error += "<li>Vui lòng nhập link trả về</li>";
                isValid = false;
            }
            $('.summary-eror').html(error);
            return isValid;
        }

        $(form).on('submit', function (e) {
            e.preventDefault();
            console.log(editorValue.getData());
           
            var isValid = ValidateForm();
            if (!isValid) return false;
            console.log($(form).serialize());
            $('.btn-send-mail').prop('disabled', true);
                $.ajax({
                    url: 'SaveDataAndSendEmail',
                    type: "POST",
                    data: $(form).serialize(),
                    success: function (res) {
                        result = res.result;
                        if (result.isSuccessful) {
                            $(form).find('.summary-eror').text("Gửi email thành công");
							$(form).trigger('reset');
                            editorValue.setData('');
                            location.reload();
                        } else {
                            $(form).find('.summary-eror').text(result.message);
                        }
                        $('.btn-send-mail').prop('disabled', false);
                        $('.main').scrollTop();
                    },

                });
        });
    }

    function HanldeEmailList() {
        $('.view_content').click(function () {
            var content = $(this).parent().find('.email_content').val();
            var title = $(this).parent().find('.email_title').val();
            $('#ViewEmailContent .modal-body').html(content);
            $('#ViewEmailContent .title').html(title);
            $('#ViewEmailContent').modal('show');
        });
    }
	function init() {
        HandleValidate();
        HanldeEmailList();
    }

    return {
        init
    }
})(jQuery);


$(document).ready(function () {
    sendmailevent.init();
});
