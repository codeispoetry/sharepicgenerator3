FROM php:8.2-apache

RUN apt-get update && apt-get install -y sqlite3 gettext sudo nano
RUN curl -LO https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
RUN apt-get install -y ./google-chrome-stable_current_amd64.deb
RUN rm ./google-chrome-stable_current_amd64.deb

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN docker-php-ext-install gettext && docker-php-ext-enable gettext
RUN a2enmod rewrite

RUN echo 'www-data ALL=NOPASSWD: ALL' | tee /etc/sudoers.d/www-data
