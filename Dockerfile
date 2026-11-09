ARG PHP_VERSION=8.2
FROM php:${PHP_VERSION}-apache

# Install system dependencies
RUN apt-get update && apt-get install -y libonig-dev libcurl4-openssl-dev && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql curl mbstring

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory & install dependencies
WORKDIR /var/www/html
COPY composer.json ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy app files & setup Apache
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot ${APACHE_DOCUMENT_ROOT}|g' /etc/apache2/sites-available/000-default.conf
EXPOSE 80