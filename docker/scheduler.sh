#!/bin/sh
# Laravel scheduler — runs every 60 seconds
while true; do
    php /var/www/html/artisan schedule:run --no-interaction >> /var/www/html/storage/logs/scheduler.log 2>&1
    sleep 60
done
