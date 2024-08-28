# use PHP 8.2
FROM php:8.2-fpm

# Install common php extension dependencies
RUN apt-get update && apt-get install -y \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip

# Set the working directory
COPY . /var/www/html/

#đường dẫn này cần đúng vs copy
WORKDIR /var/www/html/

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/public \
    && chmod -R 755 /var/www/html/bootstrap 
# install composer
COPY --from=composer:2.6.5 /usr/bin/composer /usr/local/bin/composer

# copy composer.json to workdir & install dependencies
COPY composer.json ./
RUN composer install

# RUN php artisan config:cache
# RUN php artisan route:cache
# RUN php artisan view:cache
RUN php artisan key:generate


# Set the default command to run php-fpm
CMD ["php-fpm"]