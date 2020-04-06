const mix = require('laravel-mix');

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

mix.js('resources/js/home.js', 'public/js')
    .js('resources/js/global.js', 'public/js')
    .js('resources/js/manage/document.js', 'public/js/manage')
    .js('resources/js/manage/for_approval.js', 'public/js/manage')
    .js('resources/js/manage/for_review.js', 'public/js/manage')
    .js('resources/js/system.js', 'public/js')
    .js('resources/js/category.js', 'public/js')
    .js('resources/js/section.js', 'public/js')
    .js('resources/js/account.js', 'public/js')
    .js('resources/js/draft.js', 'public/js')
    .js('resources/js/site-traffic.js', 'public/js')
    .js('resources/js/trashed-document.js', 'public/js')
    .js('resources/js/profile.js', 'public/js')
    .js('resources/js/content-title.js', 'public/js')
    .sass('resources/sass/document_content.scss', 'public/css')
