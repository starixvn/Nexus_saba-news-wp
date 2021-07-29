
var email = (function ($) {
    var page = 1;
    var search = "";
    function handleEmailDetailList() {
        $('.resend-email').on('click', function () {
            var emailId = $(this).data('emailid');
            var userId = $(this).data('userid');

            $.ajax({
                url: "/Email/ResendEmail",
                type: "POST",
                data: { emailId, userId },
                success: function (response) {
                    if (response.isSuccessful) {
                        $('#NoticeModal .modal-body').html('Resend email successful');
                    } else {
                        $('#NoticeModal .modal-body').html('An error occurred while send email, please try again');
                    }
                    $('#NoticeModal').modal('show');
                }
            })
        });

        $('#NoticeModal').on('hidden.bs.modal', function () {
            location.reload();
        })
       
    }

    function renderListEmail() {
        $.ajax({
            url: '/Email/GetListEmailData',
            type: 'POST',
            data: { page: page, search: search },
            success: function (res) {
                $('.list_email_block').html(res);
                HanldeEmailList();
            }
        });
    }

    function HanldeEmailList() {
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
            page = 1;
            renderListEmail();
        });
    }
    function init() {
        handleEmailDetailList();
        renderListEmail();
    }

    return {
        init
    }

})(jQuery);


$(document).ready(function () {
    email.init();
});
