var home = {
    HandleIframe: function () {
        //Desktop & Mobile
        $('.match-schedule iframe').css('width', '100%');
        $('.play_video iframe').attr('allow', 'autoplay');
        $('.btn-play-video').on('click', function () {
            var slide = $(this).parent().parent().parent();
            if (!slide.hasClass("active")) {
                $('.play_video').html('');
                $('.img-item').show();
                $('.box-play-video').removeClass("active");
                $('.slide-video .card-item').first().addClass("box-video");
                var iframe = $(this).parent().parent().parent().find("input[name=iframe_value]").val();
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

                $(this).parent().parent().parent().find('.play_video')[0].style.display = "block";
                $(this).parent().parent().parent().find('.play_video').html(iframe);
                $(this).parent().parent().hide();
                $(this).parent().parent().addClass('active');

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
            $('.block-video .box-play-video').hide();
            var slide = $('#slide-' + id);
            slide.show();
            slide.find('.img-item').hide();
            $('.slide-video .card-item').removeClass("box-video");
            $(this).parent().addClass("box-video");

            if (!slide.hasClass("active")) {
                $('.play_video').html('');
                $('.box-play-video').removeClass("active");
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
                slide.find('.play_video')[0].style.display = "block";
                slide.find('.play_video').html(iframe);

                if (id) {
                    $.ajax({
                        url: "/api/post/view-video",
                        method: "POST",
                        data: {
                            postId: id
                        },
                        success: function (response) {
                            if (response.IsSuccessful) {
                                $(this).removeAttr("post-id");
                            }
                        }
                    });
                }
            }
        });
    },
    HandleLivescoreNav: function() {
        $(window).scroll(function () {
            if($(window).innerWidth() <= 768) {
                $('.home-nav-content').addClass('standing-fixture-mobile').removeClass('home-nav-content');
            } else {
                $('.standing-fixture-mobile').addClass('home-nav-content').removeClass('standing-fixture-mobile')
            }
        });
        $('.home-nav a').on('click', function() {
            $('.home-nav a').removeClass('active');
            $(this).addClass('active');
            var id = $(this).data('id');
            $('.home-nav-content .tab').removeClass('active');
            $('.home-nav-content ' + id).addClass('active');
        })
    },
    init: function () {
        this.HandleIframe();
        this.HandleLivescoreNav();
    }
}

$(document).ready(function () {
    home.init();
})



