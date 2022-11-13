#!/bin/bash
wait-until "mysql -u ${DATABASE_USER} -p${DATABASE_PASSWORD} -h ${DATABASE_HOST} ${DATABASE_NAME} -e 'select 1'"
cd /app
runuser -l www-data -c 'composer install'
runuser -l www-data -c './yii migrate --interactive=0'
apache2-foreground