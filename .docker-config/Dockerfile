FROM qalliance/php-apache:latest

MAINTAINER mona20black@gmail.com

USER root

ARG www_data_uid
ARG www_data_gid
RUN groupmod -g $www_data_gid www-data && \
    usermod -u $www_data_uid -g $www_data_gid www-data && \
    chown -R www-data. /var/www && \
    chown www-data. /usr/local/bin/composer

RUN echo 'linux' > /etc/docker_host && \
    chmod +r /etc/docker_host

USER www-data
