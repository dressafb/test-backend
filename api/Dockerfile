FROM php:7.4.19-fpm-alpine3.12 AS base

ARG DB_SERVER
ARG DB_PORT
ARG DB_NAME
ARG NEW_RELIC_ENABLED
ARG NEW_RELIC_LICENSE_KEY
ARG NEW_RELIC_APP_NAME

ENV DB_SERVER $DB_SERVER
ENV DB_PORT $DB_PORT
ENV DB_NAME $DB_NAME
ENV NEW_RELIC_ENABLED $NEW_RELIC_ENABLED
ENV NEW_RELIC_LICENSE_KEY $NEW_RELIC_LICENSE_KEY
ENV NEW_RELIC_APP_NAME $NEW_RELIC_APP_NAME

ENV COMPOSER_ALLOW_SUPERUSER="1"
ENV PATH="/app/bin:/app/vendor/bin:${PATH}"

RUN docker-php-ext-install pdo_mysql \
    && cd ~ \
    && export NEWRELIC_VERSION="$(curl -sS https://download.newrelic.com/php_agent/release/ | sed -n 's/.*>\(.*linux-musl\).tar.gz<.*/\1/p')" \
    && curl -sS "https://download.newrelic.com/php_agent/release/${NEWRELIC_VERSION}.tar.gz" | gzip -dc | tar xf - \
    && cd "${NEWRELIC_VERSION}" \
    && NR_INSTALL_SILENT=true ./newrelic-install install \
    && curl -Lo datadog-php-tracer.apk "https://github.com/DataDog/dd-trace-php/releases/download/0.66.0/datadog-php-tracer_0.66.0_noarch.apk" \
    && apk add --no-cache datadog-php-tracer.apk --allow-untrusted \
    && cd .. \
    && unset NEWRELIC_VERSION \
    && sed -i \
        -e "s/;\?newrelic.enabled =.*/newrelic.enabled = \${NEW_RELIC_ENABLED}/" \
        -e "s/newrelic.license =.*/newrelic.license = \${NEW_RELIC_LICENSE_KEY}/" \
        -e "s/newrelic.appname =.*/newrelic.appname = \${NEW_RELIC_APP_NAME}/" \
        /usr/local/etc/php/conf.d/newrelic.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

EXPOSE 9000

FROM base AS dev

ARG DB_USER
ARG DB_PASSWORD

ENV DB_USER $DB_USER
ENV DB_PASSWORD $DB_PASSWORD

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug-3.0.0 \
    && docker-php-ext-enable xdebug \
    && apk add --no-cache --virtual pbj-mysql \
        mysql-client \
        mariadb-connector-c \
    && echo "Symfony dependency: PHP intl extension." \
    && apk add --no-cache icu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-enable intl

COPY docker/php-fpm/php.dev.ini /usr/local/etc/php/php.ini

ARG XDEBUG_REMOTE_HOST="172.17.0.1"
RUN sed -i "s|xdebug.client_host=localhost|xdebug.client_host=$XDEBUG_REMOTE_HOST|g" /usr/local/etc/php/php.ini

FROM base AS ci

ARG DB_USER
ARG DB_PASSWORD

ENV DB_USER $DB_USER
ENV DB_PASSWORD $DB_PASSWORD

COPY ./ /app

RUN apk add --no-cache $PHPIZE_DEPS && \
    pecl install xdebug-3.0.0 && \
    docker-php-ext-enable xdebug && \
    composer validate --strict && \
    composer install \
    --optimize-autoloader \
    --no-ansi \
    --no-interaction \
    --no-progress \
    --no-suggest

FROM base AS prod

ARG INFRA_ENVIRONMENT=prod
ENV INFRA_ENVIRONMENT $INFRA_ENVIRONMENT

ARG SYMFONY_DECRYPTION_SECRET
ENV SYMFONY_DECRYPTION_SECRET $SYMFONY_DECRYPTION_SECRET

ARG DEPLOY_CMD=php-fpm
ENV DEPLOY_CMD $DEPLOY_CMD

COPY docker/php-fpm/php.prod.ini /usr/local/etc/php/php.ini
COPY docker/php-fpm/docker-entrypoint.prod.sh /usr/local/bin/docker-entrypoint.sh
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

RUN install-php-extensions intl mysqli opcache

COPY ./composer.json  /app/composer.json
COPY ./composer.lock  /app/composer.lock

RUN composer self-update --1 && \
    composer install \
    --no-dev --optimize-autoloader \
    --no-ansi --no-interaction --no-progress --no-scripts --no-suggest

COPY ./bin       /app/bin
COPY ./config    /app/config
COPY ./public    /app/public
COPY ./src       /app/src
COPY ./templates /app/templates
COPY ./.env.prod /app/.env
COPY ./.env.staging /app/.env.staging

RUN mkdir /app/var/ \
    && mkdir /app/var/cache \
    && mkdir /app/var/log \
    && chown -R www-data:www-data /app/var \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["sh", "/usr/local/bin/docker-entrypoint.sh"]
CMD ${DEPLOY_CMD}
