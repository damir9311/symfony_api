#!/bin/sh

set -e

cd /var/www/html/

composer install

# check codestyle
composer cs-check

# check audit
composer cs-check-audit

php bin/console doctrine:migrations:migrate --db=migrations -n -vvv
