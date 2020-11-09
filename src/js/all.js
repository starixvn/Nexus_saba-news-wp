
$(document).ready(function(){
    $('.button-mobile').click(function(){
        if($(window).innerWidth() <= 1024){
            $('.main-menu').toggleClass('active-main-menu');
            $(this).toggleClass('open');
            $('body').toggleClass('hidden-body');
        }
    });



    if($(window).innerWidth() <= 1024){
        $('.menu-child').css('display','none');
    }


    $('.click-sub').click(function(){
        console.log(1);
        if($(window).innerWidth() <= 1024){
            var submm = $(this).parent();
            if( !$(this).hasClass("show")){
                submm.find("ul:first").stop().slideToggle(300);
                $(this).addClass("show");
                submm.siblings().each(function(){
                    if($(this).find(".submenu:first").hasClass("show")){
                        $(this).find("ul:first").stop().slideToggle(300);
                        $(this).find(".submenu:first").removeClass('show');
                    }
                });
            }else{
                submm.find("ul:first").stop().slideToggle(300);
                $(this).removeClass("show");
            }
        }
    });
    var Swipes = new Swiper('.swiper-container', {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 0,
        //grabCursor: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });
    $(window).scroll(function() {
        //console.log('scroll' , window);
        if ($(this).scrollTop()) {
            $('.back-to-top').fadeIn();
        } else {
            $('.back-to-top').fadeOut();
        }
    });
    $(".back-to-top").click(function () {
        $("html, body").animate({scrollTop: 0}, 1000);
    });
});