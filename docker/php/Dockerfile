# syntax=docker/dockerfile:1.6
FROM php:8.3-fpm

# Install system deps
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libicu-dev libpq-dev libonig-dev libxml2-dev libfreetype6-dev \
    libjpeg62-turbo-dev libpng-dev libwebp-dev libxslt1-dev libssl-dev libcurl4-openssl-dev libgmp-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd intl pdo_mysql bcmath opcache pcntl zip exif calendar

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node.js (for Vite build & asset sync)
COPY --from=node:20-slim /usr/local/bin/node /usr/local/bin/node
COPY --from=node:20-slim /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && ln -s /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

# Opcache defaults tuned for dev (validation enabled)
RUN { \
  echo 'opcache.enable=1'; \
  echo 'opcache.enable_cli=1'; \
  echo 'opcache.validate_timestamps=1'; \
  echo 'opcache.max_accelerated_files=10000'; \
  echo 'opcache.memory_consumption=192'; \
  echo 'opcache.interned_strings_buffer=16'; \
} > /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/html

# Create non-root user (optional)
RUN useradd -u 1000 -ms /bin/bash laravel
USER laravel
