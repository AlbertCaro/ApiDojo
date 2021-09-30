#!/usr/bin/env bash

if [ -z "$(ls -A docker/mysql/data/it_db)" ]; then
    echo "---- Not database loaded, loading database ----"

    wait-for-it database:3306 -- bin/console doctrine:schema:create && bin/console doctrine:fixtures:load --no-interaction
else
    echo "---- Database loaded, database load skipped ----"
fi
