#!/bin/bash

echo -n "enter database username: "
read username
echo -n "enter database password: "
read -s password

mysql -u $username -p$password -e 'CREATE DATABASE geo_simulator;'
mysql -u $username -p$password -b geo_simulator < geo_simulator.sql
echo "Loaded database"

npm install
echo "Node dependencies downloaded"

composer update
echo "PHP-Laravel dependencies downloaded"

echo "Now you can run the following commands in a separte command line to start using this mini-app: "
echo ""
echo "php artisan serve"
echo ""
echo "node/nodejs webSocket.js"

