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
        var fixtureHeight = $('.wc-bg-overflow').length > 0 ? $('.wc-bg-overflow').outerHeight() : 0;
        var tabId = location.hash;
        var offsTop = $(tabId).offset();
        if (typeof offsTop !== "undefined") {
            $('html, body').animate({
                scrollTop: offsTop.top - fixtureHeight
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

    function LoadBanner() {
        $("body").addClass("overflow-section");
        $.ajax({
            url: "/api/banner",
            type: "POST",
            success: function (res) {
                $("body").removeClass("overflow-section");
                $(".banner-left").html(GeneralBannerHtml(res.desktopLeftUrl, res.desktopLeftNavigationUrl));
                $(".banner-right").html(GeneralBannerHtml(res.desktopRightUrl, res.desktopRightNavigationUrl));
                $(".banner-desktop-top").html(GeneralBannerHtml(res.desktopTopUrl, res.desktopTopNavigationUrl));
                $(".banner-desktop-center").html(GeneralBannerHtml(res.desktopCenterUrl, res.desktopCenterNavigationUrl));
                $(".banner-mobile-top").html(GeneralBannerHtml(res.mobileTopUrl, res.mobileTopNavigationUrl));
                $(".banner-mobile-center").html(GeneralBannerHtml(res.mobileCenterUrl, res.mobileCenterNavigationUrl));
                $(".banner-mobile-bottom").html(GeneralBannerHtml(res.mobileBottomUrl, res.mobileBottomNavigationUrl));
                $(".banner-post-mobile-top").html(GeneralBannerHtml(res.postMobileTopUrl, res.postMobileTopNavigationUrl, true));
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus + ': ' + errorThrown);
            }
        });
    }

    function GeneralBannerHtml(src, link, isIntercroller = false) {
        var html = "";
        if (src !== "") {
            link = link === "" ? "javascript:;" : link;
            html += '<a href="' + link + '">';
            html += '<img loading="lazy" src="' + src + '" asp-append-version="true" class="img-res">';
            html += '</a>';
        }
        if(isIntercroller && src !== "") {
            var interscrollerHtml = '';
            interscrollerHtml += '<div id="ir__wrap" class="show-mobile">';
            interscrollerHtml += '<div class="ir__slot">';
            interscrollerHtml += '<div class="ir__box">';
            interscrollerHtml += '<div class="ir__media banner-post-mobile-top">'+ html +'</div>';
            interscrollerHtml += '</div>';
            interscrollerHtml += '</div>';
            interscrollerHtml += '</div>';
            html = interscrollerHtml;
        }
        return html;
    }

    function LoadBannerContent() {
        var html = $('.banner-content').html();
        var firstParagrapthContent = $('.content').find('p:not([class]');
        if(firstParagrapthContent.eq(0).html() !== '') {
            firstParagrapthContent.eq(0).after(html);
        } else {
            firstParagrapthContent.eq(1).after(html);
        }
    }

    function LoadSameCategoryPost() {
        if($('.same-category-post').length == 0) {
            return false;
        }
        $.ajax({
            type: 'POST',
            url: '/Post/GetSameCategoryPosts',
            data: {slug: window.cateSlug, postId: window.postId},
            success: function(res) {
                $('.same-category-post').html(res);
            }
        });
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
        LoadBanner();
        ScrollItem();
        LoadBannerContent();
        LoadSameCategoryPost();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    single.init();
});





