var edmList = (function ($) {
    var page = 1;
    var search = "";
    var fromDate = $('input[name=fromDate]').val();
    var toDate = $('input[name=toDate]').val();

    function renderListEmail() {
        $.ajax({
            url: "/Admin/Email/GetListEmailData",
            type: 'POST',
            data: { page: page, search: search, fromDate, toDate },
            success: function (res) {
                $('.list_email_block').html(res);
                HandleGridData();
            }
        });
    }

    function HandleSearch() {
        $('.search_list_email').on('click', function () {
            search = $('input[name=search]').val();
            fromDate = $('input[name=fromDate]').val();
            toDate = $('input[name=toDate]').val();
            page = 1;
            renderListEmail();
        });

        $('#NoticeModal').on('hidden.bs.modal', function () {
            renderListEmail();
        })
    }

    function HandleGridData() {
        var emailId = 0;
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

        $('.delete-email').on('click', function () {
            emailId = $(this).data('emailid');
            $('#ConfirmModal .modal-body').html('Are you sure you would like to delete this email');
            $('#ConfirmModal').modal('show');
        });

        $('.confirm-action').on('click', function () {
            $('#ConfirmModal').modal('hide');
            $.blockUI({ message: '<div class="loading"><div class="ring"></div></div>' });
            $.ajax({
                url: "/Admin/Email/Delete",
                type: "POST",
                data: { emailId },
                success: function (res) {
                    $.unblockUI();
                    if (res.isSuccessful) {
                        $('#NoticeModal .modal-body').html('Delete Email Successfully');
                    } else {
                        $('#NoticeModal .modal-body').html('An error occurred while delete email, please try again');
                    }

                    $('#NoticeModal').modal('show');
                }
            });
        });

        $('.send-email').on('click', function () {
            emailId = $(this).data('emailid');
            $.blockUI({ message: '<div class="loading"><div class="ring"></div></div>' });
            $.ajax({
                url: "/Admin/Email/SendEmail",
                type: "POST",
                data: { emailId },
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
    } 

    
    function init() {
        HandleSearch();
        renderListEmail();
    }

    return {
        init
    }

})(jQuery);


$(document).ready(function () {
    edmList.init();
});
