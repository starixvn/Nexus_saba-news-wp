var countUser = (function ($) {
    var page = 1;
    var pageSize = 10;
    function GetDataGrid(dateSearch) {
        var stage = $('.stage-select').find(':selected').data('stage');
        $.ajax({
            url: "/Admin/MinigameWorldCup2022/GetCountUserGrid",
            type: 'POST',
            data: { stage, page, pageSize, dateSearch },
            success: function (res) {
                $('.data-grid').html(res);
            }
        });
    }

    function initPaging() {
        $('.paging').on('click', function () {
            page = $(this).data('page');
            var dateSort = $("#date-sort").attr("sort-type");
            GetDataCommentGrid(dateSort);
        });

        $('#page-size').on('change', function () {
            pageSize = $(this).val();
            page = 1;
            var dateSort = $("#date-sort").attr("sort-type");
            GetDataCommentGrid(dateSort);
        });
    }

    function initSortEvent() {
        $("#date-sort").on("click", function () {
            var dateType = $(this).attr("sort-type");
            if (!dateType) {
                dateType = "desc";
                GetDataCommentGrid(dateType);
                return;
            }

            dateType = dateType === "desc" ? "asc" : "desc";
            GetDataCommentGrid(dateType);
        });
    }

    function initFilterBtn() {
        $('.search_user').on('click', function () {
            var date = $('#dateSearch').val();
            GetDataGrid(date);
        });
        $('.btn-exportexcel').on('click', function () {
            var stage = $('.stage-select').find(':selected').data('stage');
            window.location.href = window.location.origin + "/Admin/MinigameWorldCup2022/ExportExcel?stage=" + stage;
        });
    }

    function init() {
        GetDataGrid();
        initFilterBtn();
    }

    return {
        init
    }
})(jQuery);


$(document).ready(function () {
    countUser.init();
});