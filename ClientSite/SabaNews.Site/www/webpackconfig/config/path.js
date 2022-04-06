const path = require('path');

const projectRoot = path.resolve(__dirname, '../../../');

const cssPaths = {
    build: 'wwwroot/css',

    livescore_soccer_index: ['./www/sources/css/pages/livescore/soccer/index.scss'],
    livescore_soccer_detail: ['./www/sources/css/pages/livescore/soccer/detail.scss']
};

const scriptPaths = {
    build: 'wwwroot/js',

    language: ['./www/sources/js/common/languages/ViewResources_vi_VN.js'],
    main: ['./www/sources/js/pages/main.jsx'],
    livescore_soccer_index: ['./www/sources/js/pages/livescore/soccer/index/index.jsx'],
    livescore_soccer_detail: ['./www/sources/js/pages/livescore/soccer/detail/index.jsx']
};

module.exports = {
    projectRoot: projectRoot,
    cssPaths: cssPaths,
    scriptPaths: scriptPaths
};