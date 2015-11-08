FROM phusion/baseimage:latest
MAINTAINER Matthew Baggett <matthew@baggett.me>

CMD ["/sbin/my_init"]

# Install base packages
ENV DEBIAN_FRONTEND noninteractive
RUN apt-key adv --keyserver keyserver.ubuntu.com --recv-keys E5267A6C
RUN echo "deb http://ppa.launchpad.net/ondrej/php5-5.6/ubuntu precise main " >> /etc/apt/sources.list
RUN apt-get update && \
    apt-get -yq install \
        curl \
        git \
        apache2 \
        libapache2-mod-php5 \
        php5-mysql \
#        php5-gd \
        php5-curl \
        php-pear \
        php5-dev \
	php5-xdebug \
        php-apc && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN sed -i "s/variables_order.*/variables_order = \"EGPCS\"/g" /etc/php5/apache2/php.ini
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable SSH Server
#RUN rm -f /etc/service/sshd/down
#RUN /etc/my_init.d/00_regen_ssh_host_keys.sh

# Configure /app folder with sample app
RUN mkdir -p /app && rm -fr /var/www/html && ln -s /app /var/www/html
ADD . /app
ADD docker/ApacheConfig.conf /etc/apache2/sites-enabled/000-default.conf

# Run Composer
RUN cd /app && composer install

# Enable mod_rewrite
RUN a2enmod rewrite && /etc/init.d/apache2 restart

# Add ports.
EXPOSE 80

WORKDIR /app

# Add configs
ADD docker/apache2.conf /etc/apache2/apache2.conf

# Add startup scripts
RUN mkdir /etc/service/apache2
ADD docker/run.apache.sh /etc/service/apache2/run
RUN chmod +x /etc/service/*/run
