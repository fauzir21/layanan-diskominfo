FROM php:8.4-fpm

# Install dependency sistem + ekstensi PHP yang dibutuhkan
# (pdo_sqlite buat local/testing, pdo_pgsql buat production di Railway)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libsqlite3-dev \
    libpq-dev \
    zip \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_sqlite pdo_pgsql zip \
    && rm -rf /var/lib/apt/lists/*

# Copy composer dari image resmi
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Install dependency PHP (production, tanpa dev packages)
RUN composer install --no-dev --optimize-autoloader

# Install dependency JS & build asset Vite/Vue
RUN npm install && npm run build

# Pastikan file database SQLite ada (tetap disiapin buat jaga-jaga/fallback,
# walau production sekarang pakai PostgreSQL dari Railway)
RUN mkdir -p database && touch database/database.sqlite

# PENTING: folder-folder ini di-exclude di .dockerignore (storage/framework,
# storage/logs), jadi harus dibikin ulang manual di sini, kalau nggak Laravel
# error "Please provide a valid cache path" karena folder cache Blade nggak ada
RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/testing \
    storage/logs \
    bootstrap/cache

# Set permission agar Laravel bisa nulis ke storage & cache
RUN chown -R www-data:www-data storage bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache database

EXPOSE 10000

# Jalankan migration dulu (biar tabel selalu ready), baru start server
# pakai $PORT dari Railway, bukan port hardcode
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}