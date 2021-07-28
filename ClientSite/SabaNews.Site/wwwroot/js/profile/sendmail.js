
var sendmailevent = (function ($) {
    configCkeditor.configContentForm('#emailContent', '/Profile/UploadImageSendMail');

    function HandleValidate() {
        var form = 'form#form-sendmail';

        function ValidateForm() {
            var isValid = true;
            if (editorValue.getData().trim() === "") {
                $('.email_content .help-block').append('Email Content is required');
                isValid = false;
            }
            if ($('input[name="emailTitle"]').val() === "") {
                $('input[name="emailTitle"]').addClass('has-error');
                $('.email_title .help-block').append('Email Title is required');
                isValid = false;
            }
            if ($('input[name="emailReturnLink"]').val() === "") {
                $('input[name="emailReturnLink"]').addClass('has-error');
                $('.email_link .help-block').append('Return Link is required');
                isValid = false;
            }
           // $('.summary-eror').html(error);
            return isValid;
        }

        $('input[name="emailTitle"], input[name="emailReturnLink"]').on('keyup', function () {
            $(this).parent().find('.help-block').html('');
            $(this).removeClass('has-error');
            $('.email_content .help-block').html('');
        });

        $(form).on('submit', function (e) {
            e.preventDefault();
           
            var isValid = ValidateForm();
            if (!isValid) return false;
            var data = $(form).serialize();
            data += "&emailEmbeddedLink=" + $('#returnUrl').val();
            $('.btn-send-mail').prop('disabled', true);
                $.ajax({
                    url: 'SaveDataAndSendEmail',
                    type: "POST",
                    data: data,
                    success: function (res) {
                        result = res.result;
                        if (result.isSuccessful) {
                            var html = '<p>Send Email Successfully. There are ' + res.userNumber + ' emails have been sent<p>';
                            $(form).trigger('reset');
                            editorValue.setData('');
                            $('#NoticeModal .modal-body').html(html)
                        } else {
                            $(form).find('.summary-eror').text(result.message);
                        }
                        $('.btn-send-mail').prop('disabled', false);
                        $('#NoticeModal').modal('show');
                        $('.raw_link').hide();
                    },

                });
        });

        $('input[name=emailReturnLink]').on('keyup', function () {
            if ($(this).val().trim() !== "") {
                $('.raw_link').show()
                var value = "/" + $(this).val();
                var baseUrl = $('#baseUrl').val();
                value = value.replace("//", "/");
                var rawLink = baseUrl + '?returnUrl=' + value;
                $('#returnUrl').val(rawLink);
                $('.raw_link span').html(rawLink);
            } else {
                $('.raw_link').hide()
            }
        });

        $('#NoticeModal').on('hidden.bs.modal', function () {
            location.reload();
        })
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
