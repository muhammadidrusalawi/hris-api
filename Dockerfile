FROM php:8.2-fpm-alpine

# system deps
RUN apk add --no-cache \
    bash \
    git \
    unzip \
    curl \
    libpq-dev \
    oniguruma-dev \
    icu-dev

# php extensions
RUN docker-php-ext-install pdo pdo_pgsql intl

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# install deps first (cache friendly)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# copy source
COPY . .

# permissions
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
