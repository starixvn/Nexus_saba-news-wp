var sendmailevent = (function ($) {

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
                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances['emailContent'].updateElement();
                }
                var desc = CKEDITOR.instances.emailContent.getData();
                var data = 
                console.log(desc);
                console.log($(form).serialize());
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
