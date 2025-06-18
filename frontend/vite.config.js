// vite.config.js
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
  plugins: [vue()],
  root: './',
  base: './',
  build: {
    manifest: true,
    outDir: './../../resources/js/',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        form: path.resolve(__dirname, 'form.ts'),
        panel: path.resolve(__dirname, 'matherBoard.ts')
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
      '@': path.resolve(__dirname, './frontend')
    }
  }
})