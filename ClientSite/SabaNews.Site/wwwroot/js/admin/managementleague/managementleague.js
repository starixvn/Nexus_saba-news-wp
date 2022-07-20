var adminManagementLeague = (function ($) {
    var page = 1;
    var pageSize = 10;
    var url = "";
    function GetDataCommentGrid(dateSort) {
        var search = document.getElementById("search").value;
        var isMajor = document.getElementById("major").value;
        $.ajax({
            url: "/Admin/ManagementLeague/GetManagementLeagueData",
            type: 'POST',
            data: { page, pageSize, search, dateSort, isMajor },
            success: function (res) {
                $('.data-grid').html(res);
                initLeague();
                initPaging();
                initSortLeague();
                initAddLeague();
            }
        });
    }

    function initLeague() {
        $('.isMajorLeague').on('click', function () {
            var url = $(this).data('url');
            var isMajor = $(this).prop("checked");
            $.ajax({
                type: 'POST',
                url: url,
                data: { isMajor }
            });
        });
        $(".priority-league").blur(function () {
            var url = $(this).data('url');
            var priority = document.getElementById("textPriority").value;
            $.ajax({
                type: 'POST',
                url: url,
                data: { priority }
            });
        });
        $('.btn-Delete').on('click', function () {
            url = $(this).data('url');
            $('#ConfirmModal .confirm-action').data('action', 'remove');
            $('#ConfirmModal .modal-body').html('Are you sure you want to remove this league ?');
            $('#ConfirmModal').modal('show');
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
                url: '/Admin/ManagementLeague/GetAllLeagues',
                success: function (html) {
                    $('#AddLeagueContent .title').html('Add League');
                    $('#AddLeagueContent .modal-body').html(html);
                    $('#AddLeagueContent').modal('show');

                    $("#AddLeagueContent").on("hidden.bs.modal", function () {
                        GetDataCommentGrid();
                    });

                    $('.btn-addleague').on('click', function () {
                        url = $(this).data('url');
                        var leagueId = $(this).data('id');
                        $.ajax({
                            type: 'POST',
                            url: url,
                            success: function (response) {
                                if (response.result.isSuccessful) {
                                    document.getElementById(leagueId).style.display = "none";
                                } else {
                                    GetErrorPopup('#NoticeModal', 'An error occurred while add league, please try again');
                                }
                            }
                        });
                    });

                    $('#submit-btnAddLeague').on('click', function () {
                        var search = document.getElementById("searchLeague").value;
                        $.ajax({
                            url: "/Admin/ManagementLeague/GetAllLeagues2",
                            type: 'POST',
                            success: function (res) {
                                $('.data-grid').html(res);
                            }
                        });
                    });
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
        $("#submit-btn").on("click", function () {
            GetDataCommentGrid(null);
        });
    }

    function HandleModal() {
        $('.confirm-action').on('click', function () {
            $('#ConfirmModal').modal('hide');
            if (url == "") {
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                success: function (response) {
                    if (response.result.isSuccessful) {
                        GetSuccessPopup('#NoticeModal', 'Remove league successfully.');
                        setTimeout(function () {
                            GetDataCommentGrid();
                            $('#NoticeModal').modal('hide');
                        }, 2000);
                    } else {
                        GetErrorPopup('#NoticeModal', 'An error occurred while remove league, please try again');
                    }
                }
            })
            url = "";
        });
    }

    function init() {
        GetDataCommentGrid();
        initFilterBtn();
        HandleModal();
    }

    return {
        init
    }
})(jQuery);


$(document).ready(function () {
    adminManagementLeague.init();
});