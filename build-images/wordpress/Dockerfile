FROM wordpress:6.3-php8.1-apache

# Install pdo_mysql
RUN apt-get update && \
    apt-get install -y default-mysql-client && \
    docker-php-ext-install pdo_mysql && \
    rm -rf /var/lib/apt/lists/*

EXPOSE 80
