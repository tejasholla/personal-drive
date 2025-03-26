#!/bin/bash
set -e

# Check if APP_KEY needs to be generated
if [ -z "$(grep '^APP_KEY=..*$' .env)" ]; then
    echo "Generating new APP_KEY..."
    php artisan key:generate
fi

# Ensure permissions are set correctly
chown -R www-data:www-data storage bootstrap/cache database /var/www/html/personal-drive-storage-folder

# Cache configuration
php artisan config:cache

# Run the main command
exec "$@"