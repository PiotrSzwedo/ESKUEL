// vite.config.js
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
  plugins: [vue()],
  root: 'frontend',
  base: './',
  build: {
    outDir: './../../resources',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        form: path.resolve(__dirname, 'form.ts'),
        app: path.resolve(__dirname, "matherBoard.ts")
      }
    }
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './frontend')
    }
  }
})
