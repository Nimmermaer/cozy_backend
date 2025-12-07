const path = require('path');

module.exports = {
    mode: 'development', // Oder 'production' für die finale Version
    entry: {
        bundle: './Assets/main',
        'service-worker': './Assets/service-worker',
    },
    module: {
        rules: [
            {
                test: /\.ts$/,
                use: 'ts-loader',
                exclude: /node_modules/,
            },
            {
                test: /\.scss$/, // Sucht nach allen .scss Dateien
                use: [
                    'style-loader',   // 3. Fügt das CSS in das DOM ein
                    'css-loader',     // 2. Interpretiert @import und url()
                    'sass-loader',    // 1. Kompiliert Sass zu CSS
                ],
            },
        ],
    },
    resolve: {
        extensions: ['.ts', '.js'],
    },
    output: {
        filename: '[name].js', // [name] wird zu bundle.js oder service-worker.js
        path: path.resolve(__dirname, '../../../Public/Assets/Mobile'),
    },
};