FROM php:7.4-fpm
COPY ./conf/php.ini /etc/php.ini
RUN docker-php-source extract \
	&& apt update && apt install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd bcmath opcache sockets sysvmsg shmop session pdo_mysql \
    && pecl install redis-5.1.1 \
    && pecl install xdebug-2.8.1 \
    && pecl install apcu \
    && pecl install mongodb \
    && pecl install xdebug \
    && docker-php-ext-enable redis xdebug apcu mongodb
WORKDIR /tmp
RUN apt update && apt install unzip -y && apt install wget -y\ 
    && wget https://github.com/swoole/swoole-src/archive/v4.5.4.zip \
    && unzip v4.5.4.zip && mv swoole-src-4.5.4 /usr/src/php/ext/swoole && docker-php-ext-install swoole
VOLUME ["/var/www"]