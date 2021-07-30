
var detail = (function ($) {
    var page = 1;
    var emailId = eId;

    function renderList() {
        $.ajax({
            url: '/Admin/Email/GetListEmailDetailData',
            type: 'POST',
            data: { emailId ,page },
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
