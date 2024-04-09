var euro2024_home = (function ($) {
    function getHome() {
        console.log(1111111)
    }

    function init() {
        getHome();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    euro2024_home.init();
});