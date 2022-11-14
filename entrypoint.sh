#!/bin/bash
wait-until "mysql -u ${DATABASE_USER} -p${DATABASE_PASSWORD} -h ${DATABASE_HOST} ${DATABASE_NAME} -e 'select 1'"
cd /app
runuser -l cuptodate -c 'cd /app && composer install'
runuser -l cuptodate -c '/app/yii migrate --interactive=0'
apache2-foreground