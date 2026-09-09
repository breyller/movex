FROM php:8.4-apache

RUN apt-get update \
    && apt-get install -y \
        git \
        git-lfs \
        unzip \
    && docker-php-ext-install pdo_mysql \
    && a2enmod rewrite \
    && sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri \
    -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" \
    /etc/apache2/sites-available/*.conf

WORKDIR /var/www/html