var single = (function ($) {
    function UpdatePostView() {
        $(window).on("load", function () {
            var postId = $("#postID").val();
            var isVideo = ($("#isVideo").val().toLowerCase() === 'true');
            $.ajax({
                url: "/api/post/update-post-view",
                type: "POST",
                data: { postId, isVideo },
                success: function (res) {
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                }
            });
        });
    }

    function ScrollItem() {
        if (location.hash) {
            setTimeout(function () {

                window.scrollTo(0, 0);
            }, 1);
        }
        //detail scroll click list
        var menuItems = $("#list-place").find("a"),
            topMenuHeight = $('.header-controls').outerHeight(),
            socialHeight = $('.main-social').outerHeight();
        var tabId = location.hash;
        var offsTop = $(tabId).offset();
        if (typeof offsTop !== "undefined") {
            $('html, body').animate({
                scrollTop: offsTop.top
            }, 800);
        }
        


        if ($(window).innerWidth() > 1024) {
            menuItems.click(function (e) {
                var href = $(this).attr("href");
                var offsetTop = href === "#" ? 0 : $(href).offset().top - 110;
                if ($(window).scrollTop() > 120) {
                    offsetTop = href === "#" ? 0 : $(href).offset().top - topMenuHeight;
                }
                $('html, body').stop().animate({
                    scrollTop: offsetTop
                }, 800);
                e.preventDefault();
            });
        } else {
            menuItems.click(function (e) {
                var href = $(this).attr("href");
                var offsetTop = href === "#" ? 0 : $(href).offset().top - 50;
                if ($(window).scrollTop() > 0) {
                    offsetTop = href === "#" ? 0 : $(href).offset().top - socialHeight;
                }
                $('html, body').stop().animate({
                    scrollTop: offsetTop
                }, 800);
                e.preventDefault();
            });
        }

    }

    function getScrollTop(isMobile, href, scrollTop) {
        var offsetTop = 0;
        if (isMobile) {
            var offsetTop = $(href).offset().top - 50;
            if ($(window).scrollTop() > 0) {
                offsetTop = $(href).offset().top - socialHeight;
            } else {
                $(href).offset().top - 110
            }
        }
    }
   

    function init() {
        UpdatePostView();
        ScrollItem();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    single.init();
});





