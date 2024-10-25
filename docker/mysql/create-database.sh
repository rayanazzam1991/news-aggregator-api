#!/usr/bin/env bash
echo "Attempting to create the testing database..."
mysql --user=root --password="$MYSQL_ROOT_PASSWORD" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS testing;
    GRANT ALL PRIVILEGES ON \`testing%\`.* TO '$MYSQL_USER'@'%';
EOSQL
if [ $? -eq 0 ]; then
    echo "Database 'testing' created successfully!"
else
    echo "Error creating the 'testing' database."
fi
