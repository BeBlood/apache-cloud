FROM ubuntu:bionic

RUN apt update
RUN apt install -y apache2

COPY apache.sh /opt/docker/apache.sh

WORKDIR /var/www/html

ENTRYPOINT ["/opt/docker/apache.sh"]
