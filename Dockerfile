# 1. Upgrade to PHP 8.4 as required by your Composer dependencies
FROM php:8.4-apache

# 2. Install system dependencies, PHP extensions for PostgreSQL, and dos2unix
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    dos2unix \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# 3. Change Apache internal port from 80 to 8000
RUN sed -i 's/Listen 80/Listen 8000/g' /etc/apache2/ports.conf
RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost \*:8000>/g' /etc/apache2/sites-available/*.conf

# 4. Enable Apache mod_rewrite
RUN a2enmod rewrite

# 5. Set the working directory
WORKDIR /var/www/html

# 6. Change Apache DocumentRoot to Laravel's public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 7. Configure PHP temporary directory and force it in php.ini
RUN mkdir -p /tmp && chmod 777 /tmp
RUN echo "sys_temp_dir = /tmp" >> /usr/local/etc/php/php.ini
RUN echo "upload_tmp_dir = /tmp" >> /usr/local/etc/php/php.ini

# 8. Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 9. Copy project files to the container
COPY . .

# 10. Set correct permissions for Laravel storage and cache folders
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 11. Setup the entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN dos2unix /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

EXPOSE 8000

CMD ["apache2-foreground"]
