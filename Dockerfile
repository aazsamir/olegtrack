FROM dunglas/frankenphp

RUN apt-get update && apt-get -y install git unzip
RUN install-php-extensions intl
ADD https://github.com/aptible/supercronic/releases/download/v0.2.39/supercronic-linux-amd64 /usr/local/bin/supercronic
RUN chmod +x /usr/local/bin/supercronic

COPY tempest /app/tempest
RUN mkdir /app/app

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY composer.json /app/composer.json
COPY composer.lock /app/composer.lock
ENV COMPOSER_HOME=/.composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN --mount=type=cache,target=/.composer/cache composer install --no-dev --optimize-autoloader

COPY app /app/app
COPY public /app/public
COPY .env.docker /app/.env

RUN chown -R www-data:www-data app
RUN php tempest key:generate
RUN php tempest discovery:generate

ENV SERVER_NAME=:80

# Add cronjob
COPY docker/tempest-cron /etc/tempest-cron
RUN chmod 0644 /etc/tempest-cron

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

RUN mkdir /app/var

EXPOSE 80