FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       libcurl4-openssl-dev pkg-config \
       build-essential autoconf \
    && apt-get clean

## Install Xdebug
#RUN pecl install xdebug \
#    && docker-php-ext-enable xdebug

## Install Xdebug only if it's not installed
RUN if ! pecl list | grep -q xdebug; then \
        pecl install xdebug && \
        docker-php-ext-enable xdebug; \
    else \
        echo "Xdebug already installed"; \
    fi

# Install PDO MySQL extension
RUN docker-php-ext-install pdo_mysql
