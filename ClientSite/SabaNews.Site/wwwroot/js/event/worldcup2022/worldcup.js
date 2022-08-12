var worldcup = (function ($) {

    function getTopScorers() {
        if ($('#topScorersWC')) {
            $.ajax({
                url: '/WorldCup2022/GetTopScorers',
                type: 'GET',
            }).done(function (data) {
                $('#topScorersWC').html(data);
            })
        }
    }




    function init() {
        getTopScorers();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    worldcup.init();

    $(window).scroll(function () {
        console.log('1111')
    });
});

