var worldcup = (function ($) {
    var leagueId = 14638;
    var seasonId = 10810;
    function getStanding() {
        var param = JSON.stringify({ leagueId, seasonId });
        
        $.ajax({
            type: 'POST',
            url: '/worldcup2022/GetHomepageStandings',
            data: param,
            contentType: "application/json",
            success: function (res) {
                $('#home-standing').html(res);
            }
        })
    }
    function init() {
        getStanding();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    worldcup.init();
});