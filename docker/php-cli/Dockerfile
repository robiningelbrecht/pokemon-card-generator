FROM php:8.1-cli

RUN apt-get update && apt-get install -y zip unzip git curl
RUN docker-php-ext-install mysqli pdo pdo_mysql sockets pcntl

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer