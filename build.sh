#!/usr/bin/env bash
set -o errexit

echo "Installing PHP dependencies..."
composer install --no-dev --no-interaction --prefer-dist

echo "Installing Node dependencies..."
npm install

echo "Building assets..."
npm run build

echo "Caching configuration..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Caching views..."
php artisan view:cache

echo "Build completed!"
