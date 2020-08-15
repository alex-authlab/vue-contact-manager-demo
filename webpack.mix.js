let mix = require('laravel-mix');

mix.setPublicPath('dist');
mix.setResourceRoot('../');

mix.js('src/app.js', 'dist/')
    .sass('src/app.scss', 'dist/');
