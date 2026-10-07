FROM php:8.4-cli

# Install MariaDB server and client, and PDO MySQL extension
RUN apt-get update && apt-get install -y mariadb-server mariadb-client dos2unix && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /app

COPY . .

# Fix CRLF line endings on start.sh if committed from Windows
RUN sed -i -e 's/\r$//' start.sh && chmod +x start.sh

EXPOSE 10000

CMD ["bash", "start.sh"]