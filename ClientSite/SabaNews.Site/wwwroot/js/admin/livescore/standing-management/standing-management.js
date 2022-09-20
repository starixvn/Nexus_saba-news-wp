var standingManagement = (function ($) {
    var leagueId = window.leagueId;
    var seasonId = window.seasonId;
    var search = '';
    function GetSeason(leagueId) {
        $.ajax({
            url: "/Admin/MatchManagement/GetSeason",
            type: 'POST',
            data: { leagueId },
            success: function (res) {
                $('#seasonFilter').html(res);
            }
        });
    }
    function GetDataGrid() {
        $.ajax({
            url: "/Admin/StandingManagement/GetDataGrid",
            type: 'POST',
            data: { seasonId, leagueId },
            success: function (res) {
                $('.data-grid').html(res);
                initAction();
                //initPaging();
                //initSortEvent();
            }
        });
    }

    function initAction() {
        $('.btn-Edit').on('click', function () {
            var url = $(this).data('url');
            $.ajax({
                type: 'GET',
                url: url,
                success: function (html) {
                    $('#EditStandingModal .title').html('Edit Staning');
                    $('#EditStandingModal .modal-body').html(html);
                    $('#EditStandingModal').modal('show');

                    $('#btn-save').on('click', function () {
                        $(this).prop('disabled', true);
                        var stageId = $(this).data('stageid');
                        var teamId = $('#editTeamId').val();
                        var teamNameVN = $('#editTeamNameVN').val();
                        var icon = $('#editIconVN')[0].files[0];
                        var position = $('#editPosition').val();
                        var currentIcon = $('#editIconVN').parent().find('.review_photo').attr('src');
                        var isDeletedIcon = currentIcon === '';
                        var formData = new FormData();
                        if (position === '') {
                            $('#editPosition').parent().find('.help-block').html('Position can not be empty');
                            $(this).prop('disabled', false);
                            return false;
                        }
                        
                        formData.append('seasonId', seasonId);
                        formData.append('stageId', stageId);
                        formData.append('teamId', teamId);
                        formData.append('icon', icon);
                        formData.append('position', position);
                        formData.append('teamNameVN', teamNameVN);
                        formData.append('isDeletedIcon', isDeletedIcon);
                        $.ajax({
                            type: 'POST',
                            url: "/Admin/StandingManagement/EditStanding",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                if (response.isSuccessful) {
                                    $('#EditStandingModal').modal('hide');
                                    GetSuccessPopup('#NoticeModal', 'Update Match successfully!');
                                    setTimeout(function () {
                                        $('#NoticeModal').modal('hide');
                                        GetDataGrid();
                                    }, 1000);
                                } else {
                                    $(this).prop('disabled', false);
                                    GetErrorPopup('#NoticeModal', 'An error occurred while add league, please try again');
                                }
                            }
                        })
                    });

                    $('.photo').on('change', function (e) {
                        var form = $(this).parent();
                        var files = $(this)[0].files[0];
                        var isValid = ValidatePhoto(files, $(this));
                        if (!isValid) {
                            CancelUploadHandler($(this).parent());
                            return false;
                        }
                        var review = $(this).parent().find('.review_photo');

                        var reader = new FileReader();
                        reader.readAsDataURL(files);
                        reader.onload = function (e) {
                            review.attr('src', e.target.result);
                            ChangePhotoHanlder(form);
                        };
                    });

                    $('.cancel-upload').on('click', function () {
                        var form = $(this).parent().parent();
                        CancelUploadHandler(form);
                        $(this).hide();
                    });

                    $('#editPosition').on('keyup', function () {
                        $(this).parent().find('.help-block').html('');
                    });
                }
            })
        });
    }

    function ChangePhotoHanlder(form) {
        $(form).find('.review_photo').show();
        $(form).find('.photo-error').hide();
        $(form).find('.txt-img').css('display', 'none');
        $(form).find('.cancel-upload').show();
    }

    function CancelUploadHandler(form) {
        $(form).find('.review_photo').attr('src', '');
        $(form).find('.review_photo').hide();
        $(form).find('.txt-img').css('display', 'flex');
        $(form).find('.cancel-upload').hide();
        $(form).find('.photo').val('');
    }

    function ValidatePhoto(files, self) {
        var isValid = true;
        var errorSection = self.parent().find('.photo-error');
        if (typeof files === "undefined") {
            errorSection.html("Please choose file first!");
            errorSection.show();
            return false;
        }
        var type_reg = /^image\/(jpg|png|jpeg|bmp|gif|ico)$/;
        var fsize = files.size,
            ftype = files.type,
            fname = files.name,
            fextension = fname.substring(fname.lastIndexOf('.') + 1);

        if (!type_reg.test(ftype)) {
            errorSection.html("Sorry, this file type is not permitted for security reasons!");
            errorSection.show();
            return false;
        } else if (fsize > 3145728) {
            errorSection.html("Exceeds the maximum upload size for this site.");
            errorSection.show();
            return false;
        }
        return isValid;
    }

    function initFilterBtn() {
        $("#submit-btn").on("click", function () {
            page = 1;
            pageSize = 10;
            leagueId = $('.league-select').val();
            seasonId = $('.season-select').val();
            GetDataGrid();
        });
        $('.league-select').on('change', function () {
            var currentLeagueId = $(this).val();
            GetSeason(currentLeagueId);
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
        $('.league-select').select2();
        GetSeason(leagueId);
        GetDataGrid();
        initFilterBtn();
    }

    return {
        init
    }
})(jQuery);


$(document).ready(function () {
    standingManagement.init();
});