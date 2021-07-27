
var sendmailevent = (function ($) {
    configCkeditor.configContentForm('#emailContent', '/Profile/UploadImageSendMail');

    function HandleValidate() {
        var form = 'form#form-sendmail';

        function ValidateForm() {
            var error = "";
            var isValid = true;
            if (editorValue.getData().trim() === "") {
                error += "<li>Email Content is required</li>"
                isValid = false;
            }
            if ($('input[name="emailTitle"]').val() === "") {
                error += "<li>Email Title is required</li>";
                isValid = false;
            }
            $('.summary-eror').html(error);
            return isValid;
        }

        $(form).on('submit', function (e) {
            e.preventDefault();
           
            var isValid = ValidateForm();
            if (!isValid) return false;
            $('.btn-send-mail').prop('disabled', true);
                $.ajax({
                    url: 'SaveDataAndSendEmail',
                    type: "POST",
                    data: $(form).serialize(),
                    success: function (res) {
                        result = res.result;
                        if (result.isSuccessful) {
                            var html = '<p>Send Email Successfully. There are ' + res.userNumber + ' email has been sent<p>';
                            $(form).trigger('reset');
                            editorValue.setData('');
                            $('#NoticeModal .modal-body').html(html)
                        } else {
                            $(form).find('.summary-eror').text(result.message);
                        }
                        $('.btn-send-mail').prop('disabled', false);
                        $('#NoticeModal').modal('show');
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
