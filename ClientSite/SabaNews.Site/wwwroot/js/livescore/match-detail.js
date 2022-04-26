if (document.querySelector('.header') != null) {
    var checkScroll = function (e) {
        var wapper = $('.wapper');
        var scrollHeight = window.pageYOffset;
        if (scrollHeight > 110) {
            wapper.addClass('fixed-nav-league');
        } else {
            wapper.removeClass('fixed-nav-league');
        }
    }
}

if ($(window).innerWidth() < 1024) {
    window.addEventListener('scroll', function () { checkScroll() })
}