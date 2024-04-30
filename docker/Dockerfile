#syntax=docker/dockerfile:1.7@sha256:dbbd5e059e8a07ff7ea6233b213b36aa516b4c53c645f1817a4dd18b83cbea56

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
WORKDIR /
COPY --link ./docker/frankenphp/conf.d/app.ini $PHP_INI_DIR/conf.d/
COPY --link --chmod=755 ./docker/frankenphp/docker-entrypoint.sh /usr/local/bin/api-entrypoint
COPY --link --chmod=755 ./docker/entrypoint.sh /usr/local/bin/dungap-entrypoint
COPY --link ./docker/frankenphp/Caddyfile /etc/caddy/Caddyfile
# api end

# pwa start
RUN corepack enable && \
	corepack prepare --activate pnpm@latest
# pwa end

COPY --link docker/supervisor.d/messenger.ini /etc/supervisor.d/
COPY --link docker/supervisor.d/tasks.ini /etc/supervisor.d/
COPY --link docker/supervisord.conf /etc/

VOLUME /var/log/dungap

ENTRYPOINT ["api-entrypoint"]

HEALTHCHECK --start-period=60s CMD curl -f http://localhost:2019/metrics || exit 1

CMD [ "supervisord", "-c", "/etc/supervisord.conf" ]

FROM base as dev

# api start
ENV APP_ENV=dev XDEBUG_MODE=off

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

COPY --link docker/supervisor.d/caddy.dev.ini /etc/supervisor.d/
COPY --link docker/supervisor.d/pwa.dev.ini /etc/supervisor.d/
# dev stage end

FROM base as pwa-deps
WORKDIR /srv/pwa

COPY --link ./pwa/pnpm-lock.yaml /srv/pwa
COPY --link ./pwa/package.json /srv/pwa
RUN --mount=type=cache,id=pnpm,target=/pnpm/store pnpm install --prod --frozen-lockfile

FROM base AS pwa-build
WORKDIR /srv/pwa

COPY --link ./pwa /srv/pwa

RUN --mount=type=cache,id=pnpm,target=/pnpm/store pnpm install --frozen-lockfile \
    && pnpm run build

FROM base as production

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY --link ./docker/frankenphp/conf.d/app.prod.ini $PHP_INI_DIR/conf.d/
COPY --link ./docker/frankenphp/worker.Caddyfile /etc/caddy/worker.Caddyfile

WORKDIR /srv/api
# prevent the reinstallation of vendors at every changes in the source code
COPY --link ./api/composer.* ./api/symfony.* /srv/api/
RUN set -eux; \
	composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress
# copy sources
COPY --link ./api /srv/api
RUN set -eux; \
	mkdir -p var/cache var/log; \
	composer dump-autoload --classmap-authoritative --no-dev; \
	composer dump-env prod; \
	composer run-script --no-dev post-install-cmd; \
	chmod +x /srv/api/bin/console; sync;
# api end

# pwa start
ENV NODE_ENV production
COPY --from=pwa-deps /srv/pwa/node_modules /srv/pwa/node_modules
COPY --from=pwa-build --link /srv/pwa/build /srv/pwa/build
COPY --from=pwa-build --link /srv/pwa/public /srv/pwa/public
COPY --from=pwa-build --link /srv/pwa/package.json /srv/pwa
# pwa end

WORKDIR /
COPY --link docker/supervisor.d/caddy.prod.ini /etc/supervisor.d/
COPY --link docker/supervisor.d/pwa.prod.ini /etc/supervisor.d/

RUN rm -rf /var/cache/apk
