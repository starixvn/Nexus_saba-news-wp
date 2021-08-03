
var sendmailevent = (function ($) {
    configCkeditor.configContentForm('#emailContent', '/Admin/Email/UploadImageSendMail');
    var form = 'form#form-sendmail';
    function HandleValidate() {
        var isValid = true;
        if (editorValue.getData().trim() === "") {
            $('.email_content .help-block').html('Email Content is required');
            isValid = false;
        }
        if ($('input[name="emailTitle"]').val() === "") {
            $('input[name="emailTitle"]').addClass('has-error');
            $('.email_title .help-block').html('Email Title is required');
            isValid = false;
        }
        if ($('input[name="emailReturnLink"]').val() === "") {
            $('input[name="emailReturnLink"]').addClass('has-error');
            $('.email_link .help-block').html('Return Link is required');
            isValid = false;
        }
        // $('.summary-eror').html(error);
        return isValid;
    }

    function HandleProcessForm() {
        $('input[name="emailTitle"], input[name="emailReturnLink"]').on('keyup', function () {
            $(this).parent().find('.help-block').html('');
            $(this).removeClass('has-error');
            $('.email_content .help-block').html('');
        });


        $('input[name=emailReturnLink]').on('keyup', function () {
            if ($(this).val().trim() !== "") {
                $('.group_raw_link').show()
                var value = $(this).val();
                var baseUrl = $('#baseUrl').val();
                var rawLink = baseUrl + '&returnUrl=' + value;
                $('#returnUrl').val(rawLink);
                $('.raw_link').html(rawLink);
            } else {
                $('.group_raw_link').hide()
            }
        });

        $('.copy-link').on("click", function () {
            var self = $(this);
            var dummy = document.createElement('input'),
                text = $('#returnUrl').val();

            document.body.appendChild(dummy);
            dummy.value = text;
            dummy.select();
            document.execCommand('copy');
            document.body.removeChild(dummy);
            $(this).parent().find('.tip').show();
            setTimeout(function () {
                self.parent().find('.tip').hide();
            }, 1000);
        });


        $('#NoticeModal').on('hidden.bs.modal', function () {
            location.reload();
        });
    }

    function HandleClick() {
        $('.btn-send-mail').on('click', function (e) {
            e.preventDefault();
            HandleSubmit("/Admin/Email/SaveDataAndSendEmail");
        });

        $('.btn-save-as-draft').on('click', function (e) {
            e.preventDefault();
            HandleSubmit("/Admin/Email/SaveAsDraft");
        });
    }

    function HandleSubmit(url) {
        var isValid = HandleValidate();
        if (!isValid) return false;
        var data = $(form).serialize();
        data += "&emailEmbeddedLink=" + $('#returnUrl').val();
        data += "&emailContent=" + encodeURIComponent(editorValue.getData());
        $('.btn-send-mail').prop('disabled', true);
        $('.btn-save-as-draft').prop('disabled', true);
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function (res) {
                result = res.result;
                html = "";
                if (result.isSuccessful && res.userNumber != null) {
                    html = '<p>Send Email Successfully. There are ' + res.userNumber + ' emails have been sent<p>';
                    $('#NoticeModal .modal-body').html(html);
                } else if (result.isSuccessful) {
                    html = '<p>Send Email Successfully<p>';
                    $('#NoticeModal .modal-body').html(html);
                } else {
                    $(form).find('.summary-eror').text(result.message);
                }
                //$('.btn-send-mail').prop('disabled', false);
                $('#NoticeModal').modal('show');
                $('.group_raw_link').hide();
            },

        });
    }


   
    function init() {
        HandleProcessForm();
        HandleClick();
    }

    return {
        init
    }
})(jQuery);


$(document).ready(function () {
    sendmailevent.init();
});
