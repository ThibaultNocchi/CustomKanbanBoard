#!/bin/bash
mkdir -p sqlite
touch sqlite/db.sqlite
chmod -R a+w sqlite/
php artisan migrate
