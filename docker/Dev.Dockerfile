FROM php:7.4-fpm-buster

# APT Repositories aktualisieren
RUN apt update -y

# Abhaengigkeit fuer PHP-7.4 Extensions
RUN apt install libonig-dev libsqlite3-dev libmagickwand-dev -y

# PHP Extensions installieren
RUN docker-php-ext-install mbstring pdo pdo_sqlite
RUN pecl install imagick
RUN docker-php-ext-enable imagick

# Build-Abhaengigkeiten entfernen
RUN apt remove libonig-dev libsqlite3-dev libmagickwand-dev -y

# Wartungstools: tmux vim
RUN apt install -y tmux vim

# Composer requirements
RUN apt install -y wget git curl zip unzip

# Composer installieren
RUN wget "https://getcomposer.org/download/latest-stable/composer.phar" -O /usr/bin/composer \
    && chmod +x /usr/bin/composer

# Nginx installieren
RUN apt install -y nginx

# Nginx konfigurieren
COPY ./config/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./config/nginx/books.conf /etc/nginx/sites-available/books.conf
RUN ln -s /etc/nginx/sites-available/books.conf /etc/nginx/sites-enabled/books \
    && chmod 0744 /etc/nginx/sites-available/books.conf \
    && rm /etc/nginx/sites-enabled/default

# PHP-FPM konfigurieren
COPY ./config/php-fpm/php.ini /usr/local/etc/php/php.ini
COPY ./config/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf

# Imagick konfigurieren
COPY ./config/imagick/policymap.xml /etc/ImageMagick-6/policy.xml

# Webserver Root anlegen
RUN mkdir -p /var/www/html/books

# Startup Skript
COPY ./startup.sh /startup.sh
CMD ["/bin/bash", "/startup.sh"]

EXPOSE 80
