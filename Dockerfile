# Use Apache with PHP (mod_php)
FROM php:8.3-apache

# Install system deps + PHP extensions (pgsql, zip, etc.)
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev \
 && docker-php-ext-install pdo pdo_pgsql zip \
 && a2enmod rewrite \
 && rm -rf /var/lib/apt/lists/*

# Set working dir
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copy composer files first for better Docker cache
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Copy the rest of the app
COPY . .

# Ensure Laravel writable dirs
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R ug+rwx storage bootstrap/cache

# Use our vhost that serves /public and allows .htaccess
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Laravel public symlink (ok if exists already)
RUN php -r "if(!is_link('public/storage')) { @mkdir('public',0775,true); @symlink('/var/www/html/storage/app/public','/var/www/html/public/storage'); }"

# Apache listens on 80; Render will detect it automatically
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
