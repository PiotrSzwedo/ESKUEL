FROM ubuntu:24.04

ARG WWWGROUP
ARG WWWUSER

WORKDIR /var/www/html

# install php
RUN apt-get update && \
    apt-get install -y \
        apache2 \
        composer \
        libapache2-mod-php8.3 \
        php8.3 \
        php8.3-cli \
        php8.3-fpm \
        php8.3-common \
        php8.3-mbstring \
        php8.3-xml \
        php8.3-curl \
        php8.3-mysql \
        php8.3-bcmath \
        php8.3-zip \
        php8.3-intl \
        php8.3-readline \
        php8.3-opcache \
        php8.3-soap \
        php8.3-gd \
        php8.3-dev \
        php-pear \
        libzip-dev \
        unzip \
        curl \
        git \
        supervisor && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# install nodejs
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm@latest

RUN userdel -r ubuntu

RUN groupadd --force -g $WWWGROUP sail && \
    useradd -ms /bin/bash -g $WWWGROUP -u $WWWUSER sail

RUN chown -R sail:sail /var/www/html

RUN sed -i 's/^User .*/User sail/' /etc/apache2/apache2.conf && \
    sed -i 's/^Group .*/Group sail/' /etc/apache2/apache2.conf

COPY php.ini /etc/php/8.3/cli/conf.d/99-sail.ini
COPY 000-default.conf /etc/apache2/sites-available/
RUN a2enmod php8.3
RUN a2enmod rewrite

EXPOSE 80/tcp