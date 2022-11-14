FROM yiisoftware/yii2-php:8.1-apache

COPY . /app
RUN apt-get -yq update && apt-get -yq install default-mysql-client cron
ADD https://raw.githubusercontent.com/nickjj/wait-until/v0.2.0/wait-until /usr/local/bin
RUN chmod +x /usr/local/bin/wait-until && chmod +x /app/entrypoint.sh  && chmod +x /app/yii

COPY crontab /etc/crontab

RUN useradd cuptodate --home /home/cuptodate --create-home --groups www-data
RUN chown -R cuptodate:www-data /app

WORKDIR /app

CMD ["/app/entrypoint.sh"]