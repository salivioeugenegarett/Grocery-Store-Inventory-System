# Use official PHP 8.2 Apache image
FROM php:8.2-apache

# Install dependencies required for SQLite
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Enable Apache rewrite module (if needed)
RUN a2enmod rewrite

# Copy all project files into the web server root
COPY . /var/www/html/

# Give proper permissions to the SQLite database directory
RUN chown -R www-data:www-data /var/www/html/db

# Expose port 80 (used by Render automatically)
EXPOSE 80
