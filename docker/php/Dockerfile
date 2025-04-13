# Utiliser l'image PHP 8.4 FPM de base
FROM php:8.4-fpm

# Installer les dépendances nécessaires pour Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    git \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mbstring zip

# Installer l'extension Redis via PECL
RUN apt-get install -y libssl-dev && \
    pecl install redis && \
    docker-php-ext-enable redis

# Installer l'extension PCOV via PECL
RUN pecl install pcov && \
    docker-php-ext-enable pcov && \
    echo "pcov.enabled=1" > /usr/local/etc/php/conf.d/pcov.ini

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configurer le répertoire de travail
WORKDIR /app

# Exposer le port 9000 pour PHP-FPM
EXPOSE 9000