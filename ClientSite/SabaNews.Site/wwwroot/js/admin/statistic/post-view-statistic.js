var postViewStatistic = (function ($) {
    function getPostViewStatistic() {
        var data = {
            FromDate: new Date($("#FromDate").val()).toISOString(),
            ToDate: new Date($("#ToDate").val()).toISOString()
        };
        $.ajax({
            url: "PostViewStatistic",
            method: "POST",
            data: data,
            success: function (result) {
                $("#statistic-report").html(result);
            }
        });
    }

    function initEventSubmitBtn() {
        $("#filter").on("submit", function (e) {
            e.preventDefault();
            getPostViewStatistic();
        });
    }

    function init() {
        initEventSubmitBtn();
        getPostViewStatistic();
    }

    return { init };
})(jQuery);

$(document).ready(function () {
    postViewStatistic.init();
});