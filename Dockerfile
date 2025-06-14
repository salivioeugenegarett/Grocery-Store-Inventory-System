# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Enable Apache rewrite module (optional but useful)
RUN a2enmod rewrite

# Install SQLite extension
RUN docker-php-ext-install pdo pdo_sqlite

# Copy all files into web server root
COPY . /var/www/html/

# Set permissions for SQLite to be writable (adjust as needed)
RUN chown -R www-data:www-data /var/www/html/db

# Expose port (Render uses 80 automatically)
EXPOSE 80
