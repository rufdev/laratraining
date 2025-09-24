# Dockerfile

FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip nodejs npm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


# Copy only composer files first to leverage Docker caching
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copy only package.json and package-lock.json for npm dependencies
COPY package.json package-lock.json ./

RUN npm install

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Build Vue assets
RUN npm run build
# Set file permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port (if needed)
EXPOSE 9000

CMD ["php-fpm"]