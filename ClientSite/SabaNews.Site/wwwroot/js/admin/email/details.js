
var detail = (function ($) {
    var page = 1;
    var emailId = eId;
    var search = "";
    var filterClicked = -1;

    function renderList() {
        $.ajax({
            url: '/Admin/Email/GetListEmailDetailData',
            type: 'POST',
            data: { emailId, page, search, filterClicked },
            success: function (res) {
                $('.list_detail_content').html(res);
                handleEmailDetailList();
            }
        });
    }
    function handleEmailDetailList() {
        $('.resend-email').on('click', function () {
            var emailId = $(this).data('emailid');
            var userId = $(this).data('userid');

            $.ajax({
                url: "/Admin/Email/ResendEmail",
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
            renderList();
        });

        $('.paging').on('click', function () {
            page = $(this).data('page');
            renderList();

        });

        $('.search_email_detail').on('click', function () {
            search = $("input[name=search]").val();
            filterClicked = $("select[name=filterClicked]").val();
            renderList();
        });
    }

 
    function init() {
        renderList();
        
    }

    return {
        init
    }

})(jQuery);


$(document).ready(function () {
    detail.init();
});
