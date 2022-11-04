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
                        $(this).html('thu gọn <i class="icon-chevron-up"></i>');
                    } else {
                        $(this).html('xem thêm <i class="icon-chevron-down"></i>');
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

