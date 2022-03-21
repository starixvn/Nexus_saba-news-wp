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
                }
            })
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
                $(".banner-mobile-top").html(GeneralBannerHtml(res.mobileTopUrl, res.mobileTopNavigationUrl));
                $(".banner-desktop-center").html(GeneralBannerHtml(res.mobileTopUrl, res.mobileTopNavigationUrl));
                console.log(res);
                console.log(res.desktopCenterNavigationUrl);
            }
        });
    }

    function GeneralBannerHtml(src, link) {
        var html = "";
        //<a href="@(string.IsNullOrEmpty(Model.DesktopRightNavigationUrl)? " javascript:; " : Model.DesktopRightNavigationUrl)" >
        //    <img loading="lazy" src="@Model.DesktopRightUrl" asp-append-version="true" class="img-res">
        //</a>
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
        LoadBanner();
    }

    return {
        init
    }

})(jQuery);

$(document).ready(function () {
    component.init();
});

