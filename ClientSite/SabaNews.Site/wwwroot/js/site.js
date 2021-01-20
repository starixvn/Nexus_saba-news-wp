var setUp = {
    HandleIframe: function () {
        //Desktop & Mobile
        $('.match-schedule iframe').css('width', '100%');
        $('.play_video iframe').attr('allow', 'autoplay');
        $('.btn-play-video').on('click', function () {
            var slide = $(this).parent().parent();

            if (!slide.hasClass("active")) {
                $('.play_video').html('');
                $('.img-item').show();
                $('.swiper-slide').removeClass("active");
                var iframe = $(this).parent().parent().find("input[name=iframe_value]").val();
                var found = $.parseHTML(iframe);
                if (typeof found[2] === "undefined") { return false; }
                iframe = $(($.parseHTML(found[2].outerHTML)));
                iframe.attr('allow', 'autoplay');
                var src = iframe.attr('src');
                var autoplay = src.indexOf("autoplay");
                if (autoplay > -1) {
                    src = src.replace("autoplay=0", "autoplay=1");
                    src = src.replace("autoplay=1", "autoplay=1");
                }
                else {
                    var symbol = src.indexOf("?") > -1 ? "&" : "?";
                    src = src + symbol + "autoplay=1"
                }
                
                iframe.attr('src', src );

                $(this).parent().parent().find('.play_video').html(iframe);
                $(this).parent().hide();
                slide.addClass('active');
            }
        });
        //Desktop
        $('.image-slider').on('click', function () {
            var id = $(this).data('id');
            $('.swiper-slide').hide();
            var slide = $('#slide-' + id);
            slide.show();
            slide.find('.img-item').hide();
            $('.flex-item').removeClass("active");
            $(this).parent().parent().addClass("active");

            if (!slide.hasClass("active")) {
                $('.play_video').html('');
                $('.swiper-slide').removeClass("active");
                var iframe = slide.find("input[name=iframe_value]").val();
                var found = $.parseHTML(iframe);
                if (typeof found[2] === "undefined") { return false; }
                iframe = $(($.parseHTML(found[2].outerHTML)));
                iframe.attr('allow', 'autoplay');
                var src = iframe.attr('src');
                var autoplay = src.indexOf("autoplay");
                if (autoplay > -1) {
                    src = src.replace("autoplay=0", "autoplay=1");
                    src = src.replace("autoplay=1", "autoplay=1");
                }
                else {
                    var symbol = src.indexOf("?") > -1 ? "&" : "?";
                    src = src + symbol + "autoplay=1"
                }

                iframe.attr('src', src);
                slide.find('.play_video').html(iframe);
                slide.addClass('active');
            }
           
        });
    },
    //Desktop & Mobile
    HandleSearch: function () {
        $('.search-input button').on('click', function (e) {
            var keyword = $('input[name=keyword]').val();
            if ($.trim(keyword) === '') {
                e.preventDefault();
                $('input[name=keyword]').focus();
            }
        });
    },
    //Desktop & Mobile
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