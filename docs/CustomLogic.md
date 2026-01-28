# Custom Logic, Routes, and Controllers

This document provides an overview of the custom logic, routes, and controllers in the project.

## Routes

The main web routes are defined in the `routes/web.php` file. This file includes routes for:

- Homepage
- Checkout
- Source Game pages
- Blog pages
- Seller registration and dashboard
- Admin seller and product management
- Company logos
- Job pages
- Forum
- User profiles
- AI thumbnail generation
- Product file serving
- Customer authentication

## Controllers

The custom controllers are located in the `app/Http/Controllers` directory. These controllers handle the application's HTTP requests.

### Key Controllers

- **`HomeController`**: Handles the homepage.
- **`LamGamePageController`**: Handles the Source Game, Blog, and Job pages.
- **`ForumController`**: Handles the forum.
- **`SellerController`**: Handles the seller registration and dashboard.
- **`SellerProductController`**: Handles the seller's products.
- **`Admin\AdminSellerController`**: Handles the admin's seller management.
- **`Admin\AdminProductController`**: Handles the admin's product management.
- **`UserProfileController`**: Handles the user profiles.
- **`Auth\CustomerAuthController`**: Handles customer authentication.

## Custom Logic

The `app` directory contains the core application logic. This includes:

- **`DataGrids`**: Contains the DataGrids for the admin panel.
- **`Exports`**: Contains the data exports.
- **`Helpers`**: Contains helper functions.
- **`Imports`**: Contains the data imports.
- **`Listeners`**: Contains event listeners.
- **`Mail`**: Contains the mailable classes.
- **`Models`**: Contains the application's Eloquent models.
- **`Providers`**: Contains the application's service providers.
- **`Repositories`**: Contains the application's repositories.
- **`Services`**: Contains the application's services.
- **`Traits`**: Contains the application's traits.
