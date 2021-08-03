
var testing = (function ($) {
    configCkeditor.configContentForm('#emailContent', '/Admin/Email/UploadImageSendMail');
    var form = 'form#form-sendmail';
    function HandleValidate() {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

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
        if (!regex.test($('input[name="email"]').val())) {
            $('input[name="email"]').addClass('has-error');
            $('.email .help-block').html('Please enter email format');
            isValid = false;
        }
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
            var dummy = document.createElement('input'),
                text = $('#returnUrl').val();

            document.body.appendChild(dummy);
            dummy.value = text;
            dummy.select();
            document.execCommand('copy');
            document.body.removeChild(dummy);
        });


        $('#NoticeModal').on('hidden.bs.modal', function () {
            location.reload();
        });

        $('input[name=sendTo]').on('change', function () {
            var type = $(this).val()
            var email = $('.users').find(':selected').data('email');
            if (type === "email") {
                $('#email').prop('readonly', false);
                $('.user_list').hide();
            } else {
                $('#email').prop('readonly', true);
                $('#email').val(email);
                $('.user_list').show();
            }
        });

        $('.user').on('change', function () {
            var email = $(this).find(':selected').data('email');
            $('#email').val(email);
        })
    }

    function HandleClick() {
        $('.btn-send-mail').on('click', function (e) {
            e.preventDefault();
            HandleSubmit("/Admin/Email/SaveAndReviewSendEmail");
        });
    }

    function HandleSubmit(url) {
        var isValid = HandleValidate();
        if (!isValid) return false;
        var data = $(form).serialize();
        data += "&emailEmbeddedLink=" + $('#returnUrl').val();
        data += "&emailContent=" + encodeURIComponent(editorValue.getData());
        $('.button').prop('disabled', true);
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function (res) {
                result = res.result;
                html = "";
                if (result.isSuccessful ) {
                    html = '<p>Send Email Successfully. Please Check Your Email Box!';
                    $('#NoticeModal .modal-body').html(html);
                } else {
                    $(form).find('.summary-eror').text(result.message);
                }
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
    testing.init();
});
