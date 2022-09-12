var minigame = (function ($) {
    var isLogin = typeof checkLogin != 'undefined' ? checkLogin.toLowerCase() === 'true' : false;
    var teamId = 0;
    var teamName = "";
    function HandleSubmitNumber() {
        $('#membership-event-form').on('submit', function (e) {
            e.preventDefault();
            var luckyNumber = $('#luckyNumber').val();
            var isValid = ValidateFormEnterLuckynumber(luckyNumber);
            if (!isValid) {
                return false;
            }
            $.ajax({
                method: 'POST',
                url: '/WorldCup2022/CreateMinigameData',
                data: { luckyNumber, teamId },
                success: function (res) {
                    if (res.isSuccessful) {
                        $('#success').bind('hidden.bs.modal', function () {
                            location.reload();
                        });
                        $('#success').modal('show');
                    } else {
                        $('#notice .modal-body  .text-event-modal').html(res.message);
                        $('#notice').unbind('hidden.bs.modal');
                        $('#notice').modal('show');
                    }
                }
            });
        });

        $('.btn-choseTeam').on('click', function () {
            teamId = $(this).data("teamid");
            teamName = $(this).data("teamname");
        });

        $('.btn-submit-number').on('click', function () {
            var luckyNumber = $('#luckyNumber').val();

            var isValid = ValidateFormEnterLuckynumber(luckyNumber);
            if (!isValid) {
                return false;
            }
            var html = "<li>" + teamName + " sẽ vô địch World Cup 2022 và</li>";
            html += "<li>" + luckyNumber + " là số người có cùng dự đoán với b</li>";
            $('#register .review-number').html(html);
            $('#register').modal('show');
        });
        $('#register .btn-accept').on('click', function () {
            $('#membership-event-form').submit();
        });

        $(".input-event-number").keydown(function (e) {
            if (e.key === "Tab") {
                var numAttr = $(this).attr('id');
                var num = parseInt(numAttr[numAttr.length - 1]);
                if (num == 3) {
                    $('#number1').focus();
                    e.preventDefault();
                }
            }
        });

        $('.input-event-number').on('keypress', function (e) {
            $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
            if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                e.preventDefault();
            }
        });

        $('.input-event-number').on("input", function () {
            if ($(this).val().length == $(this).attr("maxlength")){
                $(this).next().focus();
            }
        });

        $('.input-event-number').on("click focus", function () {
            $(this).select();
        });

        $('.btn-reset-number').on('click', function () {
            $('.input-event-number').val('');
            $('#number1').focus();
        });
    }

    function ValidateFormEnterLuckynumber(luckyNumber) {
        if (!isLogin) {
            $('#notice .modal-body  .text-event-modal').html("Vui lòng đăng nhập để tham gia sự kiện!");
            $('#notice').unbind('hidden.bs.modal');
            $('#notice').modal('show');
            return false;
        }
        if (!$.isNumeric(luckyNumber) && teamName == "") {
            $('#notice .modal-body  .text-event-modal').html("Vui lòng chọn đội tuyển tên và nhập số người cùng dự đoán với bạn!");
            $('#notice').unbind('hidden.bs.modal');
            $('#notice').modal('show');
            return false;
        }
        if (teamName == "") {
            $('#notice .modal-body  .text-event-modal').html(" Vui lòng chọn tên đội tuyển!");
            $('#notice').unbind('hidden.bs.modal');
            $('#notice').modal('show');
            return false;
        }
        if (!$.isNumeric(luckyNumber)) {
            $('#notice .modal-body  .text-event-modal').html(" Vui lòng nhập số người cùng dự đoán với bạn!");
            $('#notice').unbind('hidden.bs.modal');
            $('#notice').modal('show');
            return false;
        }
        if (luckyNumber < 0 && luckyNumber > 999999) {
            $('#notice .modal-body  .text-event-modal').html("Số may mắn phải là số nằm trong khoảng từ 000 đến 999999!");
            $('#notice').unbind('hidden.bs.modal');
            $('#notice').modal('show');
            return false;
        }
        return true;
    }

    function HandleUI() {
        $('#btn-register').on('click', function () {
            $('#register').modal('show');
        })
        $('#btn-guide').on('click', function () {
            $('#guide').modal('show');
        })

        $(".btn-accept ").click(function () {
            $('html, body').animate({
                scrollTop: $("#field-number-event").offset().top
            }, 500);
        });


        $('.click-number-event').click(function () {
            $('#joining').modal('show');
            //const windowHeight = window.innerHeight;
            //const blockNumber = $('.field-number');
            //const blockNumberHeight = blockNumber.innerHeight();
            //const heightElement = windowHeight / 2 - blockNumberHeight / 3;
            //$('html, body').animate({
            //    scrollTop: blockNumber.offset().top - heightElement
            //}, 500)
        })

        $('.click-list-rigister').click(function () {
            $('html, body').animate({
                scrollTop: $("#list-rigister-event").offset().top
            }, 500);
        })

        $('.header-control ul li a').click(function () {
            $('.header-control ul li a').removeClass("active");
            $(this).addClass("active");
        });

        $('.btn-accept').click(function () {
            $('#register').modal('hide');
        })

        $(window).click(function (e) {
            var elmAuth = $(e.target).parent().parent();
            if (!elmAuth.is('.after-login') && !elmAuth.is('.auth-section')) {
                if ($('.profile-login').hasClass('active-login-review')) {
                    $('.profile-login').removeClass('active-login-review');
                }
            }
        });
    }

    var page = 1;
    var pageSize = 10;
    function GetDataMinigame() {
        $.ajax({
            type: "GET",
            url: "/WorldCup2022/GetMinigameData",
            data: { page, pageSize },
            success: function (res) {
                $('.data-grid').html(res);
                initPaging();
            }
        });
    }

    function initPaging() {
        $('.paging').on('click', function () {
            page = $(this).data('page');
            GetDataMinigame();
        });
    }

    function init() {
        HandleUI();
        HandleSubmitNumber();
        GetDataMinigame();
    }

    return {
        init
    }

})(jQuery);


$(document).ready(function () {
    minigame.init();
});