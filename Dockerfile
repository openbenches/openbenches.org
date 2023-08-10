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
RUN cp /root/.symfony5/bin/symfony /usr/local/bin/symfony


COPY ./www .
#
# TODO : Add a CA Server?