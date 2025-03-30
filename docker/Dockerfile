FROM php:8.2-apache

# Installation des extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libmariadb-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \ 
    && docker-php-ext-install mysqli pdo_mysql intl \
    && docker-php-ext-install mysqli pdo_mysql \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd


RUN  pecl install apcu \
    && docker-php-ext-enable apcu

# Activation des modules Apache
RUN a2enmod ssl
RUN a2enmod rewrite

# Activation du site SSL par défaut
RUN a2ensite default-ssl

# Commande de démarrage
CMD ["apache2-foreground"]
