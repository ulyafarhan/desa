# STEP 1: Gunakan base image PHP 8.3 FPM (FrankenPHP)
# FrankenPHP adalah server PHP modern dan cepat yang direkomendasikan Railway
FROM dunglas/frankenphp:php8.3-fpm-bookworm

# STEP 2: Instal Ekstensi PHP
# Instal ekstensi yang dibutuhkan Filament (intl) dan OpenSpout (zip)
RUN install-php-extensions \
    intl \
    zip \
    pdo_mysql \
    ctype curl dom fileinfo hash mbstring openssl pcre pdo session tokenizer xml

# STEP 3: Setup Environment
# Tentukan direktori kerja dan instal git, unzip, zip
WORKDIR /app
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

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
# Buat script untuk Migrasi dan Start
# Script ini akan dijalankan saat container di-deploy
RUN echo '#!/bin/bash \n\
# Jalankan migrasi database \n\
php artisan migrate --force \n\
# Jalankan supervisor untuk worker (Opsional, nanti dibahas) \n\
# php artisan queue:work --daemon --sleep=3 --tries=3 \n\
# Start the FrankenPHP server \n\
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf \n\
' > /start.sh \
&& chmod +x /start.sh

# Start Application
CMD ["/start.sh"]