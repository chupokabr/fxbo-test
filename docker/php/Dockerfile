#FROM composer:latest AS composer

FROM php:7.4-fpm

RUN apt-get update && apt-get install -y libxml2-dev \
  curl \
  git \
  zip \
  unzip \
 && docker-php-ext-install pdo_mysql xml \
 && echo "pm = dynamic" >> /usr/local/etc/php/php.ini \
 && echo "php_admin_value[memory_limit] = 512M" >> /usr/local/etc/php-fpm.conf \
 && mkdir -p /app/var \
 && chmod -R 777 /app/var \
 && rm -rf /var/lib/apt/lists/*

# Composer
#COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY . /app

WORKDIR /app

#For dev
#RUN composer install --ignore-platform-reqs
#For prod
#RUN composer install --no-dev --prefer-dist --optimize-autoloader