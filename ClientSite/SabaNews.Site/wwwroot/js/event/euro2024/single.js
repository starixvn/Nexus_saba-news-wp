var euro2024_single = (function ($) {
    function getSigle() {
        console.log('getSigle')
    }

    function init() {
        getSigle();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    euro2024_single.init();
});