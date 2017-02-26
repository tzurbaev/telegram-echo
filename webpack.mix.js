const { mix } = require('laravel-mix');

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

mix.disableNotifications()
mix.options({
    processCssUrls: false,
})

mix.js('resources/assets/js/app.js', 'public/js')
   .combine([
        'resources/assets/css/semantic.css',
        'node_modules/font-awesome/css/font-awesome.css',
    ], 'public/css/vendor.css')
   .combine([
        'resources/assets/scripts/jquery-3.1.1.min.js',
        'resources/assets/scripts/semantic.js',
    ], 'public/js/vendor.js')
   .less('resources/assets/less/app.less', 'public/css')
   .copy('resources/assets/css/themes', 'public/css/themes', false)
   .copy('node_modules/font-awesome/fonts', 'public/fonts', false)
