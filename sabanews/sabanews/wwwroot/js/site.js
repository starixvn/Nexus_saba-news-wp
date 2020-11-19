$(document).ready(function () {
    $('.play_video iframe').css({ "width": "425px", "height": "240px" });
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
})