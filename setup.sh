#!/bin/bash

# Exit on error
set -e

# Default values
WEB_USER="www-data"
WEB_GROUP="www-data"

# Function to ask for user input
ask_for_value() {
    local prompt="$1"
    local default_value="$2"
    local user_value

    read -p "$prompt [$default_value]: " user_value
    echo "${user_value:-$default_value}"
}

# echo "You can change these values if needed."
# Ask the user to confirm or change the web server user and group
WEB_USER=$(ask_for_value "Enter the web server user" "$WEB_USER")
WEB_GROUP=$(ask_for_value "Enter the web server group" "$WEB_GROUP")

echo "Setting up database..."
mkdir -p database
if [ ! -f database/database.sqlite ]; then
    echo "Creating SQLite database file..."
    touch database/database.sqlite
fi

# Set proper permissions for the database file
chmod 666 database/database.sqlite

echo "Installing composer dependencies..."
composer install --no-interaction --prefer-dist

echo "Installing npm dependencies..."
npm install && npm run build

echo "Setting up environment file..."
cp .env.example .env

echo "Generating application key..."
php artisan key:generate

echo "Running migrations..."
#php artisan migrate

echo "Setting permissions for storage and bootstrap/cache..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "Clearing and caching config..."
php artisan config:clear
php artisan cache:clear
php artisan config:cache

echo "Setup complete!"

