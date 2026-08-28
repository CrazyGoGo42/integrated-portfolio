FROM php:8.1-apache

# System dependencies
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql mysqli zip

# Apache modules
RUN a2enmod rewrite headers expires deflate

# SPA-aware vhost
COPY apache-spa.conf /etc/apache2/sites-available/000-default.conf

# The repo keeps the frontend (HTML/CSS/JS/assets) and the backend (PHP API)
# in separate folders; at build time they are served together from one docroot.
COPY frontend/ /var/www/html/
COPY backend/  /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Writable uploads directory (also a mounted volume at runtime)
RUN mkdir -p /var/www/html/uploads \
    && chown www-data:www-data /var/www/html/uploads \
    && chmod 777 /var/www/html/uploads

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/ || exit 1
