FROM php:7-fpm

EXPOSE 80

EXPOSE 1000-4000

ENV TZ=Asia/Hong_Kong
ENV LANG=C.UTF-8

RUN apt-get update && apt-get install -y libmcrypt-dev mysql-client \
    && docker-php-ext-install mcrypt pdo_mysql

WORKDIR /var/www