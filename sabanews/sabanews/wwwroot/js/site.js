$(document).ready(function () {
    $('.play_video iframe').css({  "height": "340px" });
    $('.play_video iframe').hide();
    $('.play_video iframe').attr('allow', 'autoplay');
    $('.btn-play-video').on('click', function () {
        var iframe = $(this).parent().parent().find('iframe');
        var src = iframe.attr('src');
        var symbol = src.indexOf("?") > -1 ? "&" : "?";
        iframe.attr('src', src + symbol + "autoplay=1");
        $(this).parent().hide();
        iframe.show();
    });

    //match schedule
    $('.match-schedule iframe').css('width', '100%');

    $('.search-input button').on('click', function (e) {
        var keyword = $('input[name=keyword]').val();
        if ($.trim(keyword) == '') {
            e.preventDefault();
            $('input[name=keyword]').focus();
        }
    });
    $('.swiper-slide').last().show();
       
    $(window).on('resize', function () {
        if ($(window).width() < 992) {
            $('.swiper-slide').show();
        } else {
            $('.swiper-slide').last().show();
        }
    });
    
    $('.image-slider').on('click', function () {
        var id = $(this).data('id');
        $('.swiper-slide').hide();
        $('#slide-' + id).show();
        $('.flex-item').removeClass("active");
        $(this).parent().parent().addClass("active");
        //$('iframe').attr('src', $('iframe').attr('src'));//stop all iframe

        var iframe = $('#slide-' + id).find('iframe');
        var src = iframe.attr('src');
        var symbol = src.indexOf("?") > -1 ? "&" : "?";
        iframe.attr('src', src + symbol + "autoplay=1");
        $('#slide-' + id).find('.img-item').hide();
        iframe.show();
    });

   
})