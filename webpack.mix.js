const mix = require('laravel-mix');
const webpackOverride = require('./webpack.config');

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

mix.js('resources/js/app.js', 'public/js');

mix.sass('resources/scss/main.scss', 'public/css/app.css').options({
  autoprefixer: {
    options: {
      browsers: ['last 10 versions']
    }
  }
});

mix.copyDirectory('resources/images', 'public/images');

mix.browserSync('localhost:9090');

mix.webpackConfig(webpackOverride);
