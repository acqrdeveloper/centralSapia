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

mix.js(['resources/assets/js/app.js'], 'public/dist');
mix.sass("public/vendor/scss/style.scss","public/build/css/style.css");
// mix.sass('resources/assets/sass/app.scss', "public/build/css/app.css");

mix.copyDirectory("node_modules/font-awesome/fonts","public/fonts");


mix.styles([
    "node_modules/bootstrap/dist/css/bootstrap.css",
    "node_modules/font-awesome/css/font-awesome.css",
    "node_modules/vue-multiselect/dist/vue-multiselect.min.css",
    "public/vendor/css/sb-admin.css",
    "public/build/css/style.css"
], "public/dist/main.css");

mix.js([
    "node_modules/jquery/dist/jquery.js",
    "node_modules/bootstrap/dist/js/bootstrap.bundle.js",
    "node_modules/jquery.easing/jquery.easing.js"
], "public/dist/main.js");

