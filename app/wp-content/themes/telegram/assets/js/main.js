
(function (e) {
  "use strict";
  var n = window.Telegram_JS || {};
  n.navigation = function() {
    e("ul#primary-menu>li,div#primary-menu>ul>li").has("ul").addClass("down-arrow");
    e("ul#primary-menu>li>ul li,div#primary-menu>ul>li>ul li").has("ul").addClass("right-arrow");
    // Newsletter popup
    e("#newsletter").on("click",function(){
      e("#newsletter-content").addClass("open");
      e("#newsletter-content").removeClass("close");
      e("#body-overlay").addClass("show");
      e("body").css("overflow","hidden");
      e('#newsletter-close').focus();

    });
    e("#newsletter-close").on("click",function(){
      e("#newsletter-content").addClass("close");
      e("#body-overlay").removeClass("show");
      e("body").css("overflow","visible");
      e('#newsletter').focus();
    });
    
    // Focus on search Colose
    e('.skip-link-search-start').focus(function(){
      e('#search-close').focus();
    });

    e('.skip-link-search-end').focus(function(){
      e('.twp-nav-search-content .search-field').focus();
    });

    e('.skip-link-search-start-1').focus(function(){
      e('.twp-nav-search-content .search-field').focus();
    });

    e('.skip-link-search-start-2').focus(function(){
      e('#search').focus();
    });

    // navigation Search
    e("#search").on("click",function(){
      e('body').toggleClass('search-reveal');
      e("#search-content").slideToggle();
      e('.twp-nav-search-content .search-field').focus();
    });
    e("#search-close").on("click",function(){
      e('body').toggleClass('search-reveal');
      e("#search-content").slideUp("close");
      e('#search').focus();
    });
    
    e('.skip-link-offcanvas-end-2').focus(function(){
      e('#offcanvas-close button').focus();
    });

    e('.skip-link-offcanvas-start').focus(function(){
      e('.skip-link-offcanvas-end-1').focus();
    });

    e('.skip-link-newsletter-start').focus(function () {
        e('.skip-link-newsletter-end-1').focus();
    });

    e('.skip-link-newsletter-end-2').focus(function () {
        e('#newsletter-close').focus();
    });

    e(document).keyup(function (j) {
        if (j.key === "Escape") { // escape key maps to keycode `27`

            if( e('#offcanvas-content').hasClass('open') ){
              e("#offcanvas-content").removeClass("open");
              e("#body-overlay").removeClass("show");
              e("body").css("overflow","visible");
              e('#offcanvas').focus();
            }

            if( e('#newsletter-content').hasClass('open') ){
              e("#newsletter-content").addClass("close");
              e("#body-overlay").removeClass("show");
              e("body").css("overflow","visible");
              e('#newsletter').focus();
            }

        }
    });

    // Offcanvas
    e("#offcanvas").on("click",function(){
      e('#offcanvas-close button').focus();
      e("#offcanvas-content").addClass("open");
      e("#body-overlay").addClass("show");
      e("body").css("overflow","hidden");
    });
    e("#offcanvas-close,#body-overlay").on("click",function(){
      e("#offcanvas-content").removeClass("open");
      e("#body-overlay").removeClass("show");
      e("body").css("overflow","visible");
    });
    
    // Dark and light mode
    e("#color-mode").on("click",function(){
      e("body").toggleClass("dark-mode");
    })

  };
  
  n.mobileMenu = function () {
    
    e("#nav-menu-icon").on("click",function(){
      
      e("#mobile-menu").addClass("open");
      e("#primary-menu").clone().appendTo(".menu-caontainer-mobile");
      e("#body-overlay").toggleClass("show");
      e("body").css("overflow","hidden");

      setTimeout(function(){

         e('#mobile-menu-close').focus();

      },300);
     

    });

    e("#mobile-menu-close,#body-overlay").on("click",function(){
        e("#mobile-menu").removeClass("open");
        e("#mobile-menu #primary-menu").remove();
        e("#body-overlay").removeClass("show");
        e("body").css("overflow","visible");

        setTimeout(function(){

         e('#nav-menu-icon').focus();
         
      },300);

    });

    e('.skip-link-menu-start').focus(function(){

      e('#primary-menu li:last-child a').focus();

    });

    e('.skip-link-menu-end').focus(function(){

      e('#mobile-menu-close').focus();

    });

    e(document).keyup(function(j) {
        if (j.key === "Escape") { // escape key maps to keycode `27`

          if( e("#mobile-menu").hasClass("open") ){
            e("#mobile-menu").removeClass("open");
            e("#mobile-menu #primary-menu").remove();
            e("#body-overlay").removeClass("show");
            e("body").css("overflow","visible");
            e('#nav-menu-icon').focus();

          }

          if( e("body").hasClass("search-reveal") ){

            e('body').toggleClass('search-reveal');
            e("#search-content").slideUp("close");
            e('#search').focus();

          }

        }
    });

  };
  n.stickyNav = function () {
    var stickyNav = document.getElementById("sticky-nav-menu");
    var navbar = document.getElementById("twp-navigation-bar");
    if(e("div").hasClass("sticky-nav-menu")){
      var sticky = stickyNav.offsetTop;
      if (window.pageYOffset > sticky) {
          navbar.classList.add("sticky");
      }else{
        navbar.classList.remove("sticky");
      }
    }
  },

  n.slider = function() {
    var rtlStatus = false;
    if( e('body').hasClass('rtl') ){
      rtlStatus = true;
    }

    e(".twp-breaking-news-slider").slick({
      speed: 3000,
      autoplay: true,
      autoplaySpeed: 0,
      cssEase: 'linear',
      slidesToShow: 3,
      slidesToScroll: 1,
      infinite: true,
      swipeToSlide: true,
      centerMode: true,
      focusOnSelect: true,
      dots: false,
      arrows: false,
      rtl: rtlStatus,
      responsive: [
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 576,
          settings: {
            slidesToShow: 1,
          }
        },
      ],
    });
    e('.twp-carousel-slider').slick({
      slidesToShow: 5,
      slidesToScroll: 1,
      arrows: true,
      dots: false,
      cssEase: 'linear',
      autoplay: true,
      rtl: rtlStatus,
      responsive: [
        {
          breakpoint: 1050,
          settings: {
            slidesToShow: 3,
          }
        },
        {
          breakpoint: 767,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 576,
          settings: {
            slidesToShow: 1,
          }
        },
      ],
    });
   
    e('.twp-banner-slider').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: true,
      cssEase: 'linear',
      autoplay: true,
      dots: false,
      rtl: rtlStatus,
    });
    e('.twp-exclusive-post-slider').slick({
      slidesToShow: 5,
      slidesToScroll: 1,
      arrows: true,
      dots: false,
      vertical: true,
      autoplay: true,
      cssEase: 'linear',
      verticalSwiping: true,
      // rtl: rtlStatus,
      prevArrow: e('.twp-exclusive-prev'),
      nextArrow: e('.twp-exclusive-next'),
    });

    
    e('.wp-block-gallery.columns-1 .blocks-gallery-grid,ul.wp-block-gallery.columns-1,.widget .gallery-columns-1').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: true,
      cssEase: 'linear',
      dots: false,
      rtl: rtlStatus,
    });
    
  };

  n.magnificPopUp = function () {
    e(".wp-block-gallery,.gallery").each(function () {
      e(this).magnificPopup({
          delegate: "a",
          type: "image",
          closeOnContentClick: false,
          closeBtnInside: false,
          mainClass: "mfp-with-zoom mfp-img-mobile",
          image: {
              verticalFit: true,
              titleSrc: function (item) {
                  return item.el.attr("title");
              }
          },
          gallery: {
              enabled: true
          },
          zoom: {
              enabled: true,
              duration: 300,
              opener: function (element) {
                  return element.find("img");
              }
          }
      });
    });
  },
  n.stickyScroll = function(){
    var navbar = document.getElementById("twp-navigation-bar");
    var navbarOffset = navbar.offsetTop;
    if( window.pageYOffset > navbarOffset){
      e("#scroll-top").addClass("show");
    }else{
      e("#scroll-top").removeClass("show");
    }
  };
  n.scrollToTop = function() {
    e("#scroll-top").on("click",function(){
      e("html,body").animate({
        scrollTop: 0
      },1000);
      return false;
    });
  };
  n.minHeight = function() {
    var windowHeight = e(window).height();
    var headerAD   = e('.twp-header-ad-section').outerHeight();
    var topbar     = e('.twp-topbar-section').outerHeight();
    var header     = e('#masthead').outerHeight();
    var breadcrumb = e(".twp-breadcrumbs").outerHeight();
    var contentMinHeight = (windowHeight - 65) - ( headerAD + topbar + header + breadcrumb );
    e(".twp-min-height").css("minHeight",contentMinHeight);
  };

  n.twpStickySidebar = function () {
    e('.banner-sticky-scroll, .widget-area, .widget-sticky-scroll').theiaStickySidebar({
        additionalMarginTop: 30
    });
  };
  e(window).on('load', function () { 
    e('#status').fadeOut(); 
    e('#preloader').delay(350).fadeOut('slow');  
    e('body').delay(350).css({ 'overflow': 'visible' });
  });

  e(document).ready(function () {
    n.navigation();
    n.mobileMenu();
    n.slider();
    n.magnificPopUp();
    n.twpStickySidebar();
    n.minHeight();
    n.scrollToTop();
  });
  
  e(window).scroll(function () {
    n.stickyNav();
    n.stickyScroll();
  });
  e(window).resize(function(){
    n.minHeight();
  });

})(jQuery);