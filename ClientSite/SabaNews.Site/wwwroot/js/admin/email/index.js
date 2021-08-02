
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
