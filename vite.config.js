import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import { resolve } from 'path'

export default defineConfig({
  plugins: [
    // App assets gốc của dự án (giữ nguyên)
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
      // dùng mặc định: hotFile => storage/framework/vite.hot
      // buildDirectory => build
    }),

    // Theme EmSaigon (SHOP)
    laravel({
      input: [
        resolve(__dirname, 'resources/themes/emsaigon/assets/js/app.js'),
        resolve(__dirname, 'resources/themes/emsaigon/assets/scss/app.scss'),
      ],
      refresh: true,
      // PHẢI khớp với config/themes.php
      hotFile: 'storage/framework/shop-emsaigon-vite.hot',
      buildDirectory: 'themes/shop/emsaigon/build',
    }),

    // Admin Package
    laravel({
      input: [
        resolve(__dirname, 'packages/Webkul/Admin/src/Resources/assets/css/app.css'),
        resolve(__dirname, 'packages/Webkul/Admin/src/Resources/assets/js/app.js'),
      ],
      refresh: true,
      hotFile: 'storage/framework/admin-vite.hot',
      buildDirectory: 'themes/admin/default/build',
    }),
  ],
})
