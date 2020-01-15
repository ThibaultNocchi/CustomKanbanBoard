FROM composer AS install

WORKDIR /project
COPY composer.* ./
COPY database ./database
COPY tests ./tests
RUN composer install

#
FROM php:apache AS server

WORKDIR /project

ENV APACHE_DOCUMENT_ROOT /project/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite

COPY .env.example ./.env
COPY --from=install /project/vendor ./vendor

CMD php artisan migrate && apache2-foreground

#
FROM install AS install_prod

WORKDIR /project
RUN composer install --optimize-autoloader --no-dev

#
FROM server AS server_prod

WORKDIR /project

COPY . .
RUN sed -i 's/APP_DEBUG=true/APP_DEBUG=false/g' .env
COPY --from=install_prod /project/vendor ./vendor
RUN php artisan key:generate

CMD php artisan migrate && apache2-foreground
