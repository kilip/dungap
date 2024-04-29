#!/bin/bash

cd /srv/api
source /usr/local/bin/api-entrypoint

# TODO remove this line fixed in goss testing
exec /srv/api/bin/console doctrine:schema:update --force --complete