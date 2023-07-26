FROM php:8.2-fpm-bookworm

RUN apt update \
    && apt-get install -y git libmagickwand-dev zip
RUN pecl install imagick
RUN docker-php-ext-enable imagick
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable pdo_mysql

WORKDIR /var/www/symfony_docker

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony


COPY ./www .

# RUN composer install
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