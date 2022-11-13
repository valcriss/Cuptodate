FROM yiisoftware/yii2-php:8.1-apache

COPY . /app
RUN apt-get -yq update && apt-get -yq install default-mysql-client cron
ADD https://raw.githubusercontent.com/nickjj/wait-until/v0.2.0/wait-until /usr/local/bin
RUN chmod +x /usr/local/bin/wait-until && chmod +x /app/entrypoint.sh  && chmod +x /app/yii

ADD ./crontab /etc/cron.d/update-cron
RUN chmod 0644 /etc/cron.d/update-cron && touch /var/log/update.log

CMD ["/app/entrypoint.sh"]