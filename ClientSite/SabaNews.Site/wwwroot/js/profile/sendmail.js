var sendmailevent = (function ($) {
	var emailContent;

	function configContentForm() {
		ClassicEditor
			.create(document.querySelector('#emailContent'), {
				//uploadUrl: '/js/ckfinder/core/connector/aspx/connector.aspx?command=QuickUpload&type=File',
				toolbar: {
					items: [
						'heading',
						'|',
						'bold',
						'italic',
						'link',
						'bulletedList',
						'numberedList',
						'|',
						'outdent',
						'indent',
						'|',
						'imageUpload',
						'blockQuote',
						'insertTable',
						'mediaEmbed',
						'undo',
						'redo',
						'imageInsert'
					]
				},
				language: 'en',
				image: {
					toolbar: [
						'imageTextAlternative',
						'imageStyle:inline',
						'imageStyle:block',
						'imageStyle:side'
					]
				},
				table: {
					contentToolbar: [
						'tableColumn',
						'tableRow',
						'mergeTableCells'
					]
				},
				licenseKey: '',



			})
			.then(editor => {
				emailContent = editor;
			})
			.catch(error => {
				console.error('Oops, something went wrong!');
				console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
				console.warn('Build id: fx26hugqrxhi-4uan9d1tcbpy');
				console.error(error);
			});
    }
	

    function HandleValidate() {
        var form = 'form#form-sendmail';

        var validate = $(form).validate({
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
                //for (instance in CKEDITOR.instances) {
                //    CKEDITOR.instances['emailContent'].updateElement();
				//}
				console.log(emailContent.getData());
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
							emailContent.setData('');
                        } else {
                            $(form).find('.summary-eror').text(result.message);
                        }
                        $('.btn-send-mail').prop('disabled', false);
                    },

                });
            }
        });

    }
	function init() {
		configContentForm();
        HandleValidate();
    }

    return {
        init
    }
})(jQuery);


$(document).ready(function () {
    sendmailevent.init();
});
