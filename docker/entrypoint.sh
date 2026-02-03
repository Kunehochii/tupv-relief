#!/bin/sh
set -e

echo "🚀 Starting Relief Application..."

# Wait a moment for volume mount to be ready
sleep 2

# Ensure storage directories exist and have correct permissions FIRST
echo "📂 Setting up storage directories..."
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/testing
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/bootstrap/cache

# Create .gitignore files to preserve directory structure
touch /var/www/html/storage/framework/cache/.gitignore
touch /var/www/html/storage/framework/sessions/.gitignore
touch /var/www/html/storage/framework/views/.gitignore
touch /var/www/html/storage/logs/.gitignore

# Set ownership and permissions
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Verify directories were created
echo "🔍 Verifying storage structure..."
ls -la /var/www/html/storage/framework/

# Clear any stale caches (don't fail if they don't exist)
echo "🧹 Clearing stale caches..."
rm -rf /var/www/html/bootstrap/cache/*.php
php artisan cache:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true

# Create storage symlink if it doesn't exist
if [ ! -L /var/www/html/public/storage ]; then
    echo "📁 Creating storage symlink..."
    php artisan storage:link || true
fi

# Cache configuration for production (AFTER directories are confirmed)
echo "⚙️ Caching configuration..."
php artisan config:cache
php artisan route:cache

# Create supervisor log directory
mkdir -p /var/log/supervisor

echo "✅ Setup complete! Starting services..."

# Start supervisord
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
