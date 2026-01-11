import { defineConfig } from 'vite';
import { resolve } from 'path';
import { execSync } from 'child_process';

console.log('Generating Design Tokens...');
execSync('node config.js', { stdio: 'inherit' });

export default defineConfig({
  build: {
    // Basis-Ordner für alle generierten Dateien
    outDir: 'Resources/Public',
    emptyOutDir: false,

    // Verhindert Konflikte im Watch-Modus, da wir in Unterordner von Resources schreiben
    watch: {
      exclude: ['Resources/Public/**']
    },

    rollupOptions: {
      input: {
        'main': resolve(__dirname, 'Resources/Private/Assets/JavaScript/main.js'),
        'custom': resolve(__dirname, 'Resources/Private/Assets/JavaScript/custom.js'),

        // Mobile Setup
        'mobile-bundle': resolve(__dirname, 'Resources/Private/Mobile/Pages/Assets/main.ts'),
        'mobile-sw': resolve(__dirname, 'Resources/Private/Mobile/Pages/Assets/service-worker.ts'),
      },

      output: {
        entryFileNames: (chunkInfo) => {
          // 1. Spezielle Behandlung für den Service Worker
          if (chunkInfo.name === 'mobile-sw') {
            // Resultat: Resources/Public/Assets/Mobile/sw.js
            return 'Assets/Mobile/sw.js';
          }

          // 2. Andere Mobile Dateien (z.B. mobile-bundle)
          if (chunkInfo.name.startsWith('mobile-')) {
            const name = chunkInfo.name.replace('mobile-', '');
            // Resultat: Resources/Public/Assets/Mobile/bundle.js
            return `Assets/Mobile/${name}.js`;
          }
          return 'Assets/JavaScript/[name].js';
        },

        assetFileNames: (assetInfo) => {
          const assetName = assetInfo.names ? assetInfo.names[0] : (assetInfo.name || '');

          if (assetName.endsWith('.css')) {
            // Mobile CSS Logik
            if (assetName.includes('mobile') || assetName.includes('bundle')) {
              return 'Assets/Mobile/Css/[name][extname]';
            }
            // Standard Backend CSS
            return 'Assets/Css/[name][extname]';
          }

          // Fallback für Bilder/Fonts
          return 'Assets/[name][extname]';
        }
      },

      onwarn(warning, warn) {
        // Ignoriere Eval-Warnungen von htmx
        if (warning.code === 'EVAL' && warning.id?.includes('htmx.org')) {
          return;
        }
        warn(warning);
      },
    }
  }
});
