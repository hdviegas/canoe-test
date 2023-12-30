#!/usr/bin/env bash
mysql --user=root --password="$MYSQL_ROOT_PASSWORD" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS canoe_test;
    GRANT ALL PRIVILEGES ON \`canoe_test%\`.* TO '$MYSQL_USER'@'%';
EOSQL
