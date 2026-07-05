FROM php:8.3-cli-alpine

# Dependencias del sistema necesarias para compilar las extensiones de PHP
RUN apk add --no-cache \
        postgresql-dev \
        libzip-dev \
        oniguruma-dev \
        $PHPIZE_DEPS

# Extensiones de PHP requeridas por la aplicación
RUN docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pgsql \
        zip \
        bcmath \
        mbstring

# Composer (imagen oficial) disponible dentro del contenedor de la app
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
