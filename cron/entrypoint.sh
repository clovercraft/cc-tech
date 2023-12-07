#!/bin/sh

env >> /etc/environment

cp /app/.env.example /app/.env
cd /app && composer install
php /app/artisan key:generate

# execute CMD
echo "$@"
exec "$@"
