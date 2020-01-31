FROM php:7.4-fpm

RUN apt-get update && \
    apt-get install -y libzip-dev libicu-dev --no-install-recommends

RUN docker-php-ext-install \
    pdo_mysql \
    zip \
    intl

WORKDIR /srv/book
