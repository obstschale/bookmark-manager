let mix = require('laravel-mix');
const fs = require('fs');
const path = require('path');
const webpack = require('webpack');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

/** Styles / Scripts for Bookmark Manager Backend */
mix.js(['resources/js/admin/app.js', 'resources/js/admin/carbon-field.js'], 'public/js/admin.js')
    .sass('resources/scss/admin/app.scss', 'public/css/admin.css');

/** Styles / Scripts for Bookmarklet Screen */
mix.js('resources/js/bookmarklet/app.js', 'public/js/bookmarklet.js')
    .sass('resources/scss/bookmarklet/style.scss', 'public/css/bookmarklet.css');

/** The actual bookmarklet script */
mix.minify('resources/js/bookmarklet/bookmark-this.js')
    .copy('resources/js/bookmarklet/bookmark-this.min.js', 'public/js/bookmark-this.js');
mix.js('resources/js/bookmarklet/bookmark-this-toggle.js', 'public/js/bookmark-this-toggle.js');

/** Carbon Field */
mix.sass('resources/scss/carbon-fields/field.scss', 'public/css/field.css');
    // .js('resources/js/carbon-fields/bootstrap.js', 'public/js/bundle.js');



/**
 * This path assumes that Carbon Fields and the current template
 * are both in the `/plugins` directory. Change if according to your needs.
 */
const root = path.resolve(__dirname, './vendor/htmlburger/carbon-fields');

if (!fs.existsSync(root)) {
    console.error('Could not find Carbon Fields folder.');
    process.exit(1);
    return;
}

mix.webpackConfig({
    entry: './resources/js/carbon-fields/bootstrap.js',

    output: {
        path: path.resolve(__dirname, 'public/js'),
        filename: 'bundle.js'
    },

    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader',
                options: {
                    cacheDirectory: true
                }
            }
        ]
    },

    resolve: {
        modules: [
            path.resolve(__dirname, 'resources/js/carbon-fields'),
            path.resolve(root, 'assets/js'),
            'node_modules'
        ]
    },

    plugins: [
        new webpack.ProvidePlugin({
            'jQuery': 'jquery'
        }),

        new webpack.DllReferencePlugin({
            sourceType: 'this',
            manifest: require(path.resolve(root, 'assets/dist/carbon.vendor.json')),
        }),

        new webpack.DllReferencePlugin({
            context: root,
            sourceType: 'this',
            manifest: require(path.resolve(root, 'assets/dist/carbon.core.json'))
        })
    ],

    externals: {
        'jquery': 'jQuery'
    },

    devtool: '#cheap-module-eval-source-map'
});
