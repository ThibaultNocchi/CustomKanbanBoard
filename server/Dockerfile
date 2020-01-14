FROM php:apache

WORKDIR /project

ENV APACHE_DOCUMENT_ROOT /project/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite

COPY install_composer.sh ./
RUN apt update && apt install -y wget git zip
RUN chmod +x install_composer.sh
RUN ./install_composer.sh

COPY composer.* ./
COPY database ./database
COPY tests ./tests
RUN composer install

CMD cp .env.example .env && php artisan migrate && apache2-foreground
