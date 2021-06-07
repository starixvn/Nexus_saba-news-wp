var euro2020 = (function ($) {
    var tabItem = $('.tab-data');
    function handleTab() {
        tabItem.on('click', function () {
            var slug = $(this).data('slug');
            var self = $(this);
            $.ajax({
                type: 'GET',
                url: '/euro-2020/getTabData',
                data: { slug: slug },
                success: function (res) {
                    if (res.isSuccessful) {
                        $('.post_content').html(res.data.postContent);
                        tabItem.removeClass('active');
                        self.addClass('active');
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
    $('.main-table').clone(true).appendTo('#table-scroll-team').addClass('clone');
    $('.main-table').clone(true).appendTo('#table-scroll-main').addClass('clone');
});