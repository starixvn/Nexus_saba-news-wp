var originIndex = 0, nextIndex = 0;
var adminManagementLeague = (function ($) {
    var page = 1, pageSize = 10, url = "", typeSort = 1;
    var isMajor = -1;
    function GetDataLeagueExtraGrid(dateSort) {
        var search = document.getElementById("search").value;
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
            var name = $(this).data('name');
            $.ajax({
                type: 'GET',
                url: url,
                success: function (html) {
                    $('#EditLeagueContent .title').html('League Editing / ' + name);
                    $('#EditLeagueContent .modal-body').html(html);
                    $('#EditLeagueContent').modal('show');

                    $('.photo').on('change', function (e) {
                        var form = $(this).parent();
                        var files = $(this)[0].files[0];
                        var isValid = ValidatePhoto(files, $(this));
                        if (!isValid) {
                            CancelUploadHandler($(this).parent());
                            return false;
                        }
                        var review = $(this).parent().parent().parent().parent().find('.review_photo');

                        var reader = new FileReader();
                        reader.readAsDataURL(files);
                        reader.onload = function (e) {
                            review.attr('src', e.target.result);
                            ChangePhotoHanlder(form);
                        };
                    });

                    $('.cancel-upload').on('click', function () {
                        var form = $(this).parent();
                        form.find('.review_photo').attr('src', "/css/images/logo-dummy.png");
                    });

                    $('#btn-save').on('click', function () {
                        document.getElementById("btn-save").style.display = "none";
                        var LeagueId = $(this).data('id');
                        var IsMajor = document.getElementById("editMajor").checked;
                        var Priority = document.getElementById("editPriority").value;
                        var LeagueNameVN = document.getElementById("editLeagueNameVN").value;
                        var CountryVN = document.getElementById("editCountryVN").value;
                        var Icon = $('#editLeagueIcon')[0].files[0];
                        var currentIcon = $('#editLeagueIcon').parent().parent().parent().parent().find('.review_photo').attr('src');

                        var IsDeletedIcon = currentIcon == "/css/images/logo-dummy.png" ? true : false;

                        var formData = new FormData();
                        formData.append('leagueId', LeagueId);
                        formData.append('isMajor', IsMajor);
                        formData.append('priority', Priority);
                        formData.append('leagueNameVN', LeagueNameVN);
                        formData.append('countryVN', CountryVN);
                        formData.append('icon', Icon);
                        formData.append('isDeletedIcon', IsDeletedIcon);

                        $.ajax({
                            type: 'POST',
                            url: "/Admin/ManagementLeague/EditLeague",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                if (response.isSuccessful) {
                                    $('#EditLeagueContent').modal('hide');

                                    $('#NoticeModal' + ' .modal-title').removeClass('title-error').addClass('title-success');
                                    $('#NoticeModal' + ' .title').html('<i class="icon-title icon-success-admin"></i> Successful');
                                    $('#NoticeModal' + ' .modal-body').html('Update the league successfully!');
                                    $('#NoticeModal').modal('show');

                                    setTimeout(function () {
                                        $('#NoticeModal').modal('hide');
                                        GetDataLeagueExtraGrid();
                                    }, 1000);
                                } else {
                                    document.getElementById("btn-save").style.display = "inline-block";

                                    $('#NoticeModal' + ' .modal-title').removeClass('title-success').addClass('title-error');
                                    $('#NoticeModal' + ' .title').html('<i class="icon-title icon-error-admin"></i> Error');
                                    $('#NoticeModal' + ' .modal-body').html('An error occurred while add league, please try again');
                                    $('#NoticeModal').modal('show');
                                }
                            }
                        })
                    });
                }
            })
        });
    }

    function ChangePhotoHanlder(form) {
        var review = $(form).parent().parent().parent();
        review.find('.cancel-upload').show();
        review.find('.review_photo').show();
        $(form).find('.photo-error').hide();

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
                            document.getElementById("addCountryEN").value = response.countryEN;
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

                                $('#NoticeModal' + ' .modal-title').removeClass('title-error').addClass('title-success');
                                $('#NoticeModal' + ' .title').html('<i class="icon-title icon-success-admin"></i> Successful');
                                $('#NoticeModal' + ' .modal-body').html('League was successfully added!');
                                $('#NoticeModal').modal('show');

                                setTimeout(function () {
                                    $('#NoticeModal').modal('hide');
                                    typeSort = 1;
                                    GetDataLeagueExtraGrid();
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
        $('.btn_league').click(function () {
            initAddLeague();
        });

        $('.btn_major_league').click(function () {
            $('#btn_major_league').addClass("btn_show_league");
            $('#btn_hot_league').removeClass("btn_show_league");

            document.getElementById('filter').style.display = 'flex';
            document.getElementById('leagueshot').style.display = 'none';
            document.getElementById('data-grid').style.display = 'block';
        });

        $('.btn_hot_league').click(function () {
            $('#btn_major_league').removeClass("btn_show_league");
            $('#btn_hot_league').addClass("btn_show_league");

            document.getElementById('filter').style.display = 'none';
            document.getElementById('leagueshot').style.display = 'flex';
            document.getElementById('data-grid').style.display = 'none';
        });

        $(".js-example-basic-multiple").on("change", function () {
            page = 1;
            pageSize = 10;
            typeSort = 1;
            GetDataLeagueExtraGrid(null);
        });

        $("#search").keyup(function () {
            isMajor = -1;
            GetDataLeagueExtraGrid(null);
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
                            GetDataLeagueExtraGrid();
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
        $('.js-example-basic-multiple').select2(); 
        document.getElementById('leagueshot').style.display = 'none';

       

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
                var hotleagues = $(this).find("tr").each(function (index) {
                    if (index > 0) {
                        $(this).find("td").eq(0).html(index);
                    }
                });
                nextIndex = ui.item[0].children[0].innerText;
                ui.item.removeClass("selected");
            },
            receive: function (e, ui) {
                $(this).find("tbody").append(ui.item);
            }
        });
    });
});