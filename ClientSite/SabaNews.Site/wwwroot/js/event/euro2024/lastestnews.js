var euro2024_lastestnews = (function ($) {
    var numRow = 1;
    var isMobile = false;
    var total = 0;
    function showStandings() {
        if (isMobile === "False") {
            for (var i = 1; i <= total; i++) {
                if (i != numRow) {
                    $('#standing-' + i).hide();
                }
                else {
                    $('#standing-' + i).show();
                }
            }
        }
    }
    function getStandings() {
        $.ajax({
            url: '/Euro2024/GetStandings',
            type: 'GET',
            beforeSend: function() {
                console.log("loading.....")
            },
        }).done(function (data) {
            console.log("done.....")
            $('.euro-top-standing').html(data);

            total = $(".btn-next-standing").data('total');
            isMobile = $(".euro-top-standing").data('ismobile');
            showStandings();
            $('.btn-back-standing').on('click', function () {
                numRow--;
                if (numRow >= 1) {
                    showStandings();
                }
                else {
                    numRow++;
                }
            });

            $('.btn-next-standing').on('click', function () {
                numRow++;
                var total = $(this).data("total");
                if (numRow <= total) {
                    showStandings();
                }
                else {
                    numRow--;
                }
            });
        })
    }
    function HandleMenuTab () {
        $(document).on('click', '.nav-tab-js .nav-tab-item', function(){
            $('.nav-tab-js li').removeClass('active');
            $(this).parent().addClass('active');
            let currentTab = $(this).attr('data-id');
            $('.tab-content .wc-table-body').hide();
            $(currentTab).show();
            return false;
        });
    }
    

    function init() {
        getStandings();
        HandleMenuTab();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    euro2024_lastestnews.init();
});