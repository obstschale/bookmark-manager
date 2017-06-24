let mix = require('laravel-mix');

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
mix.js(['resources/assets/js/admin/app.js', 'resources/assets/js/admin/carbon-field.js'], 'public/js/admin.js')
    .sass('resources/assets/scss/admin/app.scss', 'public/css/admin.css');

/** Styles / Scripts for Bookmarklet Screen */
mix.js('resources/assets/js/bookmarklet/app.js', 'public/js/bookmarklet.js')
    .sass('resources/assets/scss/bookmarklet/style.scss', 'public/css/bookmarklet.css');

/** The actual bookmarklet script */
mix.js('resources/assets/js/bookmarklet/bookmark-this.js', 'public/js');
