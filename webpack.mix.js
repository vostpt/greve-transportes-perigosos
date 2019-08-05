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

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .styles('resources/css/map.css', 'public/css/map.css')
   .js('resources/js/map.js', 'public/js')
   .js('resources/js/stats.js', 'public/js')
   .copy('resources/js/map_direct.js', 'public/js/map_direct.js')
   .js('resources/js/map_load.js', 'public/js');
   
if (mix.inProduction()) {
    mix.version();
}