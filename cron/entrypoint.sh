#!/bin/sh

env >> /etc/environment

cp /app/.env.example /app/.env
cd /app && composer install
php /app/artisan key:generate
php /app/artisan migrate --force

# execute CMD
echo "$@"
exec "$@"
