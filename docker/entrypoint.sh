#!/bin/sh

set -e

cd /srv/api

echo $PWD

if [ "$1" = 'frankenphp' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
	if [ -z "$(ls -A 'vendor/' 2>/dev/null)" ]; then
		composer install --prefer-dist --no-progress --no-interaction
	fi

	setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var
fi

echo $DUNGAP_DATABASE_DSN

php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force --complete -vvv

exec docker-php-entrypoint "$@"
