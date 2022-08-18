var standing = (function ($) {
    function handleBtn() {
        $('#detail-standing').show();
        $('#knock-out').hide();
        $('#btn-detail-standing').on('click', function () {
            $('#detail-standing').show();
            $('#knock-out').hide();
        });

        $('#btn-knock-out').on('click', function () {
            $('#detail-standing').hide();
            $('#knock-out').show();
        });
    }

    function init() {
        handleBtn();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    standing.init();
});
