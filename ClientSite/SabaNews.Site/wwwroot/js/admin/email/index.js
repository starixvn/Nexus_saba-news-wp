
var email = (function ($) {
    var page = 1;
    var search = " ";
    var dateFrom = " ";
    var dateTo = " ";

    function renderListEmail() {
        $.ajax({
            url: "/Admin/Email/GetListEmailData",
            type: 'POST',
            data: { page: page, search: search, dateFrom, dateTo },
            success: function (res) {
                $('.list_email_block').html(res);
                HandleEmailList();
                HandleTrySendEmail();
            }
        });
    }

    function HandleEmailList() {
        $('.view_content').click(function () {
            var content = $(this).parent().find('.email_content').val();
            var title = $(this).parent().find('.email_title').val();
            $('#ViewEmailContent .modal-body').html(content);
            $('#ViewEmailContent .title').html(title);
            $('#ViewEmailContent').modal('show');
        });

        $('.paging').on('click', function () {
            page = $(this).data('page');
            renderListEmail();
           
        });

        $('.search_list_email').on('click', function () {
            search = $('input[name=search]').val();
            dateFrom = $('input[name=dateFrom]').val();
            dateTo = $('input[name=dateTo]').val();
            page = 1;
            renderListEmail();
        });


        $('.send-email').on('click', function () {
            var id = $(this).data('emailid');
            $.blockUI({ message: '<h3>Loading</h3>' }); 
            $.ajax({
                url: "/Admin/Email/SendEmail",
                type: "POST",
                data: { emailId: id },
                success: function (res) {
                    $.unblockUI();
                    if (res.result.isSuccessful) {
                        $('#NoticeModal .modal-body').html('Send Email Successfully. There are ' + res.userNumber + ' emails have been sent<p>');
                    } else {
                        $('#NoticeModal .modal-body').html('An error occurred while send email, please try again');
                    }

                    $('#NoticeModal').modal('show');
                }
            });
        });

        $('#NoticeModal').on('hidden.bs.modal', function () {
            location.reload();
        })
    }

    function HandleTrySendEmail() {
        var form = $('form#form_review_sendemail');

        $('.review-send-email').on('click', function () {
            $('#email').val('');
            var emailId = $(this).data('emailid');
            $('#form_review_sendemail #emailId').val(emailId);
            $('#ReviewSendEmail').modal('show');
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

        $('.btn-review-send-email').on('click', function () {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (!regex.test($('input[name="email"]').val())) {
                $('input[name="email"]').addClass('has-error');
                $('.email .help-block').html('Please enter email format');
                return false;
            }
            $.blockUI({ message: '<h3>Loading</h3>' }); 
            $.ajax({
                url: "/Admin/Email/ReviewSendEmail",
                type: "POST",
                data: form.serialize(),
                success: function (res) {
                    $.unblockUI(); 
                    if (res.isSuccessful) {
                        $('#NoticeModal .modal-body').html('Send Email Successfully. Please check your mailbox~');
                    } else {
                        $('#NoticeModal .modal-body').html('An error occurred while send email, please try again');
                    }
                    $('#ReviewSendEmail').modal('hide');
                    $('#NoticeModal').modal('show');
                }
            });
           
        });
    }
    function init() {
        renderListEmail();
    }

    return {
        init
    }

})(jQuery);


$(document).ready(function () {
    email.init();
});
