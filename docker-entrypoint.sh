#!/bin/bash

# Fix Git dubious ownership warning for Docker volumes
echo "Configuring Git safe directory..."
git config --global --add safe.directory /var/www/html

# 1. Install composer dependencies if vendor doesn't exist or needs sync
echo "Running composer install..."
composer install --no-interaction --optimize-autoloader

# --- NUEVO: Crear subcarpetas de estructura y corregir permisos en caliente ---
echo "Re-applying permissions for storage and cache..."
mkdir -p /var/www/html/storage/framework/cache /var/www/html/storage/framework/views /var/www/html/storage/framework/sessions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
# ------------------------------------------------------------------------------

# 2. Wait for PostgreSQL to be ready using native Bash sockets
echo "Waiting for PostgreSQL to start..."
until timeout 1 bash -c 'cat < /dev/null > /dev/tcp/db/5432' 2>/dev/null; do
  echo "PostgreSQL is unavailable - sleeping"
  sleep 2
done
echo "PostgreSQL is up and running!"

# 3. Run migrations and seeders automatically
echo "Running database migrations..."
php artisan migrate:fresh --seed

# NUEVO: Limpiar cachés viejas acumuladas
php artisan optimize:clear

# 4. Start Apache correctly using the official image setup
echo "Starting Apache..."
exec apache2-foreground
