#!/usr/bin/env bash

#
# Toggles XDebug within the backend container
# The development containers must be already running
#

echo "Running within the container."
grep -q ';zend' "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini" # Checks if xdebug is disabled.
STATUS=$?

if [[ $STATUS -eq 1 ]]; then
    REGEX='s/zend_extension/;zend_extension/';
    echo "XDebug was ENABLED"
    echo "Disabling XDebug and reloading FPM...";
elif [[ $STATUS -eq 0 ]]; then
    echo "XDebug was DISABLED"
    REGEX='s/;zend_extension/zend_extension/';
    echo "Enabling XDebug and reloading FPM...";
else
    echo Error obtaining current XDebug status. Status: $STATUS
    exit 1
fi

sed -i "$REGEX" "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini" && pkill -SIGUSR2 php-fpm && echo 'Ready!'
STATUS=$?
