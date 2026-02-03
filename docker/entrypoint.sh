#!/bin/sh
set -e

echo "🚀 Starting Relief Application..."

# Create storage symlink if it doesn't exist
if [ ! -L /var/www/html/public/storage ]; then
    echo "📁 Creating storage symlink..."
    php artisan storage:link || true
fi

# Ensure storage directories exist and have correct permissions
echo "📂 Setting up storage directories..."
mkdir -p /var/www/html/storage/framework/{cache,sessions,views}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/app/public
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Cache configuration for production
echo "⚙️ Caching configuration..."
php artisan config:cache
php artisan route:cache

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Create supervisor log directory
mkdir -p /var/log/supervisor

echo "✅ Setup complete! Starting services..."

# Start supervisord
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
