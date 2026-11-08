ARG PHP_VERSION=8.2

FROM php:${PHP_VERSION}-apache

# Install PDO MySQL and other required extensions (curl, mbstring, etc.)
RUN docker-php-ext-install pdo pdo_mysql curl mbstring

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# --- STRIPE & COMPOSER SETUP ---
# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (for Docker caching)
COPY composer.json ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction
# --------------------------------

# Allow .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copy app files
COPY . /var/www/html/

# Fix permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Point Apache document root to public/
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot ${APACHE_DOCUMENT_ROOT}|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|<Directory /var/www/html>|<Directory ${APACHE_DOCUMENT_ROOT}>|g' /etc/apache2/apache2.conf

# Payagan ang OPTIONS method sa Apache (CORS)
RUN echo '<IfModule mod_headers.c>\n    Header set Access-Control-Allow-Origin "*"\n</IfModule>' > /etc/apache2/conf-available/cors.conf \
    && a2enconf cors

# Payagan ang OPTIONS sa Apache Limit
RUN echo '<Directory /var/www/html/public>\n    <LimitExcept GET POST OPTIONS>\n        Require all denied\n    </LimitExcept>\n</Directory>' >> /etc/apache2/apache2.conf

EXPOSE 80