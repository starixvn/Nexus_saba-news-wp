
var edmDetail = (function ($) {
    var page = 1;
    var emailId = eId;
    var search = "";
    var filterClicked = -1;
    var status = -1;
    var pageSize = 10;

    function renderList() {
        $.ajax({
            url: '/Admin/Email/GetListEmailDetailData',
            type: 'POST',
            data: { emailId, page, search, filterClicked, status, pageSize },
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

        $('#page-size').on('change', function () {
            pageSize = $(this).val();
            page = 1;
            renderListEmail();
        });

        $('.resend-email').on('click', function () {
            var userId = $(this).data('userid');
            $.blockUI({ message: '<div class="loading"><div class="ring"></div></div>' });
            $.ajax({
                url: "/Admin/Email/ResendEmail",
                type: "POST",
                data: { emailId, userId },
                success: function (response) {
                    $.unblockUI();
                    if (response.isSuccessful) {
                        GetSuccessPopup('#NoticeModal', 'Email has been sent successfully.');
                    } else {
                        GetErrorPopup('#NoticeModal', 'An error occurred while send email, please try again');
                    }
                }
            })
        });
    }

    function HandleResendAll() {
        $('.resend-all').on('click', function () {
            $.blockUI({ message: '<div class="loading"><div class="ring"></div></div>' });
            $.ajax({
                url: "/Admin/Email/ResendAll",
                type: "POST",
                data: { emailId },
                success: function (response) {
                    $.unblockUI();
                    var msg = response > 1 ? response + ' emails have' : 'Email has'
                    GetSuccessPopup('#NoticeModal', msg + ' been sent successfully.');
                    $('#NoticeModal').bind('hidden.bs.modal', function () {
                        location.reload();
                    });
                }
            });
        });
       
    }

    function CloseModalHandle() {
        $('#NoticeModal').on('hidden.bs.modal', function () {
            renderList();
        });
    }
 
    function init() {
        renderList();
        HandleSearch();
        HandleResendAll();
        CloseModalHandle();
    }

    return {
        init
    }

})(jQuery);


$(document).ready(function () {
    edmDetail.init();
});
