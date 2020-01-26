FROM php:7.4-fpm

RUN apt-get update && \
    apt-get install -y libzip-dev --no-install-recommends

RUN docker-php-ext-install \
    pdo_mysql \
    zip

WORKDIR /srv/book
