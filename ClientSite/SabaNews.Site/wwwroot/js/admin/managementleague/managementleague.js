var adminManagementLeague = (function ($) {
    var page = 1, pageSize = 10, url = "", typeSort = 1;
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

        $('#btn-sort-createdDate').on('click', function () {
            if (typeSort == 3) {
                typeSort = 4;
            }
            else {
                typeSort = 3;
            }
            GetDataLeagueExtraGrid(null);
        });

        $('.btn-Delete').on('click', function () {
            url = $(this).data('url');
            $('#ConfirmModal .confirm-action').data('action', 'remove');
            $('#ConfirmModal .modal-body').html('Are you sure you want to remove this league ?');
            $('#ConfirmModal').modal('show');
        });

        $('.btn-Edit').on('click', function () {
            var url = $(this).data('url');
            $.ajax({
                type: 'GET',
                url: url,
                success: function (html) {
                    $('#EditLeagueContent .title').html('Edit League');
                    $('#EditLeagueContent .modal-body').html(html);
                    $('#EditLeagueContent').modal('show');

                    $('#btn-save').on('click', function () {
                        document.getElementById("btn-save").style.display = "none";
                        var LeagueId = $(this).data('id');
                        var IsMajor = document.getElementById("editMajor").checked;
                        var Priority = document.getElementById("editPriority").value;
                        var LeagueNameVN = document.getElementById("editLeagueNameVN").value;
                        var CountryVN = document.getElementById("editCountryVN").value;
                        $.ajax({
                            type: 'POST',
                            url: "/Admin/ManagementLeague/EditLeague",
                            data: { LeagueId, IsMajor, Priority, LeagueNameVN, CountryVN },
                            success: function (response) {
                                if (response.isSuccessful) {
                                    $('#EditLeagueContent').modal('hide');
                                    GetSuccessPopup('#NoticeModal', 'Update league successfully!');
                                    setTimeout(function () {
                                        $('#NoticeModal').modal('hide');
                                        GetDataLeagueExtraGrid();
                                    }, 1000);
                                } else {
                                    document.getElementById("btn-save").style.display = "inline-block";
                                    GetErrorPopup('#NoticeModal', 'An error occurred while add league, please try again');
                                }
                            }
                        })
                    });
                }
            })
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
            success: function (html) {
                $('#AddLeagueContent .title').html('Add League');
                $('#AddLeagueContent .modal-body').html(html);
                $('#AddLeagueContent').modal('show');

                $("#leagueFullNameEN").select2();

                $("#leagueFullNameEN").on("change", function () {
                    var LeagueId = document.getElementById("leagueFullNameEN").value;
                    $.ajax({
                        type: 'POST',
                        url: "/Admin/ManagementLeague/GetLeagueById",
                        data: { LeagueId },
                        success: function (response) {
                            document.getElementById("addCountryVN").value = response.countryVN;
                            document.getElementById("addLeagueNameVN").value = response.leagueNameVN;
                            document.getElementById("addLeagueId").value = response.leagueId;
                        }
                    });
                });

                $("#AddLeagueContent").on("hide.bs.modal", function () {
                    typeSort = 1;
                    GetDataLeagueExtraGrid();
                });

                $('#btn-save-add').on('click', function () {
                    document.getElementById("btn-save-add").style.display = "none";
                    url = $(this).data('url');
                    var LeagueId = document.getElementById("leagueFullNameEN").value;
                    var IsMajor = document.getElementById("addMajor").checked;
                    var Priority = document.getElementById("addPriority").value;
                    var LeagueNameVN = document.getElementById("addLeagueNameVN").value;
                    var CountryVN = document.getElementById("addCountryVN").value;
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: { LeagueId, IsMajor, Priority, LeagueNameVN, CountryVN },
                        success: function (response) {
                            if (response.isSuccessful) {
                                $('#AddLeagueContent').modal('hide');
                                GetSuccessPopup('#NoticeModal', 'Add league successfully!');
                                setTimeout(function () {
                                    $('#NoticeModal').modal('hide');
                                    typeSort = 1;
                                    GetDataLeagueExtraGrid();
                                }, 1000);
                            } else {
                                document.getElementById("btn-save-add").style.display = "inline-block";
                                GetErrorPopup('#NoticeModal', 'An error occurred while add league, please try again');
                            }
                        }
                    });
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
            page = 1;
            pageSize = 10;
            typeSort = 1;
            GetDataLeagueExtraGrid(null);
        });
        $('.btn_league').click(function () {
            initAddLeague();
        });
    }

    function HandleModal() {
        $('.confirm-action').on('click', function () {
            if (url == "") {
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                success: function (response) {
                    $('#ConfirmModal').modal('hide');
                    if (response.isSuccessful) {
                        GetSuccessPopup('#NoticeModal', 'Remove league successfully!');
                        setTimeout(function () {
                            $('#NoticeModal').modal('hide');
                            GetDataLeagueExtraGrid();
                        }, 1000);
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