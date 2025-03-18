FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip curl sudo sqlite3 libsqlite3-dev \
    libjpeg-dev libpng-dev libwebp-dev libfreetype6-dev zlib1g-dev libzip-dev nginx \
    && docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
    && docker-php-ext-install pdo pdo_sqlite gd zip exif

# Install Node.js & npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Copy app files
COPY . .

# Set up environment file
RUN cp .env.example .env
RUN sed -i 's/^APP_ENV=.*/APP_ENV=development/' .env

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist

# Install npm dependencies
RUN npm install && npm run build

# Generate application key
RUN php artisan key:generate

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache database && \
    chmod -R 770 storage bootstrap/cache database

# Clear & cache config
RUN php artisan config:clear && php artisan config:cache

# Copy Nginx config
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Expose ports
EXPOSE 80

# Start Nginx & PHP-FPM together
CMD ["sh", "-c", "service nginx start && php-fpm"]