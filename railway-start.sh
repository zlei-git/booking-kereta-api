#!/bin/sh
set -e

echo "Starting Railway Deployment Initialization..."

# Run database migrations with force flag in production
echo "Running migrations..."
php artisan migrate --force

# Seed initial database records (stations, trains, schedules, admin user) if database is fresh
echo "Checking seed..."
php artisan db:seed --force

# Optimize Laravel cache for production
echo "Caching config and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Laravel using PHP built-in server bound to Railway PORT
PORT=${PORT:-8080}
echo "Starting application on port $PORT..."
exec php artisan serve --host=0.0.0.0 --port=$PORT
