#!/bin/bash

# Wait for the database to be ready
echo "Waiting for the database to be ready..."
/wait-for-it.sh $DB_HOST $DB_PORT

composer install --optimize-autoloader --no-dev

# make key
echo "making key"
php artisan key:generate

# Run migrations
echo "Running migrations and seeds"
php artisan migrate --force
php artisan db:seed

# Start Apache
echo "Starting Apache..."
apache2-foreground
