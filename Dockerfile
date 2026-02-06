FROM php:8.2-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install bcmath pdo_mysql mbstring zip exif pcntl

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=$PORT