////if (document.querySelector('.header') != null) {
////    var checkScroll = function (e) {
////        const { scrollHeight, clientHeight } = document.documentElement;
////        const canScrollHeight = scrollHeight - clientHeight;
////        var main = document.querySelector('.wapper');
////        var heightSection = $(".header-controls").innerHeight();
////        var top_of_window = window.pageYOffset;
////        if (top_of_window > heightSection && canScrollHeight > 400) {
////            main.classList.add('detail-fixed');
////        } else if (main.classList.contains('detail-fixed')) {
////            main.classList.remove('detail-fixed');
////        }
////    }

////    window.addEventListener('scroll', function () { checkScroll() })
////}


var checkScroll = function (e) {
    var heightSection = $(".sticky-header").innerHeight();
    var top_of_window = window.scrollY;
    if (top_of_window > heightSection) {
        $('.detail-fixed').addClass('active-scroll');
        $('.sticky-header').addClass('active-scroll');
    } else {
        $('.detail-fixed').removeClass('active-scroll');
        $('.sticky-header').removeClass('active-scroll');
    }
}

window.addEventListener('scroll', function () { checkScroll() })

