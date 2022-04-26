const alias = require('../config/alias');
const pathConfig = require('../config/path');
const pluginGenerator = require('../config/plugin');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');

function generateConfig(environment) {
    const jsPath = `${pathConfig.scriptPaths.build}/${environment}`;
    const cssPath = `${pathConfig.cssPaths.build}/${environment}`;
    return {
        resolve: {
            alias: alias,
            extensions: ['.js', '.jsx']
        },
        entry: {
            /*js*/
            [`${jsPath}/lib/lang-vi-VN`]: pathConfig.scriptPaths.language,
            [`${jsPath}/pages/main`]: pathConfig.scriptPaths.main,
            [`${jsPath}/pages/livescore/soccer/index`]: pathConfig.scriptPaths.livescore_soccer_index,
            [`${jsPath}/pages/livescore/soccer/league-detail`]: pathConfig.scriptPaths.livescore_soccer_league_detail,
            [`${jsPath}/pages/livescore/soccer/detail`]: pathConfig.scriptPaths.livescore_soccer_detail,
            [`${jsPath}/pages/livescore/soccer/favourite-match`]: pathConfig.scriptPaths.livescore_soccer_favourite_match,

            /*css*/
            [`${cssPath}/pages/livescore/soccer/index`]: pathConfig.cssPaths.livescore_soccer_index,
            [`${cssPath}/pages/livescore/soccer/league-detail`]: pathConfig.cssPaths.livescore_soccer_league_detail,
            [`${cssPath}/pages/livescore/soccer/detail`]: pathConfig.cssPaths.livescore_soccer_detail,
            [`${cssPath}/pages/livescore/soccer/favourite-match`]: pathConfig.cssPaths.livescore_soccer_favourite_match
        },
        output: {
            path: pathConfig.projectRoot,
            filename: "[name].chunk.js",
            chunkFilename: `${jsPath}/lib/[name].chunk.js`
        },
        module: {
            rules: [
                {
                    test: /\.(js|jsx)$/,
                    loader: ["babel-loader"],
                    exclude: /node_modules/
                }
            ]
        },
        plugins: pluginGenerator(environment),
        stats: {
            assets: false,
            colors: true,
            version: false,
            hash: false,
            timings: true,
            chunks: false,
            chunkModules: false,
            children: true
        },
        optimization: {
            minimizer: [
                new UglifyJsPlugin({
                    sourceMap: true,
                    uglifyOptions: {
                        output: {
                            comments: false
                        }
                    }
                }),
                new OptimizeCssAssetsPlugin()
            ],
            splitChunks: {
                chunks: 'all',
                minSize: 30000,
                maxSize: 0,
                minChunks: 2,
                maxAsyncRequests: 5,
                maxInitialRequests: 3,
                name: false,
                cacheGroups: {
                    // disable default cache group
                    default: false,
                    vendors: false,
                    reactVendor: {
                        name: 'react-vendor',
                        test: /[\\/]node_modules[\\/](react|react-dom)[\\/]/,
                        minChunks: 2,
                        priority: -10
                    },
                    reactBootstrapVendor: {
                        name: 'react-bootstrap-vendor',
                        test: /[\\/]node_modules[\\/](react-bootstrap)[\\/]/,
                        minChunks: 2,
                        priority: -10
                    }
                }
            }
        }
    };
}

module.exports = generateConfig;