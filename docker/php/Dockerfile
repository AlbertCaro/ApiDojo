FROM php:8.0.9-fpm-alpine

COPY wait-for-it.sh /usr/bin/wait-for-it
COPY toggle-xdebug.sh /usr/bin/toggle-xdebug
COPY load-database.sh /usr/bin/load-database
RUN chmod +x /usr/bin/load-database

ARG XDEBUG_VERSION=3.0.4

RUN chmod +x /usr/bin/wait-for-it

RUN docker-php-ext-install pdo_mysql

RUN apk --update --no-cache add bash php8-dev autoconf g++ make

RUN pecl install xdebug-$XDEBUG_VERSION && docker-php-ext-enable xdebug

COPY --from=composer /usr/bin/composer /usr/bin/composer

CMD load-database ; toggle-xdebug; composer install; php-fpm
