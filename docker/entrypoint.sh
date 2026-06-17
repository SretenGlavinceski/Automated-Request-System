#!/bin/sh

set -e

cd /app

mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

touch database/database.sqlite

chmod -R 775 storage bootstrap/cache database

php artisan optimize:clear

if [ "$1" = "web" ]; then
    php artisan migrate --force

    exec php artisan serve \
        --host=0.0.0.0 \
        --port=8000
fi

if [ "$1" = "queue" ]; then
    exec php artisan queue:work \
        --sleep=3 \
        --tries=3 \
        --timeout=90
fi

exec "$@"
