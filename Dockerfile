FROM php:8.2-fpm

# Instalar extensões necessárias para Laravel + Postgres
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    zip \
    && docker-php-ext-install pdo pdo_pgsql

# Enable OPcache for performance
RUN docker-php-ext-install opcache

# Recommended OPcache settings
COPY ./docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos do projeto (mas ignorar vendor inicialmente)
COPY src/composer.json src/composer.lock ./

# Instalar dependências antes de copiar o resto (melhora cache do Docker)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Agora copiar o resto do projeto
COPY src/ ./

# Dar permissão para storage e cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache