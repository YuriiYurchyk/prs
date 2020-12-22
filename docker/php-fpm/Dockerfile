FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libmcrypt-dev \
    default-mysql-client \
    git \
    zip \
    cron \
    supervisor \
    && docker-php-ext-install pdo pdo_mysql \
    && pecl install mcrypt-1.0.3 \
    && docker-php-ext-enable mcrypt \
#    && docker-php-ext-install pcntl \
#    && docker-php-ext-install shmop

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet

ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /var/www

RUN (crontab -l -u root; echo "* * * * * /usr/local/bin/php -q -f /var/www/artisan schedule:run >> /var/log/cron.log 2>&1") | crontab

COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord"]