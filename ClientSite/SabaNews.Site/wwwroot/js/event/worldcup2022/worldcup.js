var worldcup = (function ($) {

    function getTopScorers() {
        if ($('#topScorersWC')) {
            var isHide = true;
            var more = $('#topScorersWC').data('more');
            $.ajax({
                url: '/WorldCup2022/GetTopScorers',
                type: 'POST',
                data: { more },
            }).done(function (data) {
                $('#topScorersWC').html(data);
                $('[data-toggle="tooltip"]').tooltip();
                $('.top-scorer-hide-button').on('click', function () {
                    $('.top-scorer-table .wc-table-row.more').toggleClass('hide');
                    $('.top-scorer-table .wc-table-row.ori').toggleClass('show');
                    if (isHide) {
                        $(this).html('Thu gọn <i class="icon-chevron-up"></i>');
                    } else {
                        $(this).html('Xem thêm <i class="icon-chevron-down"></i>');
                    }
                    isHide = !isHide;
                });
            })
        }
    }


    function scrollEffectHeader() {
        $(window).scroll(function () {
            var topScroll = $(window).scrollTop();
            var header = $('.top-header');
            if (topScroll > 0) {
                header.addClass('scroll-height');
            } else {
                header.removeClass('scroll-height');
            }
        });
    }

    function handleActions() {

        $(document).on('shown.bs.modal', '.modal', function (e) {
            $('html').addClass('overflow-page');
        })

        $(document).on('hidden.bs.modal', '.modal', function (e) {
            $('html').removeClass('overflow-page');
        })
    }


    function countDownTime() {
        const second = 1000,
            minute = second * 60,
            hour = minute * 60,
            day = hour * 24;
        let countDown = new Date('November 17, 2022 00:00:00').getTime(),
            x = setInterval(function () {
                let now = new Date().getTime(),
                    distance = countDown - now;
                document.getElementById('days').innerHTML = `<div class="clock-time">${Math.floor(distance / (day))}</div> <div class="clock-text">ngày</div>`;
                document.getElementById('hours').innerHTML = `<div class="clock-time">${Math.floor((distance % (day)) / (hour))}</div> <div class="clock-text">giờ</div>`,
                    document.getElementById('minutes').innerHTML = `<div class="clock-time">${Math.floor((distance % (hour)) / (minute))}</div> <div class="clock-text">phút</div>`,
                    document.getElementById('seconds').innerHTML = `<div class="clock-time">${Math.floor((distance % (minute)) / second)}</div> <div class="clock-text">giây</div>`;
            }, second);
    }

    function init() {
        getTopScorers();
        scrollEffectHeader();
        handleActions();
        countDownTime();
    }
   

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    worldcup.init();
});

