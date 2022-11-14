#!/bin/bash
wait-until "mysql -u ${DATABASE_USER} -p${DATABASE_PASSWORD} -h ${DATABASE_HOST} ${DATABASE_NAME} -e 'select 1'" 600

cd /app && composer install
/app/yii migrate --interactive=0
chown -R www-data:www-data /app
#service supervisor start
systemctl start update
apache2-foreground