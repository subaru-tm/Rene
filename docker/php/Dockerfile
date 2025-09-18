FROM php:8.3-fpm

COPY php.ini /usr/local/etc/php/

RUN apt-get update -y && \
    apt-get install -y --no-install-recommends \
    libmariadb-dev-compat \
    zlib1g-dev \
    libzip-dev \
    unzip \
    libjpeg-dev \
    libpng-dev && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

RUN pecl install redis \
  && docker-php-ext-enable redis

RUN docker-php-ext-configure gd --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd
 
WORKDIR /var/www