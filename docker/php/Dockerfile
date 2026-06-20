FROM php:8.4-fpm-alpine

RUN apk update && apk add --no-cache \
    bash \
    git \
    icu-dev \
    libzip-dev \
    zip \
    unzip \
    g++ \
    make \
    su-exec

RUN docker-php-ext-install pdo_mysql intl zip opcache

COPY docker/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/99-opcache-custom.ini
COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN addgroup -g 1000 symfony && adduser -u 1000 -G symfony -D symfony

EXPOSE 9000
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]