var adminManagementLeague = (function ($) {
    var page = 1;
    var pageSize = 10;
    var pageAddLeague = 1;
    var pageSizeAddLeague = 10;
    var searchAddLeague = $('#searchAddLeague').val();
    function GetDataCommentGrid(dateSort) {
        var search = $('#search').val();
        $.ajax({
            url: "/Admin/ManagementLeague/GetManagementLeagueData",
            type: 'POST',
            data: { page, pageSize, search, dateSort },
            success: function (res) {
                $('.data-grid').html(res);
                initPaging();
                initSortLeague();
                initAddLeague();
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

    function initAddLeague() {
        $('.btn_league').click(function () {
            $.ajax({
                type: 'POST',
                url: '/Admin/ManagementLeague/AddLeague',
                success: function (html) {
                    $('#AddLeagueContent .title').html('Add League');
                    $('#AddLeagueContent .modal-body').html(html);
                    $('#AddLeagueContent').modal('show');
                }
            })
        });

        $('.pagingAddLeague').on('click', function () {
            page = $(this).data('page');
            var dateSort = $("#date-sort").attr("sort-type");
            initAddLeague(dateSort);

        });

        $('#page-size-AddLeague').on('change', function () {
            pageSize = $(this).val();
            page = 1;
            var dateSort = $("#date-sort").attr("sort-type");
            initAddLeague(dateSort);
        });

        $("#date-sort-AddLeague").on("click", function () {
            var dateType = $(this).attr("sort-type");
            if (!dateType) {
                dateType = "desc";
                GetDataCommentGrid(dateType);
                return;
            }

            dateType = dateType === "desc" ? "asc" : "desc";
            initAddLeague(dateType);
        });
    }

    function initSortLeague() {
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
        $("#filter").on("submit", function (e) {
            e.preventDefault();
            GetDataCommentGrid(null);
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
    adminManagementLeague.init();
});