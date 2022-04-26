if (document.querySelector('.header') != null) {
    var checkScroll = function (e) {
        var wapper = $('.wapper');
        var headerHeight = $('.top-header').innerHeight();
        var scrollHeight = window.pageYOffset
        if (scrollHeight > headerHeight) {
            wapper.addClass('fixed-nav-league');
        } else {
            wapper.removeClass('fixed-nav-league');
        }
    }
    window.addEventListener('scroll', function () { checkScroll() })
}