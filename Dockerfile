# Use FrankenPHP image (same as Railway)
FROM dunglas/frankenphp:php8.2.30-bookworm

# Set working directory
WORKDIR /app

# Install system dependencies & dev dependencies for PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    curl \
    libicu-dev \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (intl and zip required by Filament)
RUN docker-php-ext-install intl zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts --ignore-platform-req=php --ignore-platform-req=ext-intl --ignore-platform-req=ext-zip

# Copy application files
COPY . .

# Run post-install scripts
RUN composer dump-autoload --optimize --no-interaction

# Install Node dependencies
RUN npm install

# Build assets
RUN npm run build

# Create necessary directories and set permissions
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache && \
    chmod -R 777 storage bootstrap/cache

# Cache configuration
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Expose port
EXPOSE 8000

# Start the application
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
