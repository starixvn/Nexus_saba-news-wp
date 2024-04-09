var euro2024_home = (function ($) {
    function HandleSlide() {
        var swipers = new Swiper('.swiper-hot-news', {
            loop: true,
            speed: 900,
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
    }

    function init() {
        HandleSlide();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    euro2024_home.init();
});