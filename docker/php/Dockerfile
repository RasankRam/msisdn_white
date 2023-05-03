FROM php:7.4-fpm

# Install moduels
RUN apt-get update && apt-get install -y \
    net-tools \
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

# Install nodejs 16
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs
RUN npm install -g yarn
RUN yarn global add @vue/cli

# if need switching to root -> set password
RUN echo 'root:root' | chpasswd

# set by default non-root user
RUN useradd -ms /bin/bash joonix

# Set the working directory
WORKDIR /var/www

# Expose port 9000
EXPOSE 9000
# Start the PHP-FPM server
CMD ["php-fpm"]

# for local testing (windows 10 bug php response time)
#CMD ["php", "artisan", "serve", "--host", "0.0.0.0"]
