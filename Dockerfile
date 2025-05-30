FROM node:20 AS frontend

WORKDIR /app

COPY package*.json ./
 
RUN npm install -g pnpm && pnpm install 

COPY . /app

RUN npm run build

FROM php:8.2-fpm AS base

WORKDIR /app

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

COPY . /app

COPY --from=frontend /app/public /app/public

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --no-dev --optimize-autoloader

RUN bash ./scripts/init_env.sh

ENTRYPOINT ["sh", "-c", "php artisan route:cache \
    && php artisan view:cache \
    && php artisan event:cache \
    && php artisan optimize \
    && php artisan migrate \
    && php artisan serve --host=0.0.0.0 --port=${APP_PORT:-9000}"]
