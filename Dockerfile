FROM php:8.3.9-fpm


# Встановлення Imagick
RUN apt-get update && apt-get install -y \
    libmagickwand-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# Встановлення системних залежностей
RUN apt-get update && apt-get install -y \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Встановлення Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Встановлення робочої директорії
WORKDIR /var/www/api.abz.igvend.com/laravel

# Копіювання файлів проекту
COPY . .

# Встановлення залежностей
RUN composer install --no-interaction --prefer-dist

# Встановлення прав доступу
RUN chown -R www-data:www-data /var/www/api.abz.igvend.com/laravel/storage /var/www/api.abz.igvend.com/laravel/bootstrap/cache