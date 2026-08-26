FROM composer:2.10 AS composer

FROM php:8.3-apache-bookworm

RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
    git \
    libonig-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install -j"$(nproc)" \
    mbstring \
    pdo_mysql \
    zip \
    && a2enmod headers rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

COPY docker/php/php.ini "$PHP_INI_DIR/conf.d/gest-club.ini"
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html