'use strict';

const ENV = process.env.NODE_ENV || 'development';
const ENV_IS_PROD = ENV !== 'development';

const webpack = require('webpack');
const path = require('path');
const merge = require('webpack-merge');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const autoprefixer = require('autoprefixer');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const ProgressBarPlugin = require('progress-bar-webpack-plugin');

const paths = {
    src: path.resolve(__dirname, 'html'),
    dist: path.resolve(__dirname, 'gulp', 'runtime', 'dist')
};

module.exports = {
    mode: ENV,
    // watch: true, // use 'npm run watch'
    devtool: ENV_IS_PROD ? 'source-map' : 'cheap-inline-module-source-map',
    entry: {
        app: path.resolve(paths.src, 'index.js'),
        calculator: path.resolve(paths.src, 'calculator.js'),
        'calc-annuitet': path.resolve(paths.src, 'calc-annuitet.js'),
    },
    output: {
        path: paths.dist,
        filename: `js/[name].js`
    },
    optimization: {
        splitChunks: {
            cacheGroups: {
                vendor: {
                    test: /node_modules|vendor/,
                    chunks: 'initial',
                    name: 'vendor',
                    priority: 10,
                    enforce: true
                }
            }
        }
    },
    module: {
        rules: [
            // sass
            {
                test: /\.scss$/,
                include: path.resolve(paths.src, 'scss'),
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: {
                            url: false,
                            import: false,
                            sourceMap: true
                        }
                    },
                    {
                        loader: 'postcss-loader',
                        options: {
                            plugins: [
                                autoprefixer({
                                    browsers: [
                                        'ie >= 10',
                                        'last 3 version'
                                    ]
                                })
                            ],
                            sourceMap: true
                        }
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: true
                        }
                    }
                ]
            },
            // js
            {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            },
        ]
    },
    plugins: [
        // чистим dist
        new CleanWebpackPlugin(paths.dist),

        // copy files
        new CopyWebpackPlugin([
            {
                from: path.resolve(paths.src),
                to: path.resolve(paths.dist),
                ignore: [
                    'index.js',
                    'calculator.js',
                    'calc-annuitet.js',
                    'js/*',
                    'scss/*',
                ]
            }
        ]),

        // работаем с внешним CSS
        new MiniCssExtractPlugin({
            filename: `/css/styles.css`
        }),

        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery',
        }),

        new webpack.DefinePlugin({
            'process.env.NODE_ENV': JSON.stringify(ENV)
        }),

        new ProgressBarPlugin({
            format: 'Build [:bar] :percent (:elapsed seconds)',
            clear: false,
        })
    ],
};

if (ENV_IS_PROD) { // prod
    module.exports = merge(module.exports, {
        optimization: {
            minimizer: [
                new UglifyJsPlugin({
                    cache: true,
                    parallel: true,
                    sourceMap: true
                }),
                new OptimizeCSSAssetsPlugin({
                    cssProcessorOptions: {
                        map: {
                            inline: false,
                            annotation: true,
                        }
                    }
                })
            ]
        }
    });
} else { // dev
    module.exports = merge(module.exports, {
        watchOptions: {
            aggregateTimeout: 100
        },
    });
}
