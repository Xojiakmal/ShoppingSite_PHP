FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# COPY start.sh /start.sh
# RUN chmod -x /start.sh
# CMD ['/start.sh']



WORKDIR /var/www