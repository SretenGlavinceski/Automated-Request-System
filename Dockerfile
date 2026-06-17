FROM composer:2 AS composer

WORKDIR /app

COPY . .

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader


FROM node:22-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js ./

RUN npm run build


FROM php:8.4-cli

WORKDIR /app

RUN apt-get update \
    && apt-get install -y \
        git \
        unzip \
        libsqlite3-dev \
        libzip-dev \
    && docker-php-ext-install \
        pdo_sqlite \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY . .

COPY --from=composer /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

COPY docker/entrypoint.sh /usr/local/bin/requesthub-entrypoint

RUN chmod +x /usr/local/bin/requesthub-entrypoint \
    && mkdir -p \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache database

EXPOSE 8000

ENTRYPOINT ["requesthub-entrypoint"]

CMD ["web"]
