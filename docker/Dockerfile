FROM jakubzapletal/ebsphp1:5.5

RUN mkdir -p /var/log/ebs && chmod -R 0777 /var/log/ebs

RUN \
    apt-get update \
    && apt-get install -y --force-yes --no-install-recommends \
        php5.5-fpm \
        php5.5-xdebug \
        nginx \
    && rm -rf /var/lib/apt/lists/* \
    && mkdir /run/php \
    && ln -sf /dev/stdout /var/log/nginx/access.log \
	&& ln -sf /dev/stderr /var/log/nginx/error.log

RUN \
    apt-get update \
    && apt-get install -y --force-yes --no-install-recommends \
        php5.5-redis \
    && rm -rf /var/lib/apt/lists/*

RUN \
    sed -i 's|memory_limit = 128M|memory_limit = 512M|g' /etc/php/5.5/fpm/php.ini \
    && sed -i 's|log_errors_max_len = 1024|log_errors_max_len = 0|g' /etc/php/5.5/fpm/php.ini \
    && sed -i 's|memory_limit = 128M|memory_limit = 512M|g' /etc/php/5.5/cli/php.ini \
    && sed -i 's|log_errors_max_len = 1024|log_errors_max_len = 0|g' /etc/php/5.5/cli/php.ini

EXPOSE 80

CMD ["/usr/bin/supervisord"]
