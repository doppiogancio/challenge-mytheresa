FROM php:8.3-fpm

# Switch to root to install dependencies
USER root

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-configure intl \
    && docker-php-ext-install \
        pdo_pgsql \
        intl \
        zip \
        opcache

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configure Xdebug
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.discover_client_host=true" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.log=/var/www/html/var/log/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Set recommended PHP.ini settings
#RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Configure opcache
RUN echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=4000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/opcache.ini

# Configure Redis session handling
RUN echo "session.save_handler = redis" >> /usr/local/etc/php/conf.d/redis-session.ini \
    && echo "session.save_path = 'tcp://redis:6379'" >> /usr/local/etc/php/conf.d/redis-session.ini \
    && echo "redis.session.locking_enabled = 1" >> /usr/local/etc/php/conf.d/redis-session.ini \
    && echo "redis.session.lock_retries = 10" >> /usr/local/etc/php/conf.d/redis-session.ini \
    && echo "redis.session.lock_wait_time = 10000" >> /usr/local/etc/php/conf.d/redis-session.ini

# Switch back to www-data user for security
USER www-data