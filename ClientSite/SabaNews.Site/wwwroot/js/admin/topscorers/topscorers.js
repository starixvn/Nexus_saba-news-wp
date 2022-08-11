var adminManagementTopScorers = (function ($) {
    function GetDataTopScorersGrid() {
        var search = document.getElementById("search").value;
        $.ajax({
            url: "/Admin/TopScorerManagement/GetTopScorersData",
            type: 'POST',
            data: { search },
            success: function (res) {
                $('.data-grid').html(res);
                $('.btn-Edit').on('click', function () {
                    var url = $(this).data('url');
                    $.ajax({
                        type: 'GET',
                        url: url,
                        success: function (html) {
                            $('#EditPlayerContent .title').html('Edit Player');
                            $('#EditPlayerContent .modal-body').html(html);
                            $('#EditPlayerContent').modal('show');

                            $("#TeamFullNameEN").select2();
                            $("#PlayerFullNameEN").select2();

                            $('#btn-save').on('click', function () {
                                document.getElementById("btn-save").style.display = "none";
                                var url = $(this).data('url');
                                var RowNum = $(this).data('row');
                                var Top = document.getElementById("editTop").value;
                                var Goals = document.getElementById("editGoals").value;
                                var Assists = document.getElementById("editAssist").value;
                                var TeamId = document.getElementById("TeamFullNameEN").value;
                                var PlayerId = document.getElementById("PlayerFullNameEN").value;
                                $.ajax({
                                    type: 'POST',
                                    url: url,
                                    data: { Top, Goals, Assists, TeamId, PlayerId, RowNum },
                                    success: function (response) {
                                        if (response.isSuccessful) {
                                            $('#EditPlayerContent').modal('hide');
                                            GetSuccessPopup('#NoticeModal', 'Update Player successfully!');
                                            setTimeout(function () {
                                                $('#NoticeModal').modal('hide');
                                                GetDataTopScorersGrid();
                                            }, 1000);
                                        } else {
                                            document.getElementById("btn-save").style.display = "inline-block";
                                            GetErrorPopup('#NoticeModal', 'An error occurred while add player, please try again');
                                        }
                                    }
                                })
                            });
                        }
                    });
                });
            }
        });
    }

    function initFilterBtn() {
        $("#search").keyup(function () {
            GetDataTopScorersGrid();
        });

        $('.btn_player').click(function () {
            $.ajax({
                type: 'Get',
                url: '/Admin/TopScorerManagement/AddPlayer',
                success: function (html) {
                    $('#AddPlayerContent .title').html('Add Player');
                    $('#AddPlayerContent .modal-body').html(html);
                    $('#AddPlayerContent').modal('show');


                    $("#TeamFullNameEN").on("change", function () {
                        var teamId = document.getElementById("TeamFullNameEN").value;
                        $.ajax({
                            type: 'Get',
                            url: '/Admin/TopScorerManagement/GetPlayerByTeamId',
                            data: { teamId},
                            success: function (data) {
                                $("#PlayerFullNameEN").empty();
                                data.forEach(function (obj) {
                                    $('#PlayerFullNameEN').append($('<option></option>').val(obj.playerId).html(obj.fullNameEN));
                                })
                            }
                        })
                    });

                    $("#TeamFullNameEN").select2();
                    $("#PlayerFullNameEN").select2();

                    $('#btn-save-add').on('click', function () {
                        document.getElementById("btn-save-add").style.display = "none";
                        var url = $(this).data('url');                       
                        var Top = document.getElementById("addTop").value;
                        var Goals = document.getElementById("addGoals").value;
                        var Assists = document.getElementById("addAssist").value;
                        var TeamId = document.getElementById("TeamFullNameEN").value;
                        var PlayerId = document.getElementById("PlayerFullNameEN").value;
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: { Top, Goals, Assists, TeamId, PlayerId },
                            success: function (response) {
                                if (response.isSuccessful) {
                                    $('#AddPlayerContent').modal('hide');
                                    GetSuccessPopup('#NoticeModal', 'Add player successfully!');
                                    setTimeout(function () {
                                        $('#NoticeModal').modal('hide');
                                        GetDataTopScorersGrid();
                                    }, 1000);
                                } else {
                                    document.getElementById("btn-save-add").style.display = "inline-block";
                                    GetErrorPopup('#NoticeModal', 'An error occurred while add player, please try again');
                                }
                            }
                        });
                    });
                }
            })
        });
    }

    function init() {
        GetDataTopScorersGrid();
        initFilterBtn();
    }

    return {
        init
    }
})(jQuery);


$(document).ready(function () {
    adminManagementTopScorers.init();
});