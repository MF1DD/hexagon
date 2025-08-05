FROM php:8.3-fpm

# User und Gruppe erstellen
RUN addgroup --gid 1000 appgroup \
    && adduser --uid 1000 --gid 1000 --disabled-password --gecos "" appuser

RUN apt-get update
RUN apt-get install -y  \
    git  \
    curl \
    vim \
    unzip  \
    libicu-dev

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY ./.docker/xdebug.ini "${PHP_INI_DIR}/conf.d"

WORKDIR /app

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .
