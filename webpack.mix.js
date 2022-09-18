const mix = require('laravel-mix')

mix.webpackConfig({
    stats: {
        children: true,
    },
})

mix.copy('node_modules/jquery/dist/jquery.min.js', 'public/js/jquery.min.js')
    .copy('node_modules/jquery/dist/jquery.min.js', 'public/js/jquery.min.map')
    .copy('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js', 'public/js/bootstrap.bundle.min.js')
    .copy('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js.map', 'public/js/bootstrap.bundle.min.js.map')
    .copy('node_modules/chart.js/dist/chart.min.js', 'public/js/chart.min.js')
    .copy('node_modules/bootstrap-icons/font/bootstrap-icons.css', 'public/css/bootstrap-icons.css')
    .copy('node_modules/bootstrap-icons/font/fonts/bootstrap-icons.woff', 'public/css/fonts/bootstrap-icons.woff')
    .copy('node_modules/bootstrap-icons/font/fonts/bootstrap-icons.woff2', 'public/css/fonts/bootstrap-icons.woff2')
    .copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'public/css/bootstrap.min.css')
    .copy('node_modules/bootstrap/dist/css/bootstrap.min.css.map', 'public/css/bootstrap.min.css.map')
    .js('resources/js/app.js', 'public/js/app.min.js').sourceMaps()
    .css('resources/css/app.css', 'public/css/app.min.css').sourceMaps()
    .version()
    .disableNotifications()
