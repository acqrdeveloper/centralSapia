const {mix} = require('laravel-mix');
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

mix.js('resources/assets/js/app.js', 'public/dist');
// mix.sass('resources/assets/sass/app.scss', 'public/sapia/css');

mix.copyDirectory("node_modules/font-awesome/fonts","public/fonts");

mix.styles([
    "node_modules/bootstrap/dist/css/bootstrap.css",
    "node_modules/font-awesome/css/font-awesome.css",
    "public/vendor/css/sb-admin.css"
], "public/dist/main.css");

mix.js([
    "node_modules/jquery/dist/jquery.js",
    "node_modules/bootstrap/dist/js/bootstrap.bundle.js",
    "node_modules/jquery.easing/jquery.easing.js",
    "public/vendor/js/sb-admin.js"
], "public/dist/main.js");
