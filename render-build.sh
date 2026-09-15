#!/usr/bin/env bash
# Exit on error
set -o errexit

echo "Running composer install..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "Installing node dependencies & building Vite assets..."
npm install --ignore-scripts
npm run build

echo "Clearing & caching configs..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
