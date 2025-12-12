FROM php:8.3-fpm-bookworm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libicu-dev \
    git \
    unzip \
    zip \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql opcache \
    && docker-php-ext-install intl zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY . .

RUN composer install --optimize-autoloader --no-dev

RUN npm install
RUN npm run build
RUN npm prune --omit=dev --ignore-scripts

RUN chown -R 1000:1000 storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

RUN rm -f /start.sh

ENTRYPOINT ["/bin/bash", "-c", "php artisan migrate --force && php-fpm"]