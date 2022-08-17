const path = require('path');

const projectRoot = path.resolve(__dirname, '../../../');

const cssPaths = {
    build: 'wwwroot/css',

    livescore_soccer_index: ['./www/sources/css/pages/livescore/soccer/index.scss'],
    livescore_soccer_league_detail: ['./www/sources/css/pages/livescore/soccer/league-detail.scss'],
    livescore_soccer_detail: ['./www/sources/css/pages/livescore/soccer/detail.scss'],
    livescore_soccer_favourite_match: ['./www/sources/css/pages/livescore/soccer/favourite-match.scss'],
    livescore_soccer_favourite_league: ['./www/sources/css/pages/livescore/soccer/favourite-league.scss'],
    livescore_soccer_worldcup_fixture: ['./www/sources/css/pages/livescore/soccer/world-cup/fixture.scss'],
    livescore_soccer_worldcup_full_fixture: ['./www/sources/css/pages/livescore/soccer/world-cup/full-fixture.scss'],
    livescore_soccer_worldcup_standing_playoff: ['./www/sources/css/pages/livescore/soccer/world-cup/standing-playoff.scss']
};

const scriptPaths = {
    build: 'wwwroot/js',

    language: ['./www/sources/js/common/languages/ViewResources_vi_VN.js'],
    livescore_soccer_index: ['./www/sources/js/pages/livescore/soccer/index/index.jsx'],
    livescore_soccer_league_detail: ['./www/sources/js/pages/livescore/soccer/league-detail/index.jsx'],
    livescore_soccer_detail: ['./www/sources/js/pages/livescore/soccer/detail/index.jsx'],
    livescore_soccer_favourite_match: ['./www/sources/js/pages/livescore/soccer/favourite-match/index.jsx'],
    livescore_soccer_favourite_league: ['./www/sources/js/pages/livescore/soccer/favourite-league/index.jsx'],
    livescore_soccer_worldcup_fixture: ['./www/sources/js/pages/livescore/soccer/world-cup/fixture/index.jsx'],
    livescore_soccer_worldcup_full_fixture: ['./www/sources/js/pages/livescore/soccer/world-cup/full-fixture/index.jsx'],
    livescore_soccer_worldcup_standing_playoff: ['./www/sources/js/pages/livescore/soccer/world-cup/standing-playoff/index.jsx']
};

module.exports = {
    projectRoot: projectRoot,
    cssPaths: cssPaths,
    scriptPaths: scriptPaths
};