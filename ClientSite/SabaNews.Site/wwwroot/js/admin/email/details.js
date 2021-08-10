
var edmDetail = (function ($) {
    var page = 1;
    var emailId = eId;
    var search = "";
    var filterClicked = -1;
    var status = -1;

    function renderList() {
        $.ajax({
            url: '/Admin/Email/GetListEmailDetailData',
            type: 'POST',
            data: { emailId, page, search, filterClicked, status },
            success: function (res) {
                $('.list_detail_content').html(res);
                HandleActionGridData();
            }
        });
    }

    function HandleSearch() {
        $('.search_email_detail').on('click', function () {
            search = $("input[name=search]").val();
            filterClicked = $("select[name=filterClicked]").val();
            status = $("select[name=status]").val();
            renderList();
        });
    }

    function HandleActionGridData() {
        $('.paging').on('click', function () {
            page = $(this).data('page');
            renderList();

        });

        $('.resend-email').on('click', function () {
            var emailId = $(this).data('emailid');
            var userId = $(this).data('userid');

            $.ajax({
                url: "/Admin/Email/ResendEmail",
                type: "POST",
                data: { emailId, userId },
                success: function (response) {
                    if (response.isSuccessful) {
                        GetSuccessPopup('#NoticeModal', 'Resend email successful');
                    } else {
                        GetErrorPopup('#NoticeModal', 'An error occurred while send email, please try again');
                    }
                }
            })
        });

        $('#NoticeModal').on('hidden.bs.modal', function () {
            renderList();
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
        renderList();
        HandleSearch();
    }

    return {
        init
    }

})(jQuery);


$(document).ready(function () {
    edmDetail.init();
});
