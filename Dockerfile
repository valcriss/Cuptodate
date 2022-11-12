FROM yiisoftware/yii2-php:8.1-apache

COPY . /app
RUN apt-get -yq update && apt-get -yq install default-mysql-client
ADD https://raw.githubusercontent.com/nickjj/wait-until/v0.2.0/wait-until /usr/local/bin
RUN chmod +x /usr/local/bin/wait-until

CMD ["/app/entrypoint.sh"]