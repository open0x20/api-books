#!/bin/bash

/etc/init.d/nginx start
/usr/local/sbin/php-fpm --nodaemonize -c /usr/local/etc/php/php.ini
