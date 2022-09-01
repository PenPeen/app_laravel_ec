FROM php:8.1-apache
RUN apt-get update \
  && apt-get install -y zlib1g-dev \
  && apt-get install -y zip unzip \
  && apt-get install -y libonig-dev \
  && apt-get -y install libzip-dev \
  && docker-php-ext-install pdo_mysql mysqli mbstring zip \
  && docker-php-ext-enable mysqli \
  && a2enmod rewrite \
  && apt-get install -y nodejs npm \
  && npm install n -g \
  && n 18.7.0

# composer インストール
RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && chmod +x /usr/local/bin/composer

# phpcs インストール
RUN composer global require squizlabs/php_codesniffer
# phpcs PATH
ENV PATH=/root/.config/composer/vendor/bin:$PATH

# debugbarインストール
RUN composer require barryvdh/laravel-debugbar

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin
ENV TZ=Asia/Tokyo

# GD
RUN apt-get update && \
  apt-get -y install git libicu-dev libonig-dev libzip-dev unzip nodejs npm vim unzip libfreetype6-dev libjpeg62-turbo-dev libpng-dev libjpeg-dev \
  && docker-php-ext-install \
  pdo_mysql \
  bcmath \
  gd \
  && docker-php-ext-configure gd \
  --with-freetype=/usr/include/ \
  --with-jpeg=/usr/include/ \
  && docker-php-ext-install -j$(nproc) gd