FROM php:7.2-fpm
RUN apt-get update && apt-get upgrade -y \
    apt-transport-https \
    ca-certificates \
    curl \
    gnupg2 \
    software-properties-common\
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpq-dev \
    libmagickwand-dev \
    libmcrypt-dev \
    libmcrypt-dev \
    libmemcached-dev \
    libssl-dev \
    libssl-doc \
    libsasl2-dev \
    zlib1g-dev \
    apt-utils \
    libmagickwand-dev \
    imagemagick
RUN docker-php-ext-install  bz2 iconv mbstring pdo_mysql mysqli pgsql pdo_pgsql zip sockets bcmath \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd
RUN pecl install xdebug imagick  && docker-php-ext-enable xdebug imagick
RUN usermod --non-unique --uid ${UID_USER:-1000} www-data
RUN groupmod -g ${UID_USER:-1000} www-data
