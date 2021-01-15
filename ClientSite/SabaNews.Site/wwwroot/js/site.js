var setUp = {
    HandleIframe: function () {
        //Desktop & Mobile
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
                var autoplay = src.indexOf("autoplay");
                if (autoplay > -1) {
                    src = src.replace("autoplay=0", "");
                    src = src.replace("autoplay=1", "");
                }
                var symbol = src.indexOf("?") > -1 ? "&" : "?";
                iframe.attr('src', src + symbol + "autoplay=1");

                $(this).parent().parent().find('.play_video').html(iframe);
                $(this).parent().hide();
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

            var video = slide.find('.play_video').html();
            if ($.trim(video) === "") {
                var iframe = slide.find("input[name=iframe_value]").val();
                var found = $.parseHTML(iframe);
                if (typeof found[2] === "undefined") { return false; }
                iframe = $(($.parseHTML(found[2].outerHTML)));
                iframe.attr('allow', 'autoplay');
                var src = iframe.attr('src');
                var autoplay = src.indexOf("autoplay");
                if (autoplay > -1) {
                    src = src.replace("autoplay=0", "");
                    src = src.replace("autoplay=1", "");
                }

                var symbol = src.indexOf("?") > -1 ? "&" : "?";
                iframe.attr('src', src + symbol + "autoplay=1");
                slide.find('.play_video').html(iframe);
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
    //Mobile
    HandleSharingSocial: function () {
        function uriSchemeWithHyperlinkFallback(uri, href) {
            if (!window.open(uri)) {
                window.location = href;
            }
        }

        $('a.intent.facebook').on('click', function (event) {
            var uri = $(this).data('scheme');
            var href = $(this).attr('href');
            var now = new Date().valueOf();
            setTimeout(function () {
                if (new Date().valueOf() - now > 100) return;
                window.location = href;
            }, 25);
            window.location = uri;
            
            event.preventDefault();
        });

        $('a.intent.tele').on('click', function (event) {
            var uri = $(this).data('scheme');
            var href = $(this).attr('href');
            var req = new XMLHttpRequest(); 
            req.open('GET', uri, true);
            req.send();
            if (req.status !== "200") {
                window.location = href;
            } else {
                window.location = uri;
            }
            event.preventDefault();
        });
    },
    init: function () {
        this.HandleIframe();
        this.HandleSearch();
        this.HandleUI();
        this.HandleSharingSocial();
    }
}

$(document).ready(function () {
    setUp.init();
})