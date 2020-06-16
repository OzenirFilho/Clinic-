const mix = require("laravel-mix");

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
mix.copyDirectory(__dirname + "/resources/img/", `public/argon/img/`);
mix.copyDirectory(__dirname + "/resources/vendor/", `public/argon/vendor/`);

mix.js("resources/js/argon.js", "public/argon/js/")
    .js("resources/js/app.js", "public/js/app.js")
    .sass("resources/sass/argon-dashboard.scss", "public/argon/css")
    .version();
