FROM ubuntu:24.04

ARG WWWGROUP
ARG WWWUSER

WORKDIR /var/www/html

# Zainstaluj wymagane pakiety
RUN apt-get update && \
    apt-get install -y \
        nginx \
        php8.3-fpm \
        php8.3-cli \
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
        supervisor \
        composer && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalacja Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm@latest

# Usuń domyślnego użytkownika
RUN userdel -r ubuntu || true

# Tworzenie użytkownika i grupy
RUN groupadd --force -g $WWWGROUP sail && \
    useradd -ms /bin/bash -g $WWWGROUP -u $WWWUSER sail

# Nadanie uprawnień
RUN chown -R sail:sail /var/www/html

# Konfiguracja PHP-FPM dla użytkownika sail
RUN sed -i "s/^user = .*/user = sail/" /etc/php/8.3/fpm/pool.d/www.conf && \
    sed -i "s/^group = .*/group = sail/" /etc/php/8.3/fpm/pool.d/www.conf && \
    sed -i "s/^listen.owner = .*/listen.owner = sail/" /etc/php/8.3/fpm/pool.d/www.conf && \
    sed -i "s/^listen.group = .*/listen.group = sail/" /etc/php/8.3/fpm/pool.d/www.conf

# Skopiuj konfiguracje
COPY php.ini /etc/php/8.3/cli/conf.d/99-sail.ini
COPY nginx.conf /etc/nginx/nginx.conf
COPY default /etc/nginx/sites-available/default


COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord"]