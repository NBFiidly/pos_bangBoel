#!/bin/bash

# Railway build script for Laravel with PHP 8.2
# Skip problematic dev dependencies that require PHP 8.3+

composer install \
  --optimize-autoloader \
  --no-interaction \
  --no-dev \
  --ignore-platform-req=php \
  --ignore-platform-req=ext-intl \
  --ignore-platform-req=ext-zip

npm ci
npm run build

mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache
chmod -R a+rw storage

php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache
