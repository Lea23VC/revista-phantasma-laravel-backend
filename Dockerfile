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


# Install Node.js version 20 and Yarn
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
    echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list && \
    apt-get update && apt-get install -y yarn

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*


# Clear cache
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo mbstring intl zip exif

WORKDIR /app
COPY . /app
RUN composer install

# Install Node.js dependencies with Yarn and build assets
RUN yarn install && yarn run build

# Fix permissions for storage and bootstrap cache
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]