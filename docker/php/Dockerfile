FROM php:8.4-fpm

# Install system dependencies and required PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mbstring exif bcmath pcntl \
    && docker-php-ext-enable pcntl \
    && rm -rf /var/lib/apt/lists/* && echo "[www]\nlisten = 0.0.0.0:9000\nlisten.owner = www-data\nlisten.group = www-data\nlisten.mode = 0660\n" > /usr/local/etc/php-fpm.d/zz-fpm.conf

RUN sed -i 's/;listen = .*/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/www.conf
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_HOME=/var/www/.composer
RUN mkdir -p /var/www/.composer && chown -R www-data:www-data /var/www/.composer

# Ensure www-data owns the application files
RUN chown -R www-data:www-data /var/www/html

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
