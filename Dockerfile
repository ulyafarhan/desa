# STEP 1: Gunakan base image PHP 8.3 FPM (FrankenPHP)
# FrankenPHP adalah server PHP modern dan cepat yang direkomendasikan Railway
FROM php:8.3-fpm-bookworm

# STEP 2: Instal Ekstensi PHP
# Install tool untuk ekstensi PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libicu-dev \
    git \
    unzip \
    zip \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Instal Ekstensi yang Dibutuhkan Filament (intl) dan OpenSpout (zip)
# Gunakan docker-php-ext-install
RUN docker-php-ext-install pdo pdo_mysql opcache \
    && docker-php-ext-install intl zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# STEP 3: Setup Environment
WORKDIR /app

# STEP 4: Copy Kode Aplikasi dan Install Dependencies
COPY . .

# Install Composer Dependencies
RUN composer install --optimize-autoloader --no-dev

# Install dan Build Frontend Assets
RUN npm install
RUN npm run build
RUN npm prune --omit=dev --ignore-scripts

# Atur izin direktori storage (Wajib untuk Laravel)
RUN chown -R 1000:1000 storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# STEP 5: Perintah Startup (Entrypoint)
# Script ini akan dijalankan saat container di-deploy
# Perintah ini akan berjalan setelah migrasi berhasil
RUN echo '#!/bin/bash' >> /start.sh \
    && echo 'set -e' >> /start.sh \
    && echo 'echo "Starting database migration..."' >> /start.sh \
    && echo 'php artisan migrate --force' >> /start.sh \
    && echo 'echo "Migration complete. Starting web server..."' >> /start.sh \
    && echo 'exec php-fpm' >> /start.sh \
    && chmod +x /start.sh