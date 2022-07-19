var adminManagementLeague = (function ($) {
    var page = 1;
    var pageSize = 10;
    function GetDataCommentGrid(dateSort) {
        var search = document.getElementById("search").value;
        var isMajor = document.getElementById("major").value;
        $.ajax({
            url: "/Admin/ManagementLeague/GetManagementLeagueData",
            type: 'POST',
            data: { page, pageSize, search, dateSort, isMajor },
            success: function (res) {
                $('.data-grid').html(res);
                $('#checkMajor').on('click', function () {
                    var url = $(this).data('url');
                    var check = $(this).data('check');
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {check}
                    });
                });
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