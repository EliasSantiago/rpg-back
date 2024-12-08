FROM php:8.1-fpm

# set your user name, ex: user=bernardo
ARG user=elias
ARG uid=1000

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions and Composer
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath sockets

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user
RUN pecl install redis && docker-php-ext-enable redis

# Set working directory
WORKDIR /var/www

# Copy custom configurations PHP
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Configure environment variables for Laravel
#ENV DB_CONNECTION=sqlite
#ENV DB_DATABASE=/var/www/database/database.sqlite

# Create SQLite database directory and file within the project
#RUN mkdir -p /var/www/database && touch /var/www/database/database.sqlite

USER $user