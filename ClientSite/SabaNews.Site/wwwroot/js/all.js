$('.submenu').click(function(){
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
$(document).ready(function(){
    $('.button-mobile').click(function(){
        if($(window).innerWidth() <= 1024){
            $('.main-menu').toggleClass('active-main-menu');
            $(this).toggleClass('open');
            $('body').toggleClass('hidden-body');
        }
    });



    
    

    // if($(window).innerWidth() <= 1024){
    //     $('.menu-child').css('display','none');
    // }
 
    

    $('.click-sub').click(function(){
        if($(window).innerWidth() <= 1024){
            var submm = $(this).parent();
            if( !$(this).hasClass('show')){
                submm.find('ul:first').stop().slideToggle(300);
                $(this).addClass("show");
                submm.siblings().each(function(){
                    if($(this).find('.submenu:first').hasClass('show')){
                        $(this).find('ul:first').stop().slideToggle(300);
                        $(this).find('.submenu:first').removeClass('show');
                    }
                });
            }else{
                submm.find('ul:first').stop().slideToggle(300);
                $(this).removeClass('show');
            }
        }
    });
  

    $(window).scroll(function() {
        if ($(this).scrollTop() > 0) {
            $('.top-header').addClass('active-fixed');
            $('.menu-item .item').addClass('active-menu-item');
        } else {
            $('.top-header').removeClass('active-fixed');
            $('.menu-item .item').removeClass('active-menu-item');
        }
    });


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
});