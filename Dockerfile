FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    # Install PHP extensions
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    # Clear cache
    && apt-get clean && rm -rf /var/lib/apt/lists/*
# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

