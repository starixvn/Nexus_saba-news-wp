var originIndex = 0, nextIndex = 0;
var adminManagementHotLeague = (function ($) {
    var url = "";
    function GetDataHotLeagueExtraGrid() {
        window.location.href = window.location.origin + "/Admin/ManagementLeague/GetManagementLeagueHotData";
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
                            document.getElementById("addCountryEN").value = response.countryEN;
                            document.getElementById("addCountryVN").value = response.countryVN;
                            document.getElementById("addLeagueNameVN").value = response.leagueNameVN;
                            document.getElementById("addLeagueId").value = response.leagueId;
                        }
                    });
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

                                $('#NoticeModal' + ' .modal-title').removeClass('title-error').addClass('title-success');
                                $('#NoticeModal' + ' .title').html('<i class="icon-title icon-success-admin"></i> Successful');
                                $('#NoticeModal' + ' .modal-body').html('League was successfully added!');
                                $('#NoticeModal').modal('show');

                                setTimeout(function () {
                                    $('#NoticeModal').modal('hide');
                                    GetDataHotLeagueExtraGrid();
                                }, 1000);
                            } else {
                                document.getElementById("btn-save-add").style.display = "inline-block";

                                $('#NoticeModal' + ' .modal-title').removeClass('title-success').addClass('title-error');
                                $('#NoticeModal' + ' .title').html('<i class="icon-title icon-error-admin"></i> Error');
                                $('#NoticeModal' + ' .modal-body').html('An error occurred while add league, please try again');
                                $('#NoticeModal').modal('show');
                            }
                        }
                    });
                });
            }
        })
    }

    function initFilterBtn() {
        $('.btn_league').click(function () {
            initAddLeague();
        });

        $('.btn_major_league').click(function () {
            window.location.href = window.location.origin + "/Admin/ManagementLeague/Index";
        });

        $('.btn-Delete').on('click', function () {
            url = $(this).data('url');
            $('#ConfirmModal .confirm-action').data('action', 'remove');
            $('#ConfirmModal .modal-body').html('Are you sure you want to remove this league ?');
            $('#ConfirmModal').modal('show');
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
                        $('#NoticeModal' + ' .modal-title').removeClass('title-error').addClass('title-success');
                        $('#NoticeModal' + ' .title').html('<i class="icon-title icon-success-admin"></i> Successful');
                        $('#NoticeModal' + ' .modal-body').html('Remove league successfully!');
                        $('#NoticeModal').modal('show');

                        setTimeout(function () {
                            $('#NoticeModal').modal('hide');
                            GetDataHotLeagueExtraGrid();
                        }, 1000);
                    } else {
                        $('#NoticeModal' + ' .modal-title').removeClass('title-success').addClass('title-error');
                        $('#NoticeModal' + ' .title').html('<i class="icon-title icon-error-admin"></i> Error');
                        $('#NoticeModal' + ' .modal-body').html('An error occurred while remove league, please try again');
                        $('#NoticeModal').modal('show');
                    }
                }
            })
            url = "";
        });
    }

    function init() {
        initFilterBtn();
        HandleModal();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    adminManagementHotLeague.init();
    $(function () {
        $("#tblLocations").sortable({
            items: 'tr:not(tr:first-child)',
            cursor: 'pointer',
            axis: 'y',
            dropOnEmpty: false,
            start: function (e, ui) {
                originIndex = ui.item[0].children[0].innerText;
                ui.item.addClass("selected");
            },
            stop: function (e, ui) {
                $(this).find("tr").each(function (index) {
                    if (index > 0) {
                        $(this).find("td").eq(0).html(index);
                    }
                });
                nextIndex = ui.item[0].children[0].innerText;
                ui.item.removeClass("selected");
                $.ajax({
                    type: 'POST',
                    url: "/Admin/ManagementLeague/UpdatePriority",
                    data: { originIndex, nextIndex }
                });
            },
            receive: function (e, ui) {
                $(this).find("tbody").append(ui.item);
            }
        });
    });
});