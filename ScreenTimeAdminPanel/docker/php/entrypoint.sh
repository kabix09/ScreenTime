#!/bin/sh

# Install dependencies
composer install

# Update DB table schema
php bin/console doctrine:schema:update --force

# Run php
/usr/local/sbin/php-fpm