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
                $('.slide-video .card-item').first().addClass("active");
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

                iframe.attr('src', src);

                $(this).parent().parent().find('.play_video').html(iframe);
                $(this).parent().hide();
                $(this).parent().addClass('active');

                const $btn = $(this);
                var postId = $btn.attr("post-id");
                if (postId) {
                    $.ajax({
                        url: "/api/post/view-video",
                        method: "POST",
                        data: {
                            postId
                        },
                        success: function (response) {
                            if (response.IsSuccessful) {
                                $btn.removeAttr("post-id");
                            }
                        }
                    });
                }
            }
        });
        //Desktop
        $('.image-slider').on('click', function () {
            var id = $(this).data('id');
            $('.block-video .swiper-slide').hide();
            var slide = $('#slide-' + id);
            slide.show();
            slide.find('.img-item').hide();
            $('.slide-video .card-item').removeClass("active");
            $(this).parent().addClass("active");

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
            }
        });
    },
    init: function () {
        this.HandleIframe();
    }
}

$(document).ready(function () {
    setUp.init();
})

//document.onreadystatechange = function () {
//    if (document.readyState !== "complete") {
//        console.log("loading")
//        $('.main').hide();
//        $('.skeleton-vertical').show();
//    } else {
//        $('.main').show();
//        $('.skeleton-vertical').hide();
        
//        console.log("complete")
//    }
//};

//$(window).load(function () {

//});

