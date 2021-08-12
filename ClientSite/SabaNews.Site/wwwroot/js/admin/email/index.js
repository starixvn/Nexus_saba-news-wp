var edmList = (function ($) {
    var page = 1;
    var search = "";
    var fromDate = $('input[name=fromDate]').val();
    var toDate = $('input[name=toDate]').val();
    var status = -1;
    var createdBy = -1;

    function renderListEmail() {
        $.ajax({
            url: "/Admin/Email/GetListEmailData",
            type: 'POST',
            data: { page, search, fromDate, toDate, status, createdBy },
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
            status = $('select[name=status]').val();
            createdBy = $('select[name=createdBy]').val();
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
            $('#ConfirmModal .modal-body').html('Are you sure you want to delete this email?');
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
                        GetSuccessPopup('#NoticeModal', 'Delete Email Successfully');
                    } else {
                        GetErrorPopup('#NoticeModal', 'An error occurred while delete email, please try again');
                    }
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
                        GetSuccessPopup('#NoticeModal', '' + res.userNumber + ' emails have been sent successfully.');
                    } else {
                        GetErrorPopup('#NoticeModal', 'An error occurred while send email, please try again');
                    }

                    $('#NoticeModal').modal('show');
                }
            });
        });
    }

    function GetErrorPopup(popupId, msg) {
        $(popupId + ' .modal-title').removeClass('title-success').addClass('title-error');
        $(popupId + ' .title').html('<i class="icon-title icon-error-admin"></i> Error');
        $(popupId + ' .modal-body').html(msg);
        $(popupId).modal('show');
    }

    function GetSuccessPopup(popupId, msg) {
        $(popupId + ' .modal-title').removeClass('title-error').addClass('title-success');
        $(popupId + ' .title').html('<i class="icon-title icon-success-admin"></i> Successful');
        $(popupId + ' .modal-body').html(msg);
        $(popupId).modal('show');
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
