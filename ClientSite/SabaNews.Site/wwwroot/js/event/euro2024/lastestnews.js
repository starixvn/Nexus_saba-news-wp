var euro2024_lastestnews = (function ($) {
    function getLastestNews() {
        console.log('euro2024_lastestnews')
    }

    function init() {
        getLastestNews();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    euro2024_lastestnews.init();
});