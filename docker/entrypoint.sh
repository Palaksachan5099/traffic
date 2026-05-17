#!/bin/sh
set -e

cd /var/www/html

# Ensure writable directories exist
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Public storage symlink for uploaded images
php artisan storage:link --force 2>/dev/null || true

# Warm caches for production (skip if APP_KEY is missing)
if [ -n "${APP_KEY}" ]; then
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

exec "$@"
