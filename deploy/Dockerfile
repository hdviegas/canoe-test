FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    zip \
    curl \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Remove the default Apache site
RUN a2dissite 000-default.conf

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

# Install project dependencies
RUN composer install

# Change current user to www
USER www-data

# Expose port 80 and start apache server
EXPOSE 80
CMD apache2-foreground
