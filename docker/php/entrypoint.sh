#!/usr/bin/env bash

set -e


if [ ! -f /var/www/.env ] && [ -f /var/www/.env.example ]; then
    echo "Creating .env file ..."
    cp -a /var/www/.env.example /var/www/.env
fi

# Создаём папки, если их нет
mkdir -p /var/www/storage /var/www/bootstrap/cache /var/www/storage/logs /var/log/supervisor /var/run

echo "Setting permissions for storage and bootstrap/cache directories..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R a+rwx /var/www/storage /var/www/bootstrap/cache

if [ ! -f /var/www/storage/logs/laravel.log ]; then
    touch /var/www/storage/logs/laravel.log
    chown www-data:www-data /var/www/storage/logs/laravel.log
    chmod a+rwx /var/www/storage/logs/laravel.log
fi


if [ ! -d /var/www/vendor ] || [ ! -f /var/www/composer.lock ]; then
    echo "Installing composer packages..."
    composer install --optimize-autoloader --no-interaction --no-progress
fi


# Ждём готовности MySQL
# echo "Waiting for MySQL to be ready..."
# until mysql -h "$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "SELECT 1" >/dev/null 2>&1; do
#     echo "MySQL is not ready yet..."
#     sleep 2
# done
# echo "MySQL is ready!"


echo "Starting Supervisor..."
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf


exec "$@"
