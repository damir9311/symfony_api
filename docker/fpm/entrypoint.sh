#!/bin/bash

gosu user composer config -g github-oauth.github.com ${GITHUB_OAUTH_TOKEN}
export PATH="$PATH:/var/www/html/vendor/bin"

exec "$@"
