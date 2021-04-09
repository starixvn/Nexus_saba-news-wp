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
    $('.language-select').click(function () {
        $(this).siblings('.languages-list').toggleClass('active')
    });

    $('.action').click(function (e) {
        e.preventDefault();
        $(this).parent('.box-control').next().toggleClass('toggle-active-form');
    })

    $('.close-edit').click(function (e) {
        e.preventDefault();
        $(this).parent().removeClass('toggle-active-form');
    })


    //mobile
    $('.button-mobile').click(function(){
        if($(window).innerWidth() <= 1024){
            $('.main-menu').toggleClass('active-main-menu');
            $('.nav-menu').toggleClass('active-nav');
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
    $(window).scroll(function () {
        if ($(window).innerWidth() > 1024) {
            if ($(this).scrollTop() > 100) {
            $('.top-header').addClass('active-fixed');
        } else {
            $('.top-header').removeClass('active-fixed');
        }
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
    
    $('#detail-page').bind("cut copy paste contextmenu", function (e) {
        e.preventDefault();
    });

    if ($('body').find('#detail-page').length !== 0) {
        $(document).keydown(function (e) {
            if ((e.ctrlKey || e.metaKey) && e.keyCode === 65) {
                e.preventDefault();
            }
            if (e.keyCode === 27) {
                $('.modal').modal('hide');

            }
        });
    }

    $('img.profile_image').on('error', function () {
        if (typeof $(this).data('name') !== "undefined") {
            $(this).parent().html($(this).data('name'));
            $(this).remove();
        }
    });

    $(document).on('click', '.toggle-pass .icon-form', function () {
        if ($(this).hasClass('icon-show')) {
            $(this).parent().parent().find('input').attr('type', 'text');
            $(this).hide();
            $(this).parent().find('.icon-hide').show();
        } else {
            $(this).parent().parent().find('input').attr('type', 'password');
            $(this).hide();
            $(this).parent().find('.icon-show').show();
        }
    });



    $('.auth-input').click(function () {
        $('.box-auth').toggleClass('active-box-auth')
    });

    $(window).click(function (e) {
        var elmClass = e.target.className;
        if (elmClass !== 'country' && elmClass !== 'language-select' && elmClass !== 'flag flag-vn') {
            if ($('.languages-list').hasClass('active')) {
                $('.languages-list').removeClass('active');
            }
           
            
        }
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


    $(document).keyup(function (e) {
        if (e.keyCode === 27) {
            $('.modal').modal('hide');
        }
    });

    $(document).on('shown.bs.modal', '.modal', function (e) {
        $('body').addClass('overflow-modal');
    })

    $(document).on('hidden.bs.modal', '.modal', function (e) {
        $('body').removeClass('overflow-modal');
    })
});