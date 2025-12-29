FROM php:7.4-apache

# Instalar dependencias del sistema para extensiones comunes
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libxml2-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxslt1-dev \
    zip \
    unzip \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    intl \
    mbstring \
    gd \
    zip \
    soap \
    xml \
    opcache \
 && docker-php-ext-enable \
    mysqli \
    pdo_mysql \
    intl \
    mbstring \
    gd \
    zip \
    soap

# Directorio de trabajo del servidor web
WORKDIR /var/www/html

# Habilitar mod_rewrite por si la app lo usa
RUN a2enmod rewrite
