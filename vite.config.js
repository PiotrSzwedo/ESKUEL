// vite.config.js
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
  plugins: [
      vue(),
      tailwindcss(),
  ],
  root: './resources/frontend',
  base: './',
  build: {
    manifest: true,
    outDir: './../js/',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        form: path.resolve(__dirname, 'resources/frontend/form.ts'),
        panel: path.resolve(__dirname, 'resources/frontend/matherBoard.ts')
      },
      output: {
        entryFileNames: '[name].js',
        chunkFileNames: '[name].js',
        assetFileNames: '[name][extname]'
      }
    }
  },
  resolve: {
    alias: {
      'vue': 'vue/dist/vue.esm-bundler.js',
      '@': path.resolve(__dirname, './')
    }
  }
})