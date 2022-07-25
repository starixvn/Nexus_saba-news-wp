var adminManagementLeague = (function ($) {
    var page = 1, pageSize = 10, url = "", pageAddLeague = 1, pageSizeAddLeague = 10, searchAddleague = "", typeSort = 3;
    function GetDataLeagueExtraGrid(dateSort) {
        var search = document.getElementById("search").value;
        var isMajor = document.getElementById("major").value;
        $.ajax({
            url: "/Admin/ManagementLeague/GetManagementLeagueData",
            type: 'POST',
            data: { page, pageSize, search, dateSort, isMajor, typeSort },
            success: function (res) {
                $('.data-grid').html(res);
                initLeague();
                initPaging();
                initSortLeague();                
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
        $('#btn-sort-priority').on('click', function () {            
            if (typeSort == 1) {
                typeSort = 2;
            }
            else {
                typeSort = 1;
            }
            GetDataLeagueExtraGrid(null);
        });

        $('#btn-sort-createdDate').on('click', function () {
            if (typeSort == 3) {
                typeSort = 4;
            }
            else {
                typeSort = 3;
            }
            GetDataLeagueExtraGrid(null);
        });

        $(".priority-league").blur(function () {
            var url = $(this).data('url');
            var id = $(this).data('id');
            var priority = document.getElementById(id).value;
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
            GetDataLeagueExtraGrid(dateSort);

        });

        $('#page-size').on('change', function () {
            pageSize = $(this).val();
            page = 1;
            var dateSort = $("#date-sort").attr("sort-type");
            GetDataLeagueExtraGrid(dateSort);
        });
    }

    function initAddLeague(dateSort) {
        $.ajax({
            type: 'POST',
            url: '/Admin/ManagementLeague/GetAllLeagues',
            data: { pageAddLeague, pageSizeAddLeague, searchAddleague, dateSort },
            success: function (html) {
                $('#AddLeagueContent .title').html('Add League');
                $('#AddLeagueContent .modal-body').html(html);
                $('#AddLeagueContent').modal('show');

                $("#AddLeagueContent").on("hide.bs.modal", function () {
                    pageAddLeague = 1;
                    pageSizeAddLeague = 10;
                    searchAddleague = "";
                    typeSort = 3;
                    GetDataLeagueExtraGrid();
                });

                $('.btn-addleague').on('click', function () {
                    url = $(this).data('url');
                    var leagueId = $(this).data('id');
                    $.ajax({
                        type: 'POST',
                        url: url,
                        success: function (response) {
                            if (response.isSuccessful) {
                                document.getElementById(leagueId).style.display = "none";
                            } else {
                                GetErrorPopup('#NoticeModal', 'An error occurred while add league, please try again');
                            }
                        }
                    });
                });

                $('#submit-btnAddLeague').on('click', function () {
                    searchAddleague = document.getElementById("searchLeague").value;
                    initAddLeague(null);
                });

                $('#AddLeagueContent .paging').on('click', function () {
                    pageAddLeague = $(this).data('page');
                    var dateSort = $("#date-sort").attr("sort-type");
                    initAddLeague(dateSort);
                });

                $('#AddLeagueContent #page-size').on('change', function () {
                    pageSizeAddLeague = $(this).val();
                    pageAddLeague = 1;
                    var dateSort = $("#date-sort").attr("sort-type");
                    initAddLeague(dateSort);
                });
            }
        })
    }

    function initSortLeague() {
        $("#date-sort").on("click", function () {
            var dateType = $(this).attr("sort-type");
            if (!dateType) {
                dateType = "desc";
                GetDataLeagueExtraGrid(dateType);
                return;
            }

            dateType = dateType === "desc" ? "asc" : "desc";
            GetDataLeagueExtraGrid(dateType);
        });
    }

    function initFilterBtn() {
        $("#submit-btn").on("click", function () {
            GetDataLeagueExtraGrid(null);
        });
        $('.btn_league').click(function () {
            initAddLeague();
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
                    if (response.isSuccessful) {
                        GetSuccessPopup('#NoticeModal', 'Remove league successfully.');
                        setTimeout(function () {
                            GetDataLeagueExtraGrid();
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
        GetDataLeagueExtraGrid();
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