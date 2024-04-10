var euro2024_home = (function ($) {
    var leagueId = 14638;
    var seasonId = 10810;
    function getStanding() {
        var param = JSON.stringify({ leagueId, seasonId });
        
        $.ajax({
            type: 'POST',
            url: '/euro2024/GetHomepageStandings',
            data: param,
            contentType: "application/json",
            success: function (res) {
                $('#home-standing').html(res);
            }
        });
    }
    function HandleSlider() {
        var swiper = new Swiper('.swiper-hot-news', {
            loop: true,
            speed: 900,
            spaceBetween: 10,
            grabCursor: true,
            autoplay: {
                delay: 4500,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.btn-next',
                prevEl: '.btn-prev',
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true
            }
        });

        var guesSwiper = new Swiper('.swiper-guess', {
            slidesPerView: 5.5,
            spaceBetween: 20,
            loop: false,
            speed: 300,
            grabCursor: true,
            navigation: {
                nextEl: '.btn-next',
                prevEl: '.btn-prev',
            },
        });
    }
    function init() {
        HandleSlider();
        getStanding();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    euro2024_home.init();
});