FROM php:8.1-cli

RUN apt-get update && apt-get install -y zip unzip git curl zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev ffmpeg
RUN docker-php-ext-configure gd --with-freetype
RUN docker-php-ext-install fileinfo gd

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer