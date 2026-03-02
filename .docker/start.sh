#!/bin/bash
set -e

echo "==> Starting Laravel application..."

cd /var/www/html

# Ensure storage directories exist
mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache

# Fix permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Clear any stale bootstrap cache (fixes Pail & other dev-only provider errors)
rm -f bootstrap/cache/packages.php bootstrap/cache/services.php bootstrap/cache/compiled.php
php artisan clear-compiled 2>/dev/null || true

# Wait for MySQL to actually accept TCP connections
echo "==> Waiting for MySQL to be ready..."
DB_HOST="${DB_HOST:-mysql}"
DB_PORT="${DB_PORT:-3306}"

until (echo > /dev/tcp/$DB_HOST/$DB_PORT) 2>/dev/null; do
    echo "    MySQL not ready yet, retrying in 2s..."
    sleep 2
done

sleep 2
echo "    MySQL is ready!"

# Cache config, routes, views
echo "==> Caching config, routes, views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "==> Running migrations..."
php artisan migrate --force --no-interaction

# Create supervisor directories
mkdir -p /var/log/supervisor /var/run/supervisor

echo "==> Starting services via supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf