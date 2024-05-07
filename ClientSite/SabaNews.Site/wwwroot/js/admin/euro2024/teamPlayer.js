var adminEuro2024TeamPlayer = (function ($) {
    var teamId = window.teamId;

    function GetDataGrid() {
        $.ajax({
            url: "/Admin/Euro2024Management/GetPlayerGrid",
            type: 'POST',
            data: { teamId },
            success: function (res) {
                $('.data-grid').html(res);
                $('.btn-Edit').on('click', function () {
                    var url = $(this).data('url');
                    $.ajax({
                        type: 'GET',
                        url: url,
                        data: {leagueId, seasonId},
                        success: function (html) {
                            $('#EditPlayerContent .title').html('Edit Player');
                            $('#EditPlayerContent .modal-body').html(html);
                            $('#EditPlayerContent').modal('show');

                            $("#TeamFullNameEN").on("change", function () {
                                var teamId = document.getElementById("TeamFullNameEN").value;
                                $.ajax({
                                    type: 'Get',
                                    url: '/Admin/TopScorerManagement/GetPlayerByTeamId',
                                    data: { teamId },
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
                                    data: { leagueId, seasonId, Top, Goals, Assists, TeamId, PlayerId, RowNum },
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

    function HandleCreatePlayer() {
        $('.btn-create-player').on('click', function() {
            $('#CreatePlayerModel').modal('show');
        });
        $('.btn-save-create').on('click', function() {
            let formData = new FormData();
            formData.append('teamId', teamId);
            formData.append('playerName', $('input[name=player-name]').val());
            formData.append('playerFullName', $('input[name=player-full-name]').val());
            formData.append('birthday', $('input[name=birthday]').val());
            formData.append('shirtNumber', $('input[name=shirt-number]').val());
            formData.append('club', $('input[name=club]').val());
            formData.append('position', $('.position-select').val());
            formData.append('icon', $('.addPlayerIcon')[0].files[0]);
            $.ajax({
                type: 'POST',
                url: "/Admin/Euro2024Management/CreatePlayer",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response)
                {
                    console.log(response);
                    if (response.isSuccessful) {
                        $('#CreatePlayerModel').modal('hide');
                        GetSuccessPopup('#NoticeModal', 'Add player successfully!');
                        setTimeout(function () {
                            $('#NoticeModal').modal('hide');
                            GetDataGrid();
                        }, 1000);
                    } else {
                        document.getElementById("btn-save-add").style.display = "inline-block";
                        GetErrorPopup('#NoticeModal', 'An error occurred while add player, please try again');
                    }
                }

            })
        })
    }

    function HandlePhoto() {
        $('.photo').on('change', function (e) {
            let form = $(this).parent();
            let files = $(this)[0].files[0];
            let isValid = ValidatePhoto(files, $(this));
            if (!isValid) {
                CancelUploadHandler($(this).parent());
                return false;
            }
            let review = $(this).parent().parent().parent().parent().find('.review_photo');

            let reader = new FileReader();
            reader.readAsDataURL(files);
            reader.onload = function (e) {
                review.attr('src', e.target.result);
                ChangePhotoHanlder(form);
            };
        });
    }

    function ChangePhotoHanlder(form) {
        let review = $(form).parent().parent().parent();
        review.find('.cancel-upload').show();
        review.find('.review_photo').show();
        $(form).find('.photo-error').hide();

    }

    function ValidatePhoto(files, self) {
        let isValid = true;
        let errorSection = self.parent().find('.photo-error');
        if (typeof files === "undefined") {
            errorSection.html("Please choose file first!");
            errorSection.show();
            return false;
        }
        let type_reg = /^image\/(jpg|png|jpeg|bmp|gif|ico)$/;
        let fsize = files.size,
            ftype = files.type,
            fname = files.name,
            fextension = fname.substring(fname.lastIndexOf('.') + 1);

        if (!type_reg.test(ftype)) {
            errorSection.html("Just support for jpg, png, jpeg, gif file type!");
            errorSection.show();
            return false;
        } else if (fsize > 3145728) {
            errorSection.html("Exceeds the maximum upload size for this site.");
            errorSection.show();
            return false;
        }
        return isValid;
    }


    function init() {
        GetDataGrid();
        HandleCreatePlayer();
        HandlePhoto();
    }

    return {
        init
    }
})(jQuery);


$(document).ready(function () {
    adminEuro2024TeamPlayer.init();
});