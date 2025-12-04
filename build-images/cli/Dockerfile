FROM php:8.1-cli

RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev default-mysql-client \
  && docker-php-ext-install pdo_mysql mysqli zip \
  && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install WP-CLI
RUN curl -fsSL https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -o /usr/local/bin/wp \
  && chmod +x /usr/local/bin/wp

WORKDIR /var/www/html

ENTRYPOINT ["/bin/sh", "-c"]
CMD ["while true; do sleep 3600; done"]
