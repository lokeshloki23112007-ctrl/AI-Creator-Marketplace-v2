#!/bin/bash
set -e

# If no remote DB_HOST is provided (or if DB_HOST is local), start and configure local MariaDB
if [ -z "$DB_HOST" ] || [ "$DB_HOST" = "127.0.0.1" ] || [ "$DB_HOST" = "localhost" ]; then
    echo "Configuring local MariaDB for container..."
    mkdir -p /var/run/mysqld /var/lib/mysql
    chown -R mysql:mysql /var/run/mysqld /var/lib/mysql
    
    # Initialize MariaDB system tables if needed
    if [ ! -d "/var/lib/mysql/mysql" ]; then
        echo "Initializing MariaDB system tables..."
        mysql_install_db --user=mysql --datadir=/var/lib/mysql > /dev/null 2>&1 || true
    fi

    # Start MariaDB service
    echo "Starting MariaDB service..."
    service mariadb start || /etc/init.d/mariadb start || true

    # Wait for MariaDB to become ready
    for i in {1..20}; do
        if mariadb-admin ping --silent 2>/dev/null; then
            echo "MariaDB is online and responding."
            break
        fi
        sleep 1
    done

    # Setup database and user permissions for local access
    mariadb -e "CREATE DATABASE IF NOT EXISTS ai_creator_marketplace CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null || true
    mariadb -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' IDENTIFIED BY '' WITH GRANT OPTION; FLUSH PRIVILEGES;" 2>/dev/null || true
    mariadb -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' IDENTIFIED BY '' WITH GRANT OPTION; FLUSH PRIVILEGES;" 2>/dev/null || true

    # Import schema and seed data if database is empty
    if [ -f "database.sql" ]; then
        TABLE_COUNT=$(mariadb -s -N -e "SELECT count(*) FROM information_schema.tables WHERE table_schema='ai_creator_marketplace';" 2>/dev/null || echo "0")
        if [ "$TABLE_COUNT" = "0" ] || [ -z "$TABLE_COUNT" ]; then
            echo "Importing database.sql into ai_creator_marketplace..."
            mariadb ai_creator_marketplace < database.sql 2>/dev/null || true
            echo "Database import complete."
        else
            echo "Database already contains $TABLE_COUNT tables."
        fi
    fi
else
    echo "External database host specified: $DB_HOST"
fi

# Ensure uploads directory is writable
mkdir -p uploads/avatars uploads/videos
chmod -R 777 uploads 2>/dev/null || true

echo "Starting PHP server on 0.0.0.0:${PORT:-10000}..."
exec php -S 0.0.0.0:${PORT:-10000} -t .
