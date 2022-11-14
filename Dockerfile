FROM yiisoftware/yii2-php:8.1-apache

COPY . /app
RUN apt-get -yq update && apt-get -yq install default-mysql-client cron supervisor
ADD https://raw.githubusercontent.com/nickjj/wait-until/v0.2.0/wait-until /usr/local/bin
RUN chmod +x /usr/local/bin/wait-until && chmod +x /app/entrypoint.sh  && chmod +x /app/yii && chmod +x /app/update.sh

COPY update.conf /etc/supervisor/conf.d/update.conf
COPY update.service /etc/systemd/system/update.service
RUN systemctl daemon-reload
CMD ["/app/entrypoint.sh"]