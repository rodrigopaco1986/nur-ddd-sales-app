# Use official PHP 8.2 with FPM
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    librdkafka-dev \
    libffi-dev \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mbstring exif pcntl bcmath intl ffi \
    && pecl install rdkafka \
    && docker-php-ext-enable rdkafka

# Install Composer
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# Enable FFI (it’s built in, so just enable it via INI)
RUN echo "ffi.enable=1" > /usr/local/etc/php/conf.d/ffi.ini

# Copy startup script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose PHP-FPM port
#EXPOSE 9000

# Use our startup script
CMD ["/usr/local/bin/docker-entrypoint.sh"]