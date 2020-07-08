FROM php:7.2-apache

RUN echo "Building API container ......."
RUN apt-get update && apt-get install -yqq  --no-install-recommends \
    git zip unzip curl wget vim libzip-dev \
    build-essential software-properties-common \
    libmcrypt-dev libpq-dev libpng-dev libxml2-dev \
    default-mysql-client openssl libssl-dev libcurl4-openssl-dev \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev

RUN echo "Installing PHP extensions .................."
RUN pecl install mcrypt-1.0.2
RUN docker-php-ext-install -j$(nproc) gd zip bcmath pdo pdo_mysql pdo_pgsql mbstring \
    && docker-php-ext-enable mcrypt pdo_mysql pdo gd \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN apt-get clean && apt-get --purge autoremove -y

RUN echo "Deploying the API .........."
WORKDIR /var/www
COPY ./bizwhois-api /var/www

RUN echo "Configure Apache ..................."
RUN a2enmod rewrite headers ssl
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY apache/php.ini $PHP_INI_DIR/conf.d/custom.ini
#COPY apache/php.ini /etc/php7/conf.d/custom.ini
COPY apache/vhost-apache.conf /etc/apache2/sites-available/000-default.conf
RUN service apache2 restart

RUN echo "Installing composer ................."
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# cannot go further without .env file
RUN #!/bin/bash \
    && if [ ! -x "/var/www/.env"; ] then \
    &&   echo "Please ensure .env exists!" >&2 \
    &&   exit() \
    && fi

RUN echo "Wrapt it up!"
RUN composer dump-autoload \
    && composer install --ignore-platform-reqs --no-scripts --no-interaction -o

# a user with the same UID/GID as host user so as to not mess the host file's ownership
ARG uid
RUN useradd -G www-data,root -u $uid -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer \
    && chown -R devuser:devuser /home/devuser

CMD ["apache2-foreground"]

#RUN php artisan migrate:fresh
#RUN php artisan db:seed

RUN echo "API Ready!"
