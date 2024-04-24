var euro2024_single = (function ($) {

    function LoadRelatedPosts() {
        var block = $("body").find(".euro-related-posts");
        if(block.length === 0) {
            return false;
        }
        var postID = block.data("postid");
        var cateSlug = block.data("cateslug");
        $.ajax({
            url: "/euro2024/related-posts",
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

    function init() {
        LoadRelatedPosts();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    euro2024_single.init();
});