FROM php:8.4-cli

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /app

COPY . .

EXPOSE 10000

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} -t ."]