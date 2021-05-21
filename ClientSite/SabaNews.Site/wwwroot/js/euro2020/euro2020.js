var euro2020 = (function ($) {
    var tabItem = $('.tab-data');
    function handleTab() {
        tabItem.on('click', function () {
            var slug = $(this).data('slug');
            $.ajax({
                type: 'GET',
                url: '/euro-2020/getTabData',
                data: { slug: slug },
                success: function (res) {
                    if (res.isSuccessful) {
                        console.log(res.data.PostContent);
                        $('.post_content').html(res.data.postContent);
                    }
                }
            })
        })
    }
    function init() {
        handleTab();
    }

    return {
        init
    }
})(jQuery);


$(document).ready(function () {
    euro2020.init();
});