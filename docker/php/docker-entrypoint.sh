#!/bin/sh
set -e

if [ -d /var/www/html/vendor ]; then
    chown -R symfony:symfony /var/www/html/vendor
fi

if [ "$1" = 'php-fpm' ]; then
    exec "$@"
fi

exec su-exec symfony "$@"
