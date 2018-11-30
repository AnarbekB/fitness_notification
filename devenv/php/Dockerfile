FROM php:7.2.3-fpm-stretch

RUN apt-get update
RUN apt-get install -y libz-dev

RUN apt-get install -y \
  libpng-dev \
  libfreetype6-dev \
  libjpeg-dev \
  libxpm-dev \
  libxml2-dev \
  libxslt-dev \
  libwebp-dev  # php >=7.0 (use libvpx for php <7.0)
RUN docker-php-ext-configure gd \
    --with-freetype-dir=/usr/include/ \
    --with-jpeg-dir=/usr/include/ \
    --with-xpm-dir=/usr/include \
    --with-webp-dir=/usr/include/ # php >=7.0 (use libvpx for php <7.0)
RUN docker-php-ext-install gd

RUN docker-php-ext-install zip
RUN docker-php-ext-install xsl

RUN apt-get install -y libicu-dev
RUN docker-php-ext-install intl

RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install opcache
RUN docker-php-ext-install bcmath

RUN docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install pcntl

RUN pecl install xdebug-2.6.0
RUN echo "zend_extension=`php -i | grep ^extension_dir | cut -f 3 -d ' '`/xdebug.so" >> /usr/local/etc/php/conf.d/xdebug.ini

RUN docker-php-ext-install soap

ENV COMPOSER_HOME=/composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get install -y git vim rsync sshpass bzip2

ADD php.ini /usr/local/etc/php/php.ini

# Cron
#RUN apt-get install -y cron \
#    && mkfifo --mode 0666 /var/log/cron.log \
#    && sed --regexp-extended --in-place \
#    's/^session\s+required\s+pam_loginuid.so$/session optional pam_loginuid.so/' \
#    /etc/pam.d/cron
#
#COPY start-cron /usr/sbin
#RUN chmod +x /usr/sbin/start-cron
# Cron

RUN apt-get update -q && \
    apt-get install --no-install-recommends -qy \
        supervisor

CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]


# imagick
RUN apt-get install -y libmagickwand-dev wget traceroute \
    && pecl install imagick \
    && docker-php-ext-enable imagick

RUN chown -R www-data:www-data /composer