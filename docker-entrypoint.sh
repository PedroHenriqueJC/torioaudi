#!/bin/bash
set -e

cd /var/www/html

# Criar .env se não existir
if [ ! -f ".env" ]; then
    echo "Creating .env from .env.example"
    cp .env.example .env
fi

# Gerar chave
php artisan key:generate --force

# Rodar migrations
php artisan migrate --force --seed

echo "Starting Laravel..."

exec "$@"
