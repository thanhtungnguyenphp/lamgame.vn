#!/bin/sh
# Start queue worker in background, restart if it dies
while true; do
    php /var/www/html/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600 --memory=128
    echo "[$(date)] Queue worker exited, restarting in 5s..."
    sleep 5
done
