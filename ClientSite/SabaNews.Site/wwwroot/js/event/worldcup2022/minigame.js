var minigame = (function ($) {
    var isLogin = typeof checkLogin != 'undefined' ? checkLogin.toLowerCase() === 'true' : false;
    var teamId = 0;
    var teamName = "";
    const longs = [
        1669289400,
        1669366800,
        1669730400,
        1670076000
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

        $(".btn-accept ").click(function () {
            $('html, body').animate({
                scrollTop: $("#field-number-event").offset().top
            }, 500);
        });


        $('.click-number-event').click(function () {
            $('#joining').modal('show');            
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
        let countDown = new Date('November 16, 2022 08:00:00').getTime(),
                    x = setInterval(function () {
                        let now = new Date().getTime(),
                            distance = countDown - now;
                        let dayShow = Math.floor(distance / (day)),
                            hourShow = Math.floor((distance % (day)) / (hour)),
                            minuteShow = Math.floor((distance % (hour)) / (minute)),
                            secondShow = Math.floor((distance % (minute)) / second)

                        document.getElementById('hours' + (i + 1).toString()).innerHTML = `<div class="clock-time">${showTimeCountDown(hourShow)}:</div>`,
                document.getElementById('hours').innerHTML = `<div class="clock-time">${showTimeCountDown(hourShow)}</div> <div class="clock-text">giờ</div>`,
                    document.getElementById('minutes').innerHTML = `<div class="clock-time">${showTimeCountDown(minuteShow)}</div> <div class="clock-text">phút</div>`,
                    document.getElementById('seconds').innerHTML = `<div class="clock-time">${showTimeCountDown(secondShow)}</div> <div class="clock-text">giây</div>`;
            }
        }
    }

    function getStatusStage(i) {
        var timeSpanNow = new Date() / 1000;
        var checkNow = longs[i] - timeSpanNow;
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