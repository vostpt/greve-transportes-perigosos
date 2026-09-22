FROM php:7.4-fpm-alpine

RUN apk add --no-cache \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        libzip-dev \
        icu-dev \
        oniguruma-dev \
        libxml2-dev \
        git \
        unzip \
        bash \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mbstring \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
        xml \
    && rm -rf /var/cache/apk/*

COPY --from=composer:1 /usr/bin/composer /usr/bin/composer

COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-app.ini

WORKDIR /var/www/html

RUN addgroup -g 1000 app \
 && adduser -u 1000 -G app -s /bin/sh -D app \
 && chown -R app:app /var/www/html

USER app

EXPOSE 9000

CMD ["php-fpm"]
