var component = (function ($) {
    function LoadRelatedPosts() {
        var block = $("body").find(".related-posts");
        if (block.length > 0) {
            var postID = block.data("postid");
            var cateSlug = block.data("cateslug");
            $.ajax({
                url: "/api/post/related-posts",
                type: "POST",
                data: { postID, cateSlug },
                success: function (res) {
                    block.html(res);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                }
            });
        }
    }

    function LoadRelatedWorldCupPosts() {
        var block = $("body").find(".relatedwc-posts");
        if (block.length > 0) {
            var postID = block.data("postid");
            var cateSlug = block.data("cateslug");
            $.ajax({
                url: "/world-cup-2022/related-posts",
                type: "POST",
                data: { postID, cateSlug },
                success: function (res) {
                    block.html(res);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                }
            });
        }
    }

    function LoadSidebarPosts() {
        var block = $("body").find(".sidebar-posts");
        if (block.length > 0) {
            var postID = block.data("postid");
            var cateSlug = block.data("cateslug");
            $.ajax({
                url: "/api/post/sidebar-posts",
                type: "POST",
                data: { postID, cateSlug },
                success: function (res) {
                    block.html(res);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                }
            })
        }
    }

    function LoadSidebarWorldCupPosts() {
        var block = $("body").find(".sidebarwc-posts");
        if (block.length > 0) {
            var postID = block.data("postid");
            var cateSlug = block.data("cateslug");
            $.ajax({
                url: "/world-cup-2022/sidebar-posts",
                type: "POST",
                data: { postID, cateSlug },
                success: function (res) {
                    block.html(res);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                }
            })
        }
    }

    function LoadBanner() {
        if ($('.banner-left').length > 0 && $('.banner-right').length > 0) {
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
                    $(".skeleton-banner").hide();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                }
            });
        }
        
    }

    function GeneralBannerHtml(src, link) {
        var html = "";
        if (src !== "") {
            link = link === "" ? "javascript:;" : link;
            html += '<a href="' + link + '">';
            html += '<img loading="lazy test" src="' + src + '" asp-append-version="true" class="img-res">';
            html += '</a>';
        }
       
        return html;
    }
    function init() {
        LoadRelatedPosts();
        LoadSidebarPosts();
        LoadSidebarWorldCupPosts();
        LoadRelatedWorldCupPosts();
        LoadBanner();
    }

    return {
        init
    }

})(jQuery);

$(document).ready(function () {
    component.init();
});

