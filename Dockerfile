# Use the official PHP 8.3 image as the base image
FROM php:8.3-cli

# Install system dependencies for Composer and BCMath PHP extension
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip bcmath

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Set the working directory inside the container
WORKDIR /app

# Copy the application code to the container
COPY . /app

# Install PHPUnit and any other project dependencies
RUN composer install

# Command to run the tests
CMD ["./vendor/bin/phpunit", "--colors=always", "tests/"]