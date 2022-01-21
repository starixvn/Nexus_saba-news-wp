var adminMembershipEvent = (function ($) {
    var page = 1;
    var pageSize = 10;
    function GetDataCommentGrid() {
        var search = $('#search').val();
        console.log(search);
        $.ajax({
            url: "/Admin/CommentManagement/GetDataComment",
            type: 'POST',
            data: { page, pageSize, search },
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

    function initFilterBtn() {
        $("#filter").on("submit", function (e) {
            e.preventDefault();
            GetDataCommentGrid();
        });
    }

    function init() {
        GetDataCommentGrid();
        initFilterBtn();
    }

    return {
        init
    }
})(jQuery);


$(document).ready(function () {
    adminMembershipEvent.init();
});