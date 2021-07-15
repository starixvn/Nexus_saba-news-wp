
var sendmailevent = (function ($) {
    configCkeditor.configContentForm('#emailContent');

    function HandleValidate() {
        var form = 'form#form-sendmail';
        $(form).validate({
            rules: {
                emailTitle: 'required',
                emailContent: 'required',
                emailReturnLink: 'required'
            },
            messages: {
                emailTitle: "Vui lòng nhập email title",
                emailContent: "vui lòng nhập content",
                emailReturnLink: "Vui lòng nhập return link"
            },
            submitHandler: function (form) {
                console.log(editorValue.getData());
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
                        } else {
                            $(form).find('.summary-eror').text(result.message);
                        }
                        $('.btn-send-mail').prop('disabled', false);
                        $('.main').scrollTop();
                    },

                });
            }
        });

    }
	function init() {
        HandleValidate();
    }

    return {
        init
    }
})(jQuery);


$(document).ready(function () {
    sendmailevent.init();
});
