# Use PHP 8.1 FPM as base image
FROM php:8.1-fpm

# Install required PHP extensions and other dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy the existing application files into the container
COPY . .

# Install PHP dependencies using Composer
RUN composer install --optimize-autoloader --no-dev --no-interaction

# Install Node.js and npm dependencies for Vite
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install npm dependencies
RUN npm install

# Build assets using Vite
RUN npm run build

# Set permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start the PHP-FPM process
CMD ["php-fpm"]
