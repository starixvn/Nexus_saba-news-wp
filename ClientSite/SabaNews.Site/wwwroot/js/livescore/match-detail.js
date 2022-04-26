if (document.querySelector('.header') != null) {
    var checkScroll = function (e) {
        var header = document.querySelector('.header');
        var wapper = document.querySelector('.wapper');
        var headerHeight = header.offsetHeight + document.querySelector('.top-header').offsetHeight;
        var scrollHeight = window.pageYOffset
        if (scrollHeight > headerHeight) {
            wapper.classList.add('fixed-nav-league');
        } else {
            wapper.classList.remove('fixed-nav-league');
        }
    }
    window.addEventListener('scroll', function () { checkScroll() })
}