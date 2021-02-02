var auth = (function ($) {
    function HandleCloseModal() {
        $('#confirmModal').on('hidden.bs.modal', function () {
            document.location = '/';
        });
    }

    function init() {
        $('#confirmModal').modal('show');
        HandleCloseModal();
    }

    return {
        init
    }

})(jQuery);

$(document).ready(function () {
    auth.init();
})