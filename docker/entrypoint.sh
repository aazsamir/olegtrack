#!/bin/sh

# Start cron in background
supercronic /etc/tempest-cron &

php /app/tempest migrate

# Continue with the original entrypoint
exec docker-php-entrypoint --config /etc/frankenphp/Caddyfile --adapter caddyfile