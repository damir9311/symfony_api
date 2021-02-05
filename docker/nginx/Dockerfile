FROM nginx:stable

# External user id
ARG uid

# Set environment variables section
ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update \
 && apt-get install -y \
        curl

# User permissions.
# See https://denibertovic.com/posts/handling-permissions-with-docker-volumes/
# gosu https://github.com/tianon/gosu
#
# gosu for set local user as user in container
RUN apt-get install -y gnupg
RUN gpg --keyserver ha.pool.sks-keyservers.net --recv-keys B42F6819007F00F88E364FD4036A9C25BF357DD4
RUN curl -o /usr/local/bin/gosu -SL "https://github.com/tianon/gosu/releases/download/1.4/gosu-$(dpkg --print-architecture)" \
 && curl -o /usr/local/bin/gosu.asc -SL "https://github.com/tianon/gosu/releases/download/1.4/gosu-$(dpkg --print-architecture).asc" \
 && gpg --verify /usr/local/bin/gosu.asc \
 && rm /usr/local/bin/gosu.asc \
 && chmod +sx /usr/local/bin/gosu

# Create local user with UID from host machine
RUN useradd -l -u ${uid} -m user
USER user

# Custom nginx configuration
ADD ./nginx.conf /etc/nginx/nginx.conf

ENV PATH="/usr/local/bin:${PATH}"

# Volume for virtual hosts
VOLUME "/etc/nginx/conf.d"

CMD gosu root nginx -g "daemon off;"