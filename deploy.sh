#!/bin/bash
echo "=== ALL ENV VARS ==="
printenv | sort
echo "=== END ALL ENV VARS ==="

php artisan config:clear
php artisan config:cache
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
vendor/bin/heroku-php-apache2 public/