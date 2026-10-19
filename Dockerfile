FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libonig-dev libicu-dev libzip-dev \
       libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring intl zip gd \
    && a2enmod rewrite headers \
    && sed -ri 's!DocumentRoot /var/www/html!DocumentRoot /var/www/html/public!' /etc/apache2/sites-available/000-default.conf \
    && sed -ri '/<Directory \/var\/www\/>/,/<\/Directory>/s!AllowOverride None!AllowOverride All!' /etc/apache2/apache2.conf \
    && sed -ri 's!Options Indexes FollowSymLinks!Options FollowSymLinks!' /etc/apache2/apache2.conf \
    && sed -ri 's!Listen 80!Listen 10000!' /etc/apache2/ports.conf \
    && sed -ri 's!\*:80!\*:10000!' /etc/apache2/sites-available/000-default.conf \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .

RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && composer install --no-dev --no-interaction --no-progress --optimize-autoloader \
    && chown -R www-data:www-data storage bootstrap/cache

COPY deploy/start.sh /usr/local/bin/start-tienda
RUN chmod +x /usr/local/bin/start-tienda

EXPOSE 10000
CMD ["/usr/local/bin/start-tienda"]
