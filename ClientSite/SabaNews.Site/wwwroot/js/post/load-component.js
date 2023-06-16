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
            var limitItem = block.data("limititem");
            $.ajax({
                url: "/api/post/sidebar-posts",
                type: "POST",
                data: { postID, cateSlug, limitItem },
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
        if($('.singe-page').length > 0) return  false;
        if (($('.banner-left').length > 0 && $('.banner-right').length > 0)
            || $('.banner-desktop-top').length > 0) {
            $("body").addClass("overflow-section");
            $.ajax({
                url: "/api/banner",
                type: "POST",
                success: function (res) {
                    $("body").removeClass("overflow-section");
                    $(".banner-left").html(GeneralBannerHtml(res.desktopLeftUrl, res.desktopLeftNavigationUrl, "Banner left"));
                    $(".banner-right").html(GeneralBannerHtml(res.desktopRightUrl, res.desktopRightNavigationUrl, "Banner right"));
                    $(".banner-desktop-top").html(GeneralBannerHtml(res.desktopTopUrl, res.desktopTopNavigationUrl, "Banner top"));
                    $(".banner-desktop-center").html(GeneralBannerHtml(res.desktopCenterUrl, res.desktopCenterNavigationUrl, "Banner center"));
                    $(".banner-mobile-top").html(GeneralBannerHtml(res.mobileTopUrl, res.mobileTopNavigationUrl, "Banner top"));
                    $(".banner-mobile-center").html(GeneralBannerHtml(res.mobileCenterUrl, res.mobileCenterNavigationUrl, "Banner center"));
                    $(".banner-mobile-bottom").html(GeneralBannerHtml(res.mobileBottomUrl, res.mobileBottomNavigationUrl, "Banner bottom"));
                    $(".banner-post-mobile-top").html(GeneralBannerHtml(res.postMobileTopUrl, res.postMobileTopNavigationUrl, "Banner top"));
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ': ' + errorThrown);
                }
            });
        }
    }

    function GeneralBannerHtml(src, link, alt) {
        var html = "";
        if (src !== "") {
            link = link === "" ? "javascript:;" : link;
            html += '<a href="' + link + '">';
            html += '<img loading="lazy" alt="'+ alt +'" src="' + src + '" asp-append-version="true" class="img-res">';
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

