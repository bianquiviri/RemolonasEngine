#!/bin/sh

# Install Laravel if it doesn't exist (assuming vendor folder check)
if [ ! -d "vendor" ]; then
    echo "Vendor folder not found. Initializing Laravel project or running composer install..."
    if [ ! -f "artisan" ]; then
        echo "Creating new Laravel project..."
        composer create-project laravel/laravel:^11.0 temp_app
        mv temp_app/* temp_app/.* . 2>/dev/null || true
        rm -rf temp_app
    else
        composer install
    fi
fi

# NPM install
if [ ! -d "node_modules" ] && [ -f "package.json" ]; then
    echo "Running npm install..."
    npm install
fi

# Ensure permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true

# Initialize activity log if not exists
mkdir -p storage/logs
touch storage/logs/agent_activity.log
chmod 666 storage/logs/agent_activity.log

# Ensure .env exists
if [ ! -f ".env" ]; then
    cp .env.example .env
    php artisan key:generate
    
    # Configure DB in .env
    sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=pgsql/" .env
    sed -i "s/DB_HOST=.*/DB_HOST=db/" .env
    sed -i "s/DB_PORT=.*/DB_PORT=5432/" .env
    sed -i "s/DB_DATABASE=.*/DB_DATABASE=remolonas/" .env
    sed -i "s/DB_USERNAME=.*/DB_USERNAME=remolonas/" .env
    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=password/" .env
    
    # Configure Redis in .env
    sed -i "s/REDIS_HOST=.*/REDIS_HOST=redis/" .env
    sed -i "s/QUEUE_CONNECTION=.*/QUEUE_CONNECTION=redis/" .env
fi

# Start Laravel queue worker in the background
echo "Starting services..."
php artisan queue:work redis --sleep=3 --tries=3 &

# Execute main CMD (php-fpm)
exec "$@"
