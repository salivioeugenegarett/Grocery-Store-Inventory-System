# Use official PHP 8 with Apache
FROM php:8.2-apache

# Enable PDO and SQLite extensions
RUN docker-php-ext-install pdo pdo_sqlite

# Copy all files to Apache's web root
COPY . /var/www/html/

# Give proper permissions to db folder
RUN chown -R www-data:www-data /var/www/html/db

# Enable Apache rewrite module (optional, if needed)
RUN a2enmod rewrite

# Expose port 80 for the web server
EXPOSE 80
