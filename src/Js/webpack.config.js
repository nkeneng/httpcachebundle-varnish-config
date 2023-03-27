const Encore = require('@symfony/webpack-encore');

const PurgeCssPlugin = require('purgecss-webpack-plugin');
const glob = require('glob-all');
const path = require('path');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')

    .addEntry('app', './assets/app.js')

    .enableStimulusBridge('./assets/controllers.json')

    .splitEntryChunks()

    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()

    // .enableBuildNotifications()

    .enableSourceMaps(!Encore.isProduction())

    .enableVersioning(Encore.isProduction())

    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })

    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    .enableSassLoader()

    .enablePostCssLoader()

    .copyFiles([
        {
            from: './assets/main/img',
            to: 'images/[path][name].[hash:8].[ext]'
        }
    ])

    .autoProvidejQuery()

    .configureWatchOptions(function(watchOptions) {
        // enable polling and check for changes every 250ms
        // polling is useful when running Encore inside a Virtual Machine
        watchOptions.poll = 250;
    });

if (Encore.isProduction()) {
    console.log("Purging css ...");
    Encore.addPlugin(new PurgeCssPlugin({
        paths: glob.sync([
            path.join(__dirname, 'templates/**/*.html.twig'),
            path.join(__dirname, 'assets/**/*.js')
        ]),
        defaultExtractor: (content) => {
            return content.match(/[\w-/:]+(?<!:)/g) || [];
        },
        safelist: [/^iti/]
    }));
}

module.exports = Encore.getWebpackConfig();
