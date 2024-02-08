FROM php:8.3.2-apache

WORKDIR /var/www/html

RUN apt-get update

# Drivers PDO E PGSQL
RUN apt-get install -y libpq-dev \
  && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install pdo pdo_pgsql pgsql