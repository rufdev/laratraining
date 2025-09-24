# Stage 1: Build Stage
FROM node:20 as build

WORKDIR /app

# Copy only package.json and package-lock.json for caching npm dependencies
COPY package.json package-lock.json ./
RUN npm install

# Copy the rest of the application files
COPY . .

# Build assets using Laravel Mix or Vite
RUN npm run build

# Stage 2: Composer Stage
FROM composer:2 as composer

# Stage 3: Production Stage
FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy built assets from the build stage
COPY --from=build /app/public/build /var/www/public/build

# Copy PHP application files
COPY . .

# Install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Ensure Laravel directories exist and set permissions
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Copy the entrypoint script
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Use the entrypoint script
ENTRYPOINT ["/entrypoint.sh"]

# Start PHP-FPM
CMD ["php-fpm"]