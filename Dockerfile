FROM php:8.2-fpm

# Install extensions needed for Laravel + Postgres
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working dir
WORKDIR /var/www/html

# Copy Laravel files
COPY src/ ./

# Install dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Permissions for Laravel storage
RUN chown -R www-data:www-data storage bootstrap/cache

