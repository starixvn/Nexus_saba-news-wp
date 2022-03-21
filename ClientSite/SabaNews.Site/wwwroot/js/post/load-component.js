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

