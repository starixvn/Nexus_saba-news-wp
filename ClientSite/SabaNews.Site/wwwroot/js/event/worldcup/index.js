var worldcup = (function ($) {
    var leagueId = 14638;
    var seasonId = 10810;
    function getStanding() {
        var param = JSON.stringify({ leagueId, seasonId });
        
        $.ajax({
            type: 'POST',
            url: '/worldcup2022/GetHomepageStandings',
            data: param,
            contentType: "application/json",
            success: function (res) {
                $('#home-standing').html(res);
            }
        })
    }

    function HandleSlide() {
        if ($(window).innerWidth() > 1025) {
            var swipers = new Swiper('.swipper-guess', {
                loop: false,
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 1
                    },
                    768: {
                        slidesPerView: 1
                    },
                    1024: {
                        slidesPerView: 1
                    },
                },
            });
            var Swipers = new Swiper('.swipper-video', {
                loop: false,
                slidesPerView: 3,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 1
                    },
                    768: {
                        slidesPerView: 1
                    },
                    1024: {
                        slidesPerView: 1
                    },
                },
            });
        }
    }

    function init() {
        getStanding();
        HandleSlide();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    worldcup.init();
});