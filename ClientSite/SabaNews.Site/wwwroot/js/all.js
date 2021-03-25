//mobile
$('.submenu').click(function () {
    $(this).parents('.menu-item').siblings().children('.submenu').removeClass('go-page');
    if(window.innerWidth <= 1025 ){
        if($(this).hasClass('go-page')){
            console.log('Move page')
        }
        else{
            console.log('stop')
            $(this).addClass('go-page');
            return false
        }
    }
})
$(document).ready(function () {
    //mobile
    $('.button-mobile').click(function(){
        if($(window).innerWidth() <= 1024){
            $('.main-menu').toggleClass('active-main-menu');
            $(this).toggleClass('open');
            $('body').toggleClass('hidden-body');
        }
    });

    //desktop
    $('.btn-search').click(function () {
        if ($(window).innerWidth() >= 1024) {
            $('.search-input .search-box').toggleClass('active-input');
        }
    });

    //desktop
    $(window).scroll(function() {
        if ($(this).scrollTop() > 0) {
            $('.top-header').addClass('active-fixed');
            $('.menu-item .item').addClass('active-menu-item');
        } else {
            $('.top-header').removeClass('active-fixed');
            $('.menu-item .item').removeClass('active-menu-item');
        }
    });

    //back to top mb and des
    $(window).scroll(function() {
        if ($(this).scrollTop()) {
            $('.back-to-top').fadeIn();
        } else {
            $('.back-to-top').fadeOut();
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1000);
    });
    
    $('#detail-page').bind("cut copy paste", function (e) {
        e.preventDefault();
    });

    $(window).click(function (e) {
        var elmClass = e.target.className;
        if (elmClass !== 'icon-search' && elmClass !== 'keyword-input') {
            var searchbox = $('.search-box');
            if (searchbox.hasClass('active-input')) {
                searchbox.removeClass('active-input');
                $('input[name=keyword]').val('');
            }
        } else {
            $('input[name=keyword]').focus();
        }
    });
});