#!/bin/sh

env >> /etc/environment

cd /app && composer install
php /app/artisan key:generate

# execute CMD
echo "$@"
exec "$@"
