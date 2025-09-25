# Stage 1: Build Stage
# This stage is used to build the frontend assets using Node.js.
FROM node:22 as build

WORKDIR /app # Set the working directory inside the container.

# Copy only package.json and package-lock.json for caching npm dependencies.
# This ensures that `npm install` is only re-run if these files change.
COPY package.json package-lock.json ./
RUN npm install # Install Node.js dependencies.

# Copy the rest of the application files to the container.
COPY . .

# Build assets using Laravel Mix or Vite.
# This step compiles frontend assets (CSS, JS, etc.) for production.
RUN npm run build

# Stage 2: Production Stage
# This stage is used to set up the production environment for the Laravel application.
FROM php:8.2-fpm

# Install system dependencies required for PHP and Laravel.
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by Laravel.
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer (PHP dependency manager) from the composer image.
# This pulls the Composer binary from the `composer:latest` image.
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www # Set the working directory for the Laravel application.

# Copy built assets from the build stage to the public directory.
COPY --from=build /app/public/build /var/www/public/build

# Copy the Laravel application files to the container.
COPY . .

# Install PHP dependencies using Composer.
# This installs Laravel's backend dependencies.
COPY composer.json composer.lock ./ # Copy Composer files for dependency installation.
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set permissions for Laravel's storage and cache directories.
# These directories need write permissions for the web server.
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Copy the entrypoint script to the container.
# This script is used to initialize the application when the container starts.
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh # Make the entrypoint script executable.

# Expose port 9000 for PHP-FPM.
EXPOSE 9000

# Use the entrypoint script to initialize the application.
ENTRYPOINT ["/entrypoint.sh"]

# Start the PHP-FPM server.
CMD ["php-fpm"]