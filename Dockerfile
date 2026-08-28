FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql mysqli zip

# Enable Apache modules
RUN a2enmod rewrite headers expires deflate

# Configure Apache for SPA
COPY apache-spa.conf /etc/apache2/sites-available/000-default.conf

# Copy application files (frontend + backend integrated)
COPY php_backend/ /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Create uploads directory
RUN mkdir -p /var/www/html/uploads \
    && chown www-data:www-data /var/www/html/uploads \
    && chmod 777 /var/www/html/uploads

# Enable compression and caching for frontend assets
RUN echo "LoadModule deflate_module modules/mod_deflate.so" >> /etc/apache2/apache2.conf
RUN echo "LoadModule expires_module modules/mod_expires.so" >> /etc/apache2/apache2.conf

EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/ || exit 1