#!/bin/bash
echo "=== DEPLOY SCRIPT ==="

# Buat .env dari DATABASE_URL jika DB_HOST kosong
if [ -z "$DB_HOST" ] && [ -n "$DATABASE_URL" ]; then
    echo "DB vars empty, parsing from DATABASE_URL"
    echo "DATABASE_URL=$DATABASE_URL" > /app/.env
    echo "DB_CONNECTION=mysql" >> /app/.env
    echo "APP_KEY=$APP_KEY" >> /app/.env
    echo "APP_ENV=$APP_ENV" >> /app/.env
    echo "APP_DEBUG=$APP_DEBUG" >> /app/.env
fi

php artisan config:clear
php artisan config:cache
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
vendor/bin/heroku-php-apache2 public/