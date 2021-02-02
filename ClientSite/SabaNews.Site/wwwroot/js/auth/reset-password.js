var auth = (function ($) {
    function HandleCloseModal() {
        $('#resetModal').on('hidden.bs.modal', function () {
            document.location = '/';
        });
    }

    function HandleResetPassword() {
        const element = '#resetModal form#form-reset-password';
        $(element).validate({
            rules: {
                password: {
                    required: true,
                    minlength: 6
                },
                confirmPassword: {
                    required: true,
                    equalTo: '#password'
                }
            },
            messages: {
                password: {
                    required: 'Hãy nhập mật khẩu',
                    minlength: 'Mật khẩu ít nhất 6 kí tự'
                },
                confirmPassword: {
                    required: 'Hãy xác thực mật khẩu',
                    equalTo: 'Xác thực mật khẩu sai'
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: 'Auth/Reset',
                    method: "POST",
                    data: $(form).serialize(),
                    success: function (res) {
                        //if (res.isSuccessful) {
                        //    const email = $(form).find('input[name="email"]').val();
                        //    let bodyModal = `<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        //        <span aria-hidden="true">&times;</span></button>
                        //        <h3>Đăng ký thành công</h3>
                        //        <div>Hãy kiểm tra email <b>${email}</b> để xác thực email.</div>
                        //        <div>Nhớ kiểm tra thư rác nhé</div>`;
                        //    $('#authModal .modal-body').html(bodyModal);
                        //    HandleCloseModal();
                        //} else {
                        //    //show error res.Message
                        //    console.log(res);
                        //}
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ': ' + errorThrown);
                    }
                });
            }
        });
    }

    function init() {
        $('#resetModal').modal('show');
        HandleResetPassword();
    }

    return {
        init
    }

})(jQuery);

$(document).ready(function () {
    auth.init();
})