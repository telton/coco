let mix = require('laravel-mix');

// Shut off system notifications.
mix.disableNotifications();

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
mix.copy('node_modules/font-awesome/fonts', 'public/fonts/');
mix.sass('resources/assets/sass/app.scss', 'public/css/app.css');
mix.js('resources/assets/js/bootstrap.js', 'public/js/app.js');
