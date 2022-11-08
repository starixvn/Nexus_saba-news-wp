var minigame = (function ($) {
    var isLogin = typeof checkLogin != 'undefined' ? checkLogin.toLowerCase() === 'true' : false;
    const longs = [
        "10/31/2022, 3:05:00 PM",
        "11/22/2022, 8:00:00 AM",
        "11/26/2022, 8:00:00 AM",
        "11/30/2022, 8:00:00 AM"
    ];
    const second = 1000,
        minute = second * 60,
        hour = minute * 60,
        day = hour * 24;

    function HandleUI() {
        $('#btn-register').on('click', function () {
            $('#register').modal('show');
        })
        $('#btn-guide').on('click', function () {
            $('#guide').modal('show');
        })

        $('.btn-nav-guide').on('click', function () {
            $('#guide').modal('show');
        })

        $(".btn-accept ").click(function () {
            $('html, body').animate({
                scrollTop: $("#field-number-event").offset().top
            }, 500);
        });

        $('.btn-home').click(function () {
            $('html, body').animate({ scrollTop: 0 }, 1000);
        });

        $('.btn-nav-prediction').click(function () {
            if (!isLogin) {
                $('#joining').modal('show');
            }
            else {
                window.location = window.location.origin + "/world-cup-2022/su-kien-du-doan-sung-nhan-qua-khung/chi-tiet";
            }
        });

        $('.stage-votes').click(function () {
            if (!isLogin) {
                $('#joining').modal('show');
            }
            else {
                var stage = $(this).data("stage");
                window.location = window.location.origin + "/world-cup-2022/su-kien-du-doan-sung-nhan-qua-khung/giai-doan/" + stage;
            }
        });

        $('.click-number-event').click(function () {
            if (!isLogin) {
                $('#joining').modal('show');
            }
            else {
                document.getElementById("stage-minigame").scrollIntoView();
            }
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

    function countDownTime() {
        for (let i = 0; i < 4; i++) {
            var statusStage = getStatusStage(i);
            if (statusStage == "Sắp diễn ra" || statusStage == "Sắp kết thúc") {
                let timeCountDown = $("#stage" + (i + 1).toString()).data('timecountdown');
                let countDown = new Date(timeCountDown).getTime(),
                    x = setInterval(function () {
                        let now = new Date().getTime(),
                            distance = countDown - now;

                        let hourShow = Math.floor((distance % (day)) / (hour)),
                            minuteShow = Math.floor((distance % (hour)) / (minute)),
                            secondShow = Math.floor((distance % (minute)) / second)

                        document.getElementById('hours' + (i + 1).toString()).innerHTML = `<div class="clock-time">(${showTimeCountDown(hourShow)}:</div>`,
                            document.getElementById('minutes' + (i + 1).toString()).innerHTML = `<div class="clock-time">${showTimeCountDown(minuteShow)}:`,
                            document.getElementById('seconds' + (i + 1).toString()).innerHTML = `<div class="clock-time">${showTimeCountDown(secondShow)})`;
                        if (minuteShow == 0 && secondShow == 0) {
                            location.reload();
                        }
                    }, second);
            }
        }
    }

    function getStatusStage(i) {
        var timeSpanNow = new Date() / 1000;
        var checkNow = new Date(longs[i]).getTime() - timeSpanNow;
        var statusStage;
        if (checkNow > 0) {
            if (checkNow > 3600) {
                statusStage = "Chưa diễn ra";
            }
            else { statusStage = "Sắp diễn ra"; }
        }
        else {
            var timeEnd = $("#boxStage" + (i + 1).toString()).data('timeendstage');
            var timeSpanEnd = (new Date(timeEnd).getTime() / 1000);
            var checkNowEnd = timeSpanEnd - timeSpanNow;
            if (checkNowEnd > 5400) {
                statusStage = "Đang diễn ra";
            }
            else if (checkNowEnd <= 5400 && checkNowEnd > 1800) {
                statusStage = "Sắp kết thúc";
            }
            else { statusStage = "Đã kết thúc"; }
        }

        return statusStage;
    }

    function showTimeCountDown(time) {
        if (time.toString().length <= 1) {
            return "0" + time;
        }
        return time;
    }

    function init() {
        HandleUI();
        countDownTime();
    }

    return {
        init
    }

})(jQuery);


$(document).ready(function () {
    minigame.init();
});