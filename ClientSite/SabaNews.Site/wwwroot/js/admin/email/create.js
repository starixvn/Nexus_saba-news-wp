var edmCreate = (function ($) {
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
            $('.email_title .help-block').html('Subject is required');
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
                $('#embeddedLink').val(rawLink);
                $('.raw_link').html(rawLink);
            } else {
                $('.group_raw_link').hide()
            }
        });

        $('.copy-link').on("click", function () {
            var self = $(this);
            var dummy = document.createElement('input'),
                text = $('#embeddedLink').val();

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
            HandleSubmit("/Admin/Email/SaveAndSendCreate");
        });

        $('.btn-save-as-draft').on('click', function (e) {
            e.preventDefault();
            HandleSubmit("/Admin/Email/SaveCreate");
        });

        $('.btn-send-mail-edit').on('click', function (e) {
            e.preventDefault();
            HandleSubmit("/Admin/Email/SaveAndSendEdit");
        });

        $('.btn-save-as-draft-edit').on('click', function (e) {
            e.preventDefault();
            HandleSubmit("/Admin/Email/SaveEdit");
        });
    }

    function HandleSubmit(url) {
        var isValid = HandleValidate();
        if (!isValid) return false;
        var data = $(form).serialize();
        data += "&emailContent=" + encodeURIComponent(editorValue.getData());
        $('.button').prop('disabled', true);
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
                    html = '<p>Save Data Successfully<p>';
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

    function HandleTestSendEmail() {
        $('.btn-test').on('click', function () {
            var isValid = HandleValidate();
            if (isValid) {
                $('#TestSendEmail').modal('show');
            }
        });


        $('input[name=sendTo]').on('change', function () {
            var type = $(this).val()
            if (type === "email") {
                $('.user_list').hide();
            } else {
                $('.user_list').show();
            }
        });

        $('.user').on('change', function () {
            var email = $(this).find(':selected').data('email');
            $('#email').val(email);
        })

        $('.submit-test-send-email').on('click', function () {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (!regex.test($('input[name="emailAddress"]').val())) {
                $('input[name="emailAddress"]').addClass('has-error');
                $('.email .help-block').html('Please enter email format');
                return false;
            }
            $('#TestSendEmail').modal('hide');
            var data = $(form).serialize();
            data += "&emailContent=" + encodeURIComponent(editorValue.getData());
            data += "&emailAddress=" + $('input[name=emailAddress]').val();
            $.ajax({
                url: "/Admin/Email/TestSendEmail",
                type: "POST",
                data: data,
                success: function (res) {
                    if (res.isSuccessful) {
                        $('#TestModal .modal-body').html('Send Email Successfully. Please check your mailbox~');
                    } else {
                        $('#TestModal .modal-body').html('An error occurred while send email, please try again');
                    }
                    $('#TestModal').modal('show');
                }
            });

        });
    }
   
    function init() {
        HandleProcessForm();
        HandleClick();
        HandleTestSendEmail();
    }

    return {
        init
    }
})(jQuery);


$(document).ready(function () {
    edmCreate.init();
});
