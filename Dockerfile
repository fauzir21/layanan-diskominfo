FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    zip \
    nodejs \
    npm \
    nginx \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install
RUN npm run build

#RUN cp .env.example .env || true

#RUN php artisan key:generate --force || true

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD php artisan serve --host=0.0.0.0 --port=80