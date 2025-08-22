import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import svgLoader from 'vite-svg-loader';
import { fileURLToPath, URL } from 'node:url';
import tailwindcss from 'tailwindcss'
import autoprefixer from 'autoprefixer'


export default defineConfig({
    plugins: [vue(), svgLoader()],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./src', import.meta.url)),
        }
    },
    css: {
        postcss: {
            plugins: [
                tailwindcss,
                autoprefixer,
            ]
        }
    },
    optimizeDeps: {
        exclude: ['vue-search-select']
    }
})
