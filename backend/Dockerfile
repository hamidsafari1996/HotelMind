FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libonig-dev libzip-dev zip \
    && docker-php-ext-install intl pdo pdo_mysql zip

WORKDIR /app

COPY composer.json composer.lock ./

# Install composer with retry mechanism and timeout configuration
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    composer config -g process-timeout 2000 && \
    composer config -g github-protocols https && \
    composer config -g repo.packagist composer https://packagist.org

# Install dependencies with retry
RUN composer install --no-scripts --no-autoloader --prefer-dist

COPY . .

# Generate autoloader
RUN composer dump-autoload --optimize

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
