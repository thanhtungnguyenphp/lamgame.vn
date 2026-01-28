# Installation

This document provides instructions on how to set up the project for development and production.

## Prerequisites

- PHP >= 8.2
- Node.js >= 18.x
- Composer
- MySQL

## Development Setup

1.  **Clone the repository:**

    ```bash
    git clone https://github.com/lamgame-vn/lamgame.vn.git
    cd lamgame.vn
    ```

2.  **Install PHP dependencies:**

    ```bash
    composer install
    ```

3.  **Install JavaScript dependencies:**

    ```bash
    npm install
    ```

4.  **Set up the environment file:**

    - Copy the `.env.example` file to `.env`:
      ```bash
      cp .env.example .env
      ```
    - Generate the application key:
      ```bash
      php artisan key:generate
      ```
    - Configure your database credentials and other environment variables in the `.env` file.

5.  **Run database migrations and seeders:**

    ```bash
    php artisan migrate
    php artisan db:seed
    ```
    
6.  **Create a symbolic link to the storage directory:**

    ```bash
    php artisan storage:link
    ```

7.  **Build frontend assets:**

    ```bash
    npm run dev
    ```

    This command will start the Vite development server.

8.  **Start the development server:**

    ```bash
    php artisan serve
    ```

## Production Setup

1.  **Install dependencies:**

    ```bash
    composer install --optimize-autoloader --no-dev
    npm install --production
    ```

2.  **Build frontend assets:**

    ```bash
    npm run build
    ```

3.  **Optimize Laravel:**

    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```
    
4. **Run migrations**
    ```bash
    php artisan migrate --force
    ```

5.  **Configure your web server (e.g., Nginx, Apache) to point to the `public` directory.**
