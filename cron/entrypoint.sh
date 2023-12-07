#!/bin/sh

env >> /etc/environment

composer install
php artisan key:generate

# execute CMD
echo "$@"
exec "$@"
