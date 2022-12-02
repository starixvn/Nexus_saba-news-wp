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
                $('.btn-detail').on('click', function () {
                    var stageDetail = $('.stage-select').find(':selected').data('stage');
                    var userID = $(this).data('userid');
                    var totalpoint = $(this).data('totalpoint');
                    var rank = $(this).data('rank');

                    $.ajax({
                        url: "/Admin/MinigameWorldCup2022/GetDetailUser",
                        type: 'POST',
                        data: { stageDetail, userID, totalpoint, rank },
                        success: function (res) {
                            $('#DetailPlayerContent .title').html('Detail Player');
                            $('#DetailPlayerContent .modal-body').html(res);
                            $('#DetailPlayerContent').modal('show');
                        }
                    });
                });
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
        $('.search_user').on('click', function () {
            window.location.href = window.location.origin + "/Admin/MinigameWorldCup2022/CountUser";
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