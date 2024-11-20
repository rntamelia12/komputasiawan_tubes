# Menggunakan image PHP 8.2 dengan FPM
FROM php:8.2-fpm

# Install dependensi sistem
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql exif \
    && docker-php-ext-enable exif \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set direktori kerja
WORKDIR /var/www

# Salin file proyek ke dalam image
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependensi Composer tanpa menjalankan script
RUN composer install --no-scripts --no-autoloader

# Hak akses untuk direktori tertentu
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Install dependensi Node.js dan build aset frontend
RUN npm install && npm run build

# Expose port PHP-FPM
EXPOSE 9000

# Jalankan PHP-FPM
CMD ["php-fpm"]
