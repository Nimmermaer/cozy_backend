// config.js

const StyleDictionary = require('style-dictionary');

// Stellt sicher, dass die SCSS-Datei im SCSS-Quellordner liegt,
// damit sie von main.scss importiert werden kann.
const outputDir = 'Resources/Private/Assets/Scss/';

StyleDictionary.extend({
    // Quelle: Wir suchen im './tokens/' Ordner nach allen JSON-Dateien
    source: [
        './Tokens/**/*.json'
    ],

    platforms: {
        scss: {
            buildPath: outputDir,
            // Die 'scss' transformGroup wendet Standard-Konvertierungen für SCSS an
            transformGroup: 'scss',
            files: [
                {
                    // Ziel-Dateiname, der später in main.scss importiert wird
                    destination: '_tokens.scss',
                    format: 'scss/variables',
                    options: {
                        outputReferences: true
                    }
                }
            ]
        }
    }
}).buildAllPlatforms();