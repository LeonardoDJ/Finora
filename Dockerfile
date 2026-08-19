FROM node:22-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci

COPY resources ./resources
COPY vite.config.js ./
COPY tailwind.config.js ./
COPY postcss.config.js ./

RUN npm run build


FROM php:8.3-cli

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo_pgsql zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

COPY --from=frontend /app/public/build ./public/build

RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]