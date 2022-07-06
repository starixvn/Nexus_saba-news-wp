var livescore = (function ($) {
    function RefreshFavMatch() {
        let matchIds = getFavouriteMatches();
        if (matchIds.length === 0) {
            return false;
        }
        matchIds = JSON.stringify(matchIds);
        $.ajax({
            type: "POST",
            url: "/soccer/GetMatchByIds",
            data: { matchIds },
            success: function (response) {
                const newIds = response.map(item => item.matchId);
                localStorage.setItem('favourite-matches', JSON.stringify(newIds));
                getFavouriteMatchCount(newIds);
            }
        });
    }

    function getFavouriteMatchCount(matchIds) {
        const count = matchIds.length;
        const favMatch = $('.mobile-fav');
        if (favMatch) {
            if (count > 0) {
                favMatch.addClass('number-count-fav');
                favMatch.html(count);
            } else {
                favMatch.removeClass('number-count-fav');
                favMatch.html('');
            }
        }
    }

    function getFavouriteMatches() {
        let matchIds = localStorage.getItem('favourite-matches');
        if (!matchIds) {
            matchIds = [];
        } else {
            matchIds = JSON.parse(matchIds.toString());
            matchIds = matchIds.filter(match => match !== null);
        }

        return matchIds;
    }
    function init() {
        RefreshFavMatch();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    livescore.init();
});
