var setUp = {

    HandleIframe: function () {
        //Left Home Video
        $('.match-schedule iframe').css('width', '100%');
        $('.play_video iframe').hide();
        $('.play_video iframe').attr('allow', 'autoplay');
        $('.btn-play-video').on('click', function () {
            var iframe = $(this).parent().parent().find('iframe');
            var src = iframe.attr('src');
            var symbol = src.indexOf("?") > -1 ? "&" : "?";
            iframe.attr('src', src + symbol + "autoplay=1");
            $(this).parent().hide();
            iframe.show();
        });

        //Left &  Right Home Video

        if ($(window).innerWidth() < 768) {
            $('.swiper-slide').show();
            $('.swiper-slide .play_video iframe').first().hide();
        } else {
            $('.swiper-slide').first().show();
            $('.swiper-slide .play_video iframe').first().show();
        }

        $('.image-slider').on('click', function () {
            var id = $(this).data('id');
            $('.swiper-slide').hide();
            $('#slide-' + id).show();
            $('.flex-item').removeClass("active");
            $(this).parent().parent().addClass("active");

            var iframe = $('#slide-' + id).find('iframe');
            var autoplay = iframe.attr("autoplay");
            if (autoplay == null || !autoplay) {
                var src = iframe.attr('src');
                var symbol = src.indexOf("?") > -1 ? "&" : "?";
                iframe.attr('src', src + symbol + "autoplay=1");
                iframe.attr('autoplay', true);
            }

            $('#slide-' + id).find('.img-item').hide();
            iframe.show();
        });

    },
    HandleSearch: function () {
        $('.search-input button').on('click', function (e) {
            var keyword = $('input[name=keyword]').val();
            if ($.trim(keyword) == '') {
                e.preventDefault();
                $('input[name=keyword]').focus();
            }
        });
    },
    init: function () {
        this.HandleIframe();
        this.HandleSearch();
    }
}

$(document).ready(function () {
    setUp.init();
})