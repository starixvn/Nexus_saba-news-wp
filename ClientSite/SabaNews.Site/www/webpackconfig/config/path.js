const path = require('path');

const projectRoot = path.resolve(__dirname, '../../../');

const cssPaths = {
    build: 'wwwroot/css',

    livescore_soccer_index: ['./www/sources/css/pages/livescore/soccer/index.scss'],
    livescore_soccer_league_detail: ['./www/sources/css/pages/livescore/soccer/league-detail.scss'],
    livescore_soccer_detail: ['./www/sources/css/pages/livescore/soccer/detail.scss'],
    livescore_soccer_favourite_match: ['./www/sources/css/pages/livescore/soccer/favourite-match.scss']
};

const scriptPaths = {
    build: 'wwwroot/js',

    language: ['./www/sources/js/common/languages/ViewResources_vi_VN.js'],
    livescore_soccer_index: ['./www/sources/js/pages/livescore/soccer/index/index.jsx'],
    livescore_soccer_league_detail: ['./www/sources/js/pages/livescore/soccer/league-detail/index.jsx'],
    livescore_soccer_detail: ['./www/sources/js/pages/livescore/soccer/detail/index.jsx'],
    livescore_soccer_favourite_match: ['./www/sources/js/pages/livescore/soccer/favourite-match/index.jsx']
};

module.exports = {
    projectRoot: projectRoot,
    cssPaths: cssPaths,
    scriptPaths: scriptPaths
};