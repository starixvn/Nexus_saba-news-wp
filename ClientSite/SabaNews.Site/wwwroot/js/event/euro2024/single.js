var euro2024_single = (function ($) {
    function getStandings() {
        $.ajax({
            url: '/Euro2024/GetStandings',
            type: 'GET'
        }).done(function (data) {
            $('.euro-top-standing').html(data);
        })
    }

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

    function HandleMenuTab () {
        $(document).on('click', '.nav-tab-js .nav-tab-item', function(){
            $('.nav-tab-js li').removeClass('active');
            $(this).parent().addClass('active');
            let currentTab = $(this).attr('data-id');
            $('.tab-content .wc-table-body').hide();
            $(currentTab).show();
            return false;
        });
    }

    function init() {
        getStandings();
        LoadRelatedPosts();
        HandleMenuTab();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    euro2024_single.init();
});