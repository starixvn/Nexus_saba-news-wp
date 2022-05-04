if (document.querySelector('.header') != null) {
    var checkScroll = function (e) {
        const { scrollTop, scrollHeight, clientHeight } = document.documentElement;
        const canScrollHeight = scrollHeight - clientHeight;
        console.log(canScrollHeight);
        var main = document.querySelector('.wapper');
        var heightSection = $(".header-controls").innerHeight();
        var top_of_window = window.pageYOffset;
        if (top_of_window > heightSection && canScrollHeight > 400) {
            main.classList.add('detail-fixed');
        } else if (main.classList.contains('detail-fixed')) {
            main.classList.remove('detail-fixed');
        }
    }

    window.addEventListener('scroll', function () { checkScroll() })
}


