FROM ghcr.io/kilip/frankenphp-api:1.1.4-php8.3 as upstream

RUN apk add --no-cache \
        nodejs-current \
        nmap \
        awake \
        supervisor \
        bash \
        bash-completion \
    ;

RUN set -eux; \
    install-php-extensions \
        pcntl \
        redis \
        pcov \
        bcmath \
    ;

# Install goss latest version to /usr/local/bin
RUN curl -fsSL https://goss.rocks/install | sh

FROM upstream AS base
# api start
WORKDIR /srv/api
COPY --link ./docker/frankenphp/conf.d/app.ini $PHP_INI_DIR/conf.d/
COPY --link --chmod=755 ./docker/frankenphp/docker-entrypoint.sh /usr/local/bin/api-entrypoint
COPY --link --chmod=755 ./docker/entrypoint.sh /usr/local/bin/dungap-entrypoint
COPY --link ./docker/frankenphp/Caddyfile /etc/caddy/Caddyfile
# api end

#syntax=docker/dockerfile:1.7@sha256:dbbd5e059e8a07ff7ea6233b213b36aa516b4c53c645f1817a4dd18b83cbea56

# pwa start
WORKDIR /srv/pwa
RUN corepack enable && \
	corepack prepare --activate pnpm@latest && \
	pnpm config -g set store-dir /.pnpm-store
# pwa end

COPY --link docker/supervisor.d/messenger.ini /etc/supervisor.d/
COPY --link docker/supervisor.d/tasks.ini /etc/supervisor.d/
COPY --link docker/supervisord.conf /etc/

HEALTHCHECK --start-period=60s CMD curl -f http://localhost:2019/metrics || exit 1
ENTRYPOINT ["dungap-entrypoint"]
CMD [ "supervisord", "-c", "/etc/supervisord.conf" ]
# base stage end

# dev stage start
FROM base as dev

# api start
ENV APP_ENV=dev XDEBUG_MODE=off
VOLUME /srv/api/var/
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN set -eux; \
	install-php-extensions \
		xdebug \
	;
COPY --link ./docker/frankenphp/conf.d/app.dev.ini $PHP_INI_DIR/conf.d/
# api end

# pwa start
EXPOSE 5173
ENV PORT 5173
ENV HOSTNAME localhost
# pwa end

RUN rm -rf /var/cache/apk
# change workdir to /
WORKDIR /
COPY --link docker/supervisor.d/caddy.dev.ini /etc/supervisor.d/
COPY --link docker/supervisor.d/pwa.dev.ini /etc/supervisor.d/
# dev stage end


FROM base AS builder
WORKDIR /srv/pwa
# pwa start
COPY --link ./pwa/pnpm-lock.yaml ./
RUN pnpm fetch --prod

COPY --link ./pwa /srv/pwa

RUN	pnpm install && \
	pnpm build
# pwa end

FROM base as production
# api start
WORKDIR /srv/api
ENV APP_ENV=prod
ENV FRANKENPHP_CONFIG="import worker.Caddyfile"
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY --link ./docker/frankenphp/conf.d/app.prod.ini $PHP_INI_DIR/conf.d/
COPY --link ./docker/frankenphp/worker.Caddyfile /etc/caddy/worker.Caddyfile

# prevent the reinstallation of vendors at every changes in the source code
COPY --link ./api/composer.* ./api/symfony.* ./
RUN set -eux; \
	composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress
# copy sources
COPY --link ./api /srv/api
RUN rm -Rf frankenphp/
RUN set -eux; \
	mkdir -p var/cache var/log; \
	composer dump-autoload --classmap-authoritative --no-dev; \
	composer dump-env prod; \
	composer run-script --no-dev post-install-cmd; \
	chmod +x bin/console; sync;
# api end

# pwa start
WORKDIR /srv/pwa
ENV NODE_ENV production
COPY --from=builder --link /srv/pwa/public ./public
COPY --from=builder --link /srv/pwa/build ./build
COPY --from=builder --link /srv/pwa/node_modules ./node_modules
ADD ./pwa /srv/pwa
# pwa end

COPY --link docker/supervisor.d/caddy.prod.ini /etc/supervisor.d/
COPY --link docker/supervisor.d/pwa.prod.ini /etc/supervisor.d/


RUN rm -rf /var/cache/apk
WORKDIR /
