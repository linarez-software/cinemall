FROM php:5.6.40-apache

# Install essential PHP extensions only
RUN docker-php-ext-install mysqli mysql pdo pdo_mysql

# Enable Apache modules
RUN a2enmod rewrite

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]