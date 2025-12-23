FROM ubuntu:24.04

RUN apt-get update && apt-get install -y \
    software-properties-common \
    ca-certificates \
    curl \
    git \
    mc \
    unzip \
    apache2 \
    php \
    php-fpm \
    php-cli \
    php-common \
    php-mysql \
    php-xml \
    php-curl \
    php-zip \
    php-intl \
    php-iconv \
    php-mbstring \
    php-gd \
    php-bcmath \
    php-readline \
    php-opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite proxy_fcgi setenvif
RUN a2enconf php8.3-fpm

COPY docker/apache.local.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php-fpm.conf /etc/php/8.3/fpm/pool.d/www.conf
# COPY docker/xdebug.ini /etc/php/8.3/mods-available/xdebug.ini

EXPOSE 80

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
