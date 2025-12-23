#!/bin/bash

/usr/sbin/php-fpm8.3 -F &
apachectl -D FOREGROUND
