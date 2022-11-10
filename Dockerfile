FROM php:8.1.0-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash

ARG INSTALL_XDEBUG=false
RUN if [ ${INSTALL_XDEBUG} = true ]; \
    then \
      pecl install xdebug && docker-php-ext-enable xdebug; \
    fi;

COPY ./xdebug.ini "${PHP_INI_DIR}/conf.d"
#COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/html
