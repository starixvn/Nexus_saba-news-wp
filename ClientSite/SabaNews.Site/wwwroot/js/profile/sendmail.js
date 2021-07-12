var sendmailevent = (function ($) {

    function HandleValidate() {
        var form = 'form#form-sendmail';

        $(form).validate({
            rules: {
                mail_title: 'required',
                mail_content: 'required',
                mail_return_link: 'required'
            },
            messages: {
                mail_title: "Vui lòng nhập email title",
                mail_content: "vui lòng nhập content",
                mail_return_link: "Vui lòng nhập return link"
            },
            submitHandler: function (form) {
                $.ajax({
                    url: 'SaveDataAndSendEmail',
                    type: "POST",
                    data: $(form).serialize(),
                    success: function (res) {
                        result = res.result;
                        if (result.isSuccessful) {
                            //location.reload();
                        } else {
                            $(form).find('.summary-eror').text(result.message);
                        }
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
