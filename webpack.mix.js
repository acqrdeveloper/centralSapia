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

mix.js(['resources/assets/js/app.js'], 'public/dist/js/app.js');
mix.sass("public/vendor/scss/sb-admin.scss","public/build/css/sb-admin.css");
mix.sass('public/vendor/scss/style.scss',"public/build/css/style.css");

mix.copyDirectory("node_modules/font-awesome/fonts","public/dist/fonts");

mix.styles([
    "node_modules/bootstrap/dist/css/bootstrap.css",
    "node_modules/font-awesome/css/font-awesome.css",
    "node_modules/vue-multiselect/dist/vue-multiselect.min.css",
    "public/build/css/sb-admin.css",
    "public/build/css/style.css"
], "public/dist/css/main.css");

mix.js([
    "node_modules/jquery/dist/jquery.js",
    "node_modules/bootstrap/dist/js/bootstrap.bundle.js",
    "node_modules/jquery.easing/jquery.easing.js"
], "public/dist/js/main.js");

