#!/bin/bash
# Run this locally on your Windows/Mac to update composer.lock for PHP 8.2

# Make sure you have PHP 8.2+ installed locally
php --version

# Update composer.lock with no-dev and ignore platform requirements
composer update --no-dev \
  --ignore-platform-req=php \
  --ignore-platform-req=ext-intl \
  --ignore-platform-req=ext-zip

# Then commit and push
git add composer.lock
git commit -m "Update composer.lock for Railway PHP 8.2 deployment"
git push
