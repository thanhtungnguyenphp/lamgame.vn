# Frontend

This document provides an overview of the frontend setup.

## Build Tool

The project uses [Vite](https://vitejs.dev/) as the frontend build tool. The Vite configuration file is `vite.config.js`.

## Asset Bundling

The `vite.config.js` file defines three separate asset bundles:

1.  **App Assets:** These are the main application assets, located in `resources/css` and `resources/js`.
2.  **EmSaigon Theme (Shop):** This is the theme for the shop frontend, located in `resources/themes/emsaigon`.
3.  **Admin Package:** This is the theme for the admin panel, located in `packages/Webkul/Admin/src/Resources/assets`.

## Themes

The project uses a custom theme called `emsaigon` for the shop frontend. The theme files are located in `resources/themes/emsaigon`.

## JavaScript and CSS

The project uses the following JavaScript and CSS libraries:

- **Alpine.js:** A rugged, minimal framework for composing JavaScript behavior in your markup.
- **Quill:** A modern rich text editor.
- **Tom Select:** A vanilla JavaScript solution for creating dynamic and user-friendly select elements.
- **Bootstrap:** A popular CSS framework.
- **Sass:** A CSS preprocessor.

## Views

The application's views are located in the `resources/views` directory. The views for the shop theme are located in `resources/themes/emsaigon/views`.
