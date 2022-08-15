var worldcup = (function ($) {

    function getTopScorers() {
        if ($('#topScorersWC')) {
            $.ajax({
                url: '/WorldCup2022/GetTopScorers',
                type: 'GET',
            }).done(function (data) {
                $('#topScorersWC').html(data);
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

    function init() {
        getTopScorers();
        scrollEffectHeader();
        handleActions();
    }
   

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    worldcup.init();
});

