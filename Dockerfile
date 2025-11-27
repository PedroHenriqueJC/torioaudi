FROM php:8.2-fpm

# Instalar extensões para Laravel + Postgres
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    zip \
    && docker-php-ext-install pdo pdo_pgsql

# Instalar OPcache
RUN docker-php-ext-install opcache

# Configurações recomendadas do OPcache
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
 && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
 && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
 && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
 && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
 && echo "opcache.save_comments=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Diretório de trabalho
WORKDIR /var/www/html

#########################################
# 1. Copiar composer.json primeiro
#########################################
COPY src/composer.json src/composer.lock ./

#########################################
# 2. Instalar dependências SEM scripts
#########################################
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

#########################################
# 3. Copiar o restante do código
#########################################
COPY src/ ./

#########################################
# 4. Agora sim rodar scripts do Laravel
#########################################
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# Permissões
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
