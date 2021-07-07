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
                    url: "Profile/SaveDataAndSendEmail",
                    type: "POST",
                    data: $(form).serialize(),
                    success: function (res) {
                        if (res.isSuccessful) {
                            location.reload();
                        } else {
                            $(form).find('.summary-eror').text(res.message);
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
