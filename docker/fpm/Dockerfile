FROM php:7.4.3-fpm

# External user id
ARG uid

# Set environment variables section
ENV DEBIAN_FRONTEND noninteractive
ENV GOSU_VERSION 1.10

# Install system packages
RUN apt-get update \
 && apt-get -y -qq --no-install-recommends install \
        ca-certificates \
        iproute2 \
        curl \
        git \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        libpq-dev \
        libxml2-dev \
        libzip-dev \
        mc \
        ssh \
        wget \
        gnupg \
        dirmngr \
        libonig-dev \
        librabbitmq-dev \
        cron \
        gettext-base

# Install php extensions
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
 && pecl install \
        redis \
        mongodb \
        xdebug-2.9.8 \
        zip \
        amqp \
 && docker-php-ext-enable \
        redis \
        mongodb \
        xdebug \
        zip \
        amqp \
 && docker-php-ext-install -j$(nproc) \
        gd \
        iconv \
        intl \
        mbstring \
        pcntl \
        pdo_pgsql \
        pgsql \
        soap \
        sockets \
        xmlrpc \
        bcmath \
        zip

# Set configuration for xdebug
WORKDIR "/usr/local/etc/php/conf.d/"
RUN echo "xdebug.remote_enable = 1" >> docker-php-ext-xdebug.ini \
 && echo "xdebug.remote_host = host.docker.internal" >> docker-php-ext-xdebug.ini \
 && echo "xdebug.idekey = PHPSTORM" >> docker-php-ext-xdebug.ini \
 && echo "xdebug.remote_port = 9002" >> docker-php-ext-xdebug.ini \
 && echo "xdebug.remote_handler = dbgp" >> docker-php-ext-xdebug.ini \
 && echo "xdebug.remote_mode = req" >> docker-php-ext-xdebug.ini \
 && echo "xdebug.remote_connect_back = off" >> docker-php-ext-xdebug.ini \
 && echo "xdebug.remote_autostart = 1" >> docker-php-ext-xdebug.ini

# Install lib for time in tests
RUN git clone https://github.com/hnw/php-timecop.git\
&& cd php-timecop\
&& phpize\
&& ./configure\
&& make\
&& make install

ADD php.ini  /usr/local/etc/php/

#
RUN echo "extension=timecop.so" >> /usr/local/etc/php/php.ini

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR "/var/www/html"
VOLUME ["/var/www/html"]

# User permissions.
# See https://denibertovic.com/posts/handling-permissions-with-docker-volumes/
# gosu https://github.com/tianon/gosu

# gosu for set local user as user in container
#RUN gpg --keyserver ha.pool.sks-keyservers.net --recv-keys B42F6819007F00F88E364FD4036A9C25BF357DD4
#RUN curl -o /usr/local/bin/gosu -SL "https://github.com/tianon/gosu/releases/download/1.4/gosu-$(dpkg --print-architecture)" \
# && curl -o /usr/local/bin/gosu.asc -SL "https://github.com/tianon/gosu/releases/download/1.4/gosu-$(dpkg --print-architecture).asc" \
# && gpg --verify /usr/local/bin/gosu.asc \
# && rm /usr/local/bin/gosu.asc \
# && chmod +sx /usr/local/bin/gosu

RUN dpkgArch="$(dpkg --print-architecture | awk -F- '{ print $NF }')"; \
	wget -O /usr/local/bin/gosu "https://github.com/tianon/gosu/releases/download/$GOSU_VERSION/gosu-$dpkgArch"; \
	wget -O /usr/local/bin/gosu.asc "https://github.com/tianon/gosu/releases/download/$GOSU_VERSION/gosu-$dpkgArch.asc"; \
	\
# verify the signature
	export GNUPGHOME="$(mktemp -d)"; \
	gpg --keyserver ha.pool.sks-keyservers.net --recv-keys B42F6819007F00F88E364FD4036A9C25BF357DD4; \
	gpg --batch --verify /usr/local/bin/gosu.asc /usr/local/bin/gosu; \
	rm -r "$GNUPGHOME" /usr/local/bin/gosu.asc; \
	\
	chmod +sx /usr/local/bin/gosu; \
# verify that the binary works
	gosu nobody true;

# add custom entrypoint script
#COPY entrypoint.sh /usr/local/bin/entrypoint.sh
#RUN chmod +sx /usr/local/bin/entrypoint.sh
#ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Create local user with UID from host machine
RUN useradd -l -u ${uid} -m user
RUN echo "root:root" | chpasswd

USER user

# Add fpm pool config
ADD www.conf /usr/local/etc/php-fpm.d/www.conf

RUN mkdir -p /home/user/.ssh
VOLUME "/home/user/.ssh/"

# Run composer install before container start
CMD gosu root php-fpm -D \
 && /bin/bash
