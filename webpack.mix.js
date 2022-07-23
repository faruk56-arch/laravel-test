const mix = require('laravel-mix');
const { BugsnagSourceMapUploaderPlugin } = require('webpack-bugsnag-plugins')


/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/views/litiges.js', 'public/js')
    .js('resources/js/modernizr-custom.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/product.scss', 'public/css')
    .sass('resources/sass/home.scss', 'public/css')

let config = {
    output: {
        chunkFilename: '[name].[contenthash].js',
    },
}

if (mix.inProduction()) {
    mix.version();
    mix.sourceMaps()
    config = {
        devtool: 'source-map',
        plugins: [
            new BugsnagSourceMapUploaderPlugin({
                apiKey: process.env.MIX_BUGSNAG_KEY,
                appVersion: "1",
                publicPath: '*/**',
                overwrite: true
            })
        ],
        ...config
    }
}

mix.webpackConfig(config);
