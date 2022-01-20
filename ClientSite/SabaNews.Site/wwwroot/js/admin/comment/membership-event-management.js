var adminMembershipEvent = (function ($) {
    var page = 1;
    var pageSize = 10;
    function GetDataCommentGrid() {
        $.ajax({
            url: "/Admin/CommentManagement/GetDataComment",
            type: 'POST',
            data: {  page, pageSize },
            success: function (res) {
                $('.data-grid').html(res);
                initPaging();
            }
        });
    }

    function initPaging() {
        $('.paging').on('click', function () {
            page = $(this).data('page');
            GetDataCommentGrid();

        });

        $('#page-size').on('change', function () {
            pageSize = $(this).val();
            page = 1;
            GetDataCommentGrid();
        });
    }

    function init() {
        GetDataCommentGrid();
    }

    return {
        init
    }
})(jQuery);


$(document).ready(function () {
    adminMembershipEvent.init();
});