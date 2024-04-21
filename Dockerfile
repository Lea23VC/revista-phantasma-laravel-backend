FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libicu-dev \ 
    libzip-dev \    
    libexif-dev    


# Clear cache
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo mbstring intl zip exif

WORKDIR /app
COPY . /app
RUN composer install

EXPOSE 9000
CMD ["php-fpm"]