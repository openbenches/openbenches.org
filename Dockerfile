FROM php:8.1-fpm

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

WORKDIR /var/www/symfony_docker

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony


COPY ./www .
RUN composer installer

# RUN ./composer.phar require symfony/twig-pack
# RUN ./composer.phar require symfony/twig-bundle
# RUN ./composer.phar require doctrine/dbal
# RUN ./composer.phar require symfony/cache
# RUN ./composer.phar require symfony/apache-pack
# RUN ./composer.phar require symfony/http-client
# RUN ./composer.phar require symfony/mime




# FROM ubuntu:latest
# ARG DEBIAN_FRONTEND=noninteractive
# RUN apt-get update 
# RUN apt-get install -y git php-cli php-fpm php-pecl-imagick php-mbstring php-mysqlnd mariadb-server httpd mod_ssl