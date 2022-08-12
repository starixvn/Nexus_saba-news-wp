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

$(document).mousedown('a', function () {
    $(window).unbind('beforeunload');
});
$("#search").submit(function () {
    $(window).unbind('beforeunload');
});
$(window).bind('beforeunload', function (e) {
    $('.post').hide();
    $('.skeleton').show();
});
$(window).bind('unload', function (e) {
    $('.skeleton').hide();
    $('.post').show();
    
});

$(document).ready(function () {
    $('.skeleton').hide();
    $('.skeleton').hide().promise().done(function () { console.log(3); $('.post').show(); });
    $('.skeleton').hide();
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

    $('.button-social-menu').click(function () {
        $('body').addClass('hidden-body');
        $('.mb-singe-page').addClass('active-mb-social')
    })
    $('.close-socical').click(function () {
        $('body').removeClass('hidden-body');
        $('.mb-singe-page').removeClass('active-mb-social')
    })


    $(window).on("load resize", function (e) {
        var widthContainer = $('.livescore-soccer-main').innerWidth();
        $('body').removeClass('hidden-body');
        $('.button-mobile').removeClass('open');
        $('.main-menu').removeClass('active-main-menu');
        $('.nav-menu').removeClass('active-nav');
        $('.header-controls').addClass('width-100-saba');
        $('.detail-fixed').css("width", widthContainer);
    });
    //mobile
    $('.button-mobile').click(function () {
        var windowWidth = window.innerWidth;
        if ($(window).innerWidth() <= 1024) {
            $('.main-menu').toggleClass('active-main-menu');
            $('.nav-menu').toggleClass('active-nav');
            $(this).toggleClass('open');
            $('body').toggleClass('hidden-body');
            $('.header-controls').removeClass('width-100-saba');
            $('.header-controls').css('width', windowWidth);
            $('.has-sub ul').hide();
            $(".submenu-button").removeClass('submenu-opened');
        }
    });
    

    $('.button-detail-page').click(function () {
        if ($(window).innerWidth() <= 1024) {
            $(this).toggleClass('open');
            $('body').toggleClass('hidden-detail');
        }
    });

   

    //desktop
    if ($(window).innerWidth() < 1024) {
        $('.search-desktop').remove();
    }
    if (document.querySelector('.wapper') != null) {
        var checkScrollHome = function () {
            var main = document.querySelector('.wapper');
            //var top_of_obj = main.offsetTop;
            var heightSection = $(".header-controls").innerHeight();
            var top_of_window = window.scrollY;
            const { scrollHeight, clientHeight } = document.documentElement;
            const canScrollHeight = scrollHeight - clientHeight;
            if (top_of_window > heightSection && canScrollHeight > 150) {
                main.classList.add('active-fixed');
            } else {
                main.classList.remove('active-fixed');
            }
        }
        window.addEventListener('scroll', function () { checkScrollHome() })
    }
    


    function scrollBackTop() {
        if ($(window).scrollTop()) {
            $('.back-to-top').fadeIn();
        } else {
            $('.back-to-top').fadeOut();
        }
    }

    function scrollDetailPageLiveScore() {
        var heightContainer = $('.sticky-header').outerHeight();
        if ($(window).scrollTop() > heightContainer) {
            $('.detail-fixed').addClass('active-scroll');
        } else {
            $('.detail-fixed').removeClass('active-scroll');
        }
    }

    function scrollTopY() {
        var top_of_window = $(window).scrollTop();
        var widthContainer = $('.livescore-soccer-main').innerWidth();
        if (top_of_window > 0) {
            $('.detail-fixed').css("width", widthContainer);
        } else {
            $('.detail-fixed').css("width", "auto");
        }
    }




    $(window).scroll(function () {
        scrollBackTop();
        scrollDetailPageLiveScore();
        scrollTopY();
    });








    $('.back-to-top').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 1000);
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



    $('.auth-input').click(function (e) {
        $('.box-auth').toggleClass('active-box-auth');

    });

    $(window).click(function (e) {
        var elmClass = e.target.className;

        var elmAuth = $(e.target).parent().parent();
        if (!elmAuth.is('.auth-provider') && !elmAuth.is('.auth-input')) {
            if ($('.box-auth').hasClass('active-box-auth')) {
                $('.box-auth').removeClass('active-box-auth');
            }
        }

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

    $('.scroll-mobile-menu').on('click', function () {
        var leftPos = $('.main-child-menu').scrollLeft();
        $(".main-child-menu").animate({
            scrollLeft: leftPos + 200
        }, 800);
    });
  
    $('.search-input button').on('click', function (e) {
        e.preventDefault();
        var input = $(this).parent().find('.keyword-input');
        var form = $(this).parent();
        var keyword = input.val();
        if ($.trim(keyword) === '') {
            input.focus();
        } else {
            form.trigger("reset");
            form.attr('action', '/Search/' + keyword);
            form.submit();
        }
    });

    if ($(window).innerWidth() < 768) {
        $('.block-guess .block__content .card-vertical').addClass("card-horizontal").removeClass("card-vertical");
        $('.block-guess .block__content .skeleton-vertical').addClass("skeleton-horizontal").removeClass("skeleton-vertical");
    }

    
    
    $(window).on('scroll', function () {
        var banner = $('.banner').offset();
        const header = $('.header-controls').innerHeight();
        const topHeader = $('.top-header').innerHeight();
        const top = header + topHeader - 15;
        if ($(this).scrollTop() > top ) {
            $(".banner-change-domain").addClass('active-ads');
        } else {
            $(".banner-change-domain").removeClass('active-ads');
        }
    });

    $('#EventModal').modal('hide');

    getFavouriteMatchCount();
});

function getFavouriteMatchCount() {
    let matchIds = localStorage.getItem('favourite-matches');
    if (!matchIds) {
        matchIds = [];
    } else {
        matchIds = JSON.parse(matchIds.toString());
        matchIds = matchIds.filter(match => match !== null);
    }

    const count = matchIds.length;
    const favMatch = $('.mobile-fav');
    if (favMatch) {
        if (count > 0) {
            favMatch.addClass('number-count-fav');
            favMatch.html(count);
        } else {
            favMatch.removeClass('number-count-fav');
            favMatch.html('');
        }
    }
}


//submenu
(function ($) {
    $.fn.menumaker = function (options) {
        var cssmenu = $(this), settings = $.extend({
            format: "dropdown",
            sticky: false
        }, options);
        cssmenu.find('li ul').parent().addClass('has-sub');
        multiTg = function () {
            cssmenu.find(".has-sub").prepend('<span class="submenu-button"> <i class="icon-chevron-down"></i> </span>');
            cssmenu.find('.submenu-button').on('click', function () {
                $(this).toggleClass('submenu-opened');
                if ($(this).siblings('ul').hasClass('open')) {
                    $(this).siblings('ul').removeClass('open').slideToggle();
                }
                else {
                    $(this).siblings('ul').addClass('open').slideToggle();
                }
            });
        };
        if (settings.format === 'multitoggle') multiTg()
        else cssmenu.addClass('dropdown')
        resizeFix = function () {
            //when resize window
        };
    }
})(jQuery);
(function ($) {
    $(document).ready(function () {
        $("#meumutilv").menumaker({
            format: "multitoggle"
        });
        $('.menu-item ul li a').hover(function () {
            $(this).siblings('span').toggleClass('active-arrow')
        })
    });
})(jQuery);

