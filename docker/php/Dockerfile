FROM php:7.4-fpm

# Install moduels
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install mysqli gd mbstring pdo_mysql pcntl bcmath opcache

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# if need switching to root -> set password
RUN echo 'root:root' | chpasswd

# set by default non-root user
RUN useradd -ms /bin/bash joonix

# Set the working directory
WORKDIR /var/www/backend

# Expose port 9000
EXPOSE 8000
# Start the PHP-FPM server
CMD ["php-fpm"]

# for local testing (windows 10 bug php response time)
#CMD ["php", "artisan", "serve", "--host", "0.0.0.0"]
