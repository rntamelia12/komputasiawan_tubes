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
    zlib1g-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Set direktori kerja
WORKDIR /var/www

# Salin file proyek ke dalam image
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === 'your_expected_hash_here') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); exit(1); } echo PHP_EOL;" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php

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
