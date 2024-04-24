var euro2024_lastestnews = (function ($) {
    function getStandings() {
        $.ajax({
            url: '/Euro2024/GetStandings',
            type: 'GET',
            beforeSend: function() {
            },
        }).done(function (data) {
            $('.euro-top-standing').html(data);
        })
    }
    function HandleStandingTab () {
        $(document).on('click', '.nav-tab-js li', function(){
            console.log( $(this).attr('data-id'));
            $('.nav-tab-js li').removeClass('active');
            $(this).addClass('active');
            let currentTab = $(this).attr('data-id');
            $('.tab-content .wc-table-body').hide();
            $('.' + currentTab).show();
            return false;
        });
    }

    function init() {
        getStandings();
        HandleStandingTab();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    euro2024_lastestnews.init();
});