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
            });
        });
        $('.next').on('click', function () {
            var activeTab = '';
            tabItem.each(function (index, value) {
                if ($(this).hasClass("active")) {
                    activeTab = $(this);
                    return;
                }
            });
            var next = activeTab.parent().next();
            if (next.length === 0) {
                console.log(123);
                next = $('.sub-item').first();
            };
            var self = next.find('.tab-data');
            var slug = self.data('slug');
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
            });
        });
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