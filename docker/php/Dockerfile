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
    imagemagick \
    sqlite
RUN apt-get install libsqlite3-dev -y
RUN docker-php-ext-install  bz2 iconv mbstring pdo_mysql pdo_sqlite mysqli pgsql pdo_pgsql zip sockets bcmath \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd
#RUN pecl install xdebug imagick  && docker-php-ext-enable xdebug imagick
RUN apt-get install sudo -y
RUN docker-php-ext-install pcntl
RUN apt-get install sudo python -y
RUN curl https://bootstrap.pypa.io/get-pip.py -o get-pip.py
RUN python3 get-pip.py
RUN pip3 install numpy
RUN apt install ffmpeg -y
RUN apt-get install graphviz -y
RUN pecl install apcu && docker-php-ext-enable apcu
RUN pecl install -o -f redis \
  &&  rm -rf /tmp/pear \
  &&  docker-php-ext-enable redis

RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin && \
        echo "alias composer='composer'" >> /root/.bashrc && \
        composer

ARG user_uid
# RUN usermod -u ${user_uid? invalid argument} www-data
# RUN groupmod -g ${user_uid? invalid argument} www-data
RUN curl -LsS https://codeception.com/codecept.phar -o /usr/local/bin/codecept && \
 chmod a+x /usr/local/bin/codecept



