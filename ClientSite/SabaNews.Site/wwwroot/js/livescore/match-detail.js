if (document.querySelector('.wapper') != null) {
    var checkScrollHome = function (e) {
        var header = document.querySelector('.wapper');
        var topOfHeader = header.offsetTop;
        var scrollHeight = window.pageYOffset
        if (scrollHeight > topOfHeader) {
            header.classList.add('fixed-nav-league');
        } else {
            header.classList.remove('fixed-nav-league');
        }
    }
    window.addEventListener('scroll', function () { checkScrollHome() })
}