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
        //detail scroll click list
        var menuItems = $("#list-place").find("a"),
            topMenuHeight = $('.header-controls').outerHeight(),
            scrollItems = menuItems.map(function () {
                var item = $($(this).attr("href"));
                if (item.length) { return item; }
            });

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





