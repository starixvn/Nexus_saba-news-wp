var euro2024_single = (function ($) {
    var numRow = 1;
    var isMobile = false;
    var total = 0;
    function showStandings() {
        if (isMobile === "False") {
            for (var i = 1; i <= total; i++) {
                if (i != numRow) {
                    $('#standing-' + i).hide();
                }
                else {
                    $('#standing-' + i).show();
                }
            }
        }
    }
    function getStandings() {
        $.ajax({
            url: '/Euro2024/GetStandings',
            type: 'GET'
        }).done(function (data) {
            $('.euro-top-standing').html(data);

            total = $(".btn-next-standing").data('total');
            isMobile = $(".euro-top-standing").data('ismobile');
            showStandings();
           
            $('.btn-back-standing').on('click', function () {
                numRow--;
                if (numRow >= 1) {
                    showStandings();
                }
                else {
                    numRow++;
                }
            });

            $('.btn-next-standing').on('click', function () {
                numRow++;
                var total = $(this).data("total");
                if (numRow <= total) {
                    showStandings();
                }
                else {
                    numRow--;
                }
            });
        })
    }

    function LoadRelatedPosts() {
        var block = $("body").find(".related-posts");
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
                console.log(res)
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus + ': ' + errorThrown);
            }
        });
    }
    function init() {
        getStandings();
        LoadRelatedPosts();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    euro2024_single.init();
});