var euro2020 = (function ($) {
    var tabItem = $('.tab-data');
    function handleTab() {
        tabItem.on('click', function () {
            var self = $(this);
            getTabData(self);
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
            getTabData(self);
        });
        $('.previous').on('click', function () {
            var activeTab = '';
            tabItem.each(function (index, value) {
                if ($(this).hasClass("active")) {
                    activeTab = $(this);
                    return;
                }
            });
            var next = activeTab.parent().prev();
            if (next.length === 0) {
                next = $('.sub-item').last();
            };
            var self = next.find('.tab-data');
            getTabData(self);
        });
    }
    function getTabData(self) {
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
                    $('#table-scroll-team .main-table').clone(true).appendTo('#table-scroll-team').addClass('clone');
                    if ($(window).innerWidth() <= 768 && $('.ranking').length > 0) {
                        $('.sub-item').removeClass('hide-tab');
                        $('.sub-item').hide();
                        self.parent().show();
                    }
                }
            }
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
    $('#table-scroll-team .main-table').clone(true).appendTo('#table-scroll-team').addClass('clone');
    $('#table-scroll-main .main-table').clone(true).appendTo('#table-scroll-main').addClass('clone');
});