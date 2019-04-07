var Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')

    .addEntry('js/app', [
        './node_modules/bootstrap/dist/js/bootstrap.min.js',
        './assets/js/app.js',
        './node_modules/fontawesome/index.js'
    ])
    .addStyleEntry('css/app',[
        './node_modules/bootstrap/dist/css/bootstrap.min.css',
        './assets/css/app.css',
        './assets/css/app.scss'
    ])

    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())
    .enableSassLoader()

;

module.exports = Encore.getWebpackConfig();
