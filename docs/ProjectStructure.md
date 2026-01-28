# Project Structure

This document provides an overview of the key directories in the project.

- **`app/`**: This directory contains the core application code. It includes controllers, models, providers, and other application-level logic. In a Bagisto project, this directory is often used for customizations and overrides of the core packages.

- **`bootstrap/`**: This directory contains the application's bootstrapping scripts.

- **`config/`**: This directory contains the application's configuration files.

- **`database/`**: This directory contains the database migrations, seeders, and factories.

- **`docs/`**: This directory contains the project documentation.

- **`lang/`**: This directory contains the language files for localization.

- **`packages/`**: This is one of the most important directories in a Bagisto project. It contains the core modules of the platform, such as `Product`, `Sales`, `Customer`, and `Checkout`. It also contains custom modules developed specifically for this project, such as `LamGame/Banner`.

- **`public/`**: This is the document root for the application. It contains the `index.php` file, as well as the compiled assets (CSS, JavaScript, images).

- **`resources/`**: This directory contains the raw, un-compiled assets (e.g., Sass files, Vue components) and views.

- **`routes/`**: This directory contains the application's route definitions.

- **`storage/`**: This directory contains the application's cache, logs, and other generated files.

- **`vendor/`**: This directory contains the Composer dependencies.

- **`vite.config.js`**: This is the configuration file for Vite, the frontend build tool.
