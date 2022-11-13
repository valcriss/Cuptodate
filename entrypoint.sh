#!/bin/bash
wait-until "mysql -u ${DATABASE_USER} -p${DATABASE_PASSWORD} -h ${DATABASE_HOST} ${DATABASE_NAME} -e 'select 1'"
cd /app
runuser -l cuptodate -c 'composer install'
runuser -l cuptodate -c './yii migrate --interactive=0'
apache2-foreground