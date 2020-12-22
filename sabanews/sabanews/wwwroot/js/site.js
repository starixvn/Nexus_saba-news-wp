var setUp = {
    HandleIframe: function () {
        //Left Home Video
        $('.match-schedule iframe').css('width', '100%');
        $('.play_video iframe').attr('allow', 'autoplay');
        $('.btn-play-video').on('click', function () {
            var video = $(this).parent().parent().find('.play_video').text();
            if ($.trim(video) === "") {
                var iframe = $(this).parent().parent().find("input[name=iframe_value]").val();
                var found = $.parseHTML(iframe);
                if (typeof found[2] === "undefined") { return false; }
                iframe = $(($.parseHTML(found[2].outerHTML)));

                iframe.attr('allow', 'autoplay');
                var src = iframe.attr('src');
                var symbol = src.indexOf("?") > -1 ? "&" : "?";
                iframe.attr('src', src + symbol + "autoplay=1");

                $(this).parent().parent().find('.play_video').html(iframe);
                $(this).parent().hide();
            }
        });

        $('.image-slider').on('click', function () {
            var id = $(this).data('id');
            $('.swiper-slide').hide();
            var slide = $('#slide-' + id);
            slide.show();
            slide.find('.img-item').hide();
            $('.flex-item').removeClass("active");
            $(this).parent().parent().addClass("active");

            var video = slide.find('.play_video').html();
            if ($.trim(video) === "") {
                var iframe = slide.find("input[name=iframe_value]").val();
                var found = $.parseHTML(iframe);
                if (typeof found[2] === "undefined") { return false; }
                iframe = $(($.parseHTML(found[2].outerHTML)));

                iframe.attr('allow', 'autoplay');
                var src = iframe.attr('src');
                var symbol = src.indexOf("?") > -1 ? "&" : "?";
                iframe.attr('src', src + symbol + "autoplay=1");
                slide.find('.play_video').html(iframe);
            }
        });
    },
    HandleSearch: function () {
        $('.search-input button').on('click', function (e) {
            var keyword = $('input[name=keyword]').val();
            if ($.trim(keyword) === '') {
                e.preventDefault();
                $('input[name=keyword]').focus();
            }
        });
    },
    HandleUI: function () {
        if ($(window).innerWidth() < 768) {
            $('.block-guess .block__content .card-vertical').addClass("card-horizontal").removeClass("card-vertical");
        }
    },
    init: function () {
        this.HandleIframe();
        this.HandleSearch();
        this.HandleUI();
    }
}

$(document).ready(function () {
    setUp.init();
})