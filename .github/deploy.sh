#!/bin/bash
set -e

echo "Deployment started ..."

(php artisan down) || true

git pull origin develop

composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

php artisan clear-compiled

php artisan optimize

php artisan migrate

php artisan up

echo "Deployment finished!"