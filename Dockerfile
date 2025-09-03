FROM php:8.2-apache

# Install mysqli and pdo_mysql
RUN docker-php-ext-install mysqli pdo_mysql && docker-php-ext-enable mysqli pdo_mysql
