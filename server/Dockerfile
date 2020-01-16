FROM php:apache AS setup_server
ENV APACHE_DOCUMENT_ROOT /project/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

FROM composer AS composer_install
WORKDIR /project
COPY composer.* ./
COPY database ./database
COPY tests ./tests
RUN composer install

FROM setup_server AS server
WORKDIR /project
COPY --from=composer_install /project/vendor vendor
COPY .env.example .env
CMD chmod +x launch_script.sh && ./launch_script.sh && apache2-foreground

FROM composer_install AS composer_prod
WORKDIR /project
RUN composer install --optimize-autoloader --no-dev

FROM server as server_prod
WORKDIR /project
COPY . .
COPY --from=composer_install /project/vendor ./vendor
RUN sed -i 's/APP_DEBUG=true/APP_DEBUG=false/g' .env
RUN php artisan key:generate
CMD chmod +x launch_script.sh && ./launch_script.sh && apache2-foreground
