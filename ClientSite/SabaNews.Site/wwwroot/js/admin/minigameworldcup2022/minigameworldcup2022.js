var minigameworldcup2022 = (function ($) {
    var page = 1;
    var pageSize = 10;
    function GetDataCommentGrid(dateSort) {
        var stage = $('.stage-select').find(':selected').data('stage');
        $.ajax({
            url: "/Admin/MinigameWorldCup2022/GetDataMinigameWorldCup2022",
            type: 'POST',
            data: { stage, page, pageSize, dateSort },
            success: function (res) {
                $('.data-grid').html(res);
                initPaging();
                initSortEvent();
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
        $('.stage-select').on('change', function () {
            page = 1;
            GetDataCommentGrid(null);
        });
        $('.btn-exportexcel').on('click', function () {
            var stage = $('.stage-select').find(':selected').data('stage');
            window.location.href = window.location.origin + "/Admin/MinigameWorldCup2022/ExportExcel?stage=" + stage;
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
    minigameworldcup2022.init();
});