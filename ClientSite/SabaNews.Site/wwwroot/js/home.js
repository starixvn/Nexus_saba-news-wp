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
    HandleLivescore: function () {
        let userAgent = navigator.userAgent;
        let browserName;
        if (userAgent.match(/chrome|chromium|crios/i)) {
            browserName = "chrome";
            $('body').addClass('fix-chrome')
        } else if (userAgent.match(/firefox|fxios/i)) {
            browserName = "firefox";
            $('html').find('.livescore-soccer-main').addClass('ff-scroll');
            $('html').find('.livescore-standing').addClass('parent-ff-scroll');
            $('html').find('.block-video .block-child-video').addClass('ff-scroll');
            $('html').find('.hover-image').addClass('hover-ff');
            $('html').find('.hover-none-16-9').addClass('hover-ff');
        } else if (userAgent.match(/safari/i)) {
            browserName = "safari";
            console.log(browserName)
        } else if (userAgent.match(/opr\//i)) {
            browserName = "opera";
            console.log(browserName)
        } else if (userAgent.match(/edg/i)) {
            browserName = "edge";
            console.log(browserName)
        } else {
            browserName = "No browser detection";
        }
    },
    init: function () {
        this.HandleIframe();
        this.HandleLivescore();
    }
}

$(document).ready(function () {
    home.init();
})



