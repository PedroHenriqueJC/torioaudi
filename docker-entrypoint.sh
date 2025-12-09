#!/bin/bash
set -e

cd /var/www/html

echo "Waiting for Postgres at $DB_HOST:$DB_PORT..."

# wait until port is open
until nc -z "$DB_HOST" "$DB_PORT"; do
  echo "Postgres is unavailable - sleeping"
  sleep 2
done

echo "Postgres is up!"

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
