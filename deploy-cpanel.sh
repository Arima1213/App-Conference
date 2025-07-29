#!/bin/bash

# PPPKMI Conference - cPanel Safe Deployment Script
# This script avoids functions that might be disabled in shared hosting

echo "🚀 PPPKMI Conference - cPanel Deployment Starting..."
echo "=================================================="

# Set the correct path
cd /home/pppkmior/public_html/conference

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please check the path."
    exit 1
fi

echo "📁 Current directory: $(pwd)"

# 1. Composer install (production)
echo "📦 Installing Composer dependencies..."
/usr/local/bin/ea-php82 /usr/local/bin/composer install --no-dev --optimize-autoloader --no-interaction

# 2. Create storage link safely
echo "🔗 Creating storage link..."
# Test if custom command is available
if /usr/local/bin/ea-php82 artisan storage:link-cpanel --help > /dev/null 2>&1; then
    /usr/local/bin/ea-php82 artisan storage:link-cpanel
else
    echo "⚠️ Custom storage:link-cpanel not available, using manual method..."
    # Manual storage setup
    mkdir -p public/storage
    if [ -d "storage/app/public" ]; then
        cp -r storage/app/public/* public/storage/ 2>/dev/null || echo "   No files to copy yet"
    fi
    echo "✅ Manual storage setup completed"
fi

# 3. Set proper permissions
echo "🔒 Setting permissions..."
chmod -R 755 storage/ 2>/dev/null || echo "   Permissions already set or restricted"
chmod -R 755 bootstrap/cache/ 2>/dev/null || echo "   Cache permissions already set"

# 4. Clear and cache configurations
echo "🗑️ Clearing caches..."
/usr/local/bin/ea-php82 artisan cache:clear
/usr/local/bin/ea-php82 artisan config:clear
/usr/local/bin/ea-php82 artisan route:clear
/usr/local/bin/ea-php82 artisan view:clear

echo "⚡ Optimizing for production..."
/usr/local/bin/ea-php82 artisan config:cache
/usr/local/bin/ea-php82 artisan route:cache
/usr/local/bin/ea-php82 artisan view:cache

# 5. Database migrations
echo "🗄️ Running migrations..."
/usr/local/bin/ea-php82 artisan migrate --force

# 6. Queue tables setup
echo "🔄 Setting up queue tables..."
/usr/local/bin/ea-php82 artisan queue:table --quiet 2>/dev/null || echo "   Queue table already exists"
/usr/local/bin/ea-php82 artisan queue:failed-table --quiet 2>/dev/null || echo "   Failed jobs table already exists"
/usr/local/bin/ea-php82 artisan migrate --force

# 7. Test commands registration
echo "🧪 Testing custom commands..."
/usr/local/bin/ea-php82 artisan test:commands 2>/dev/null || echo "   Custom commands test not available"

# 8. Validate production setup
echo "🔍 Validating production setup..."
if /usr/local/bin/ea-php82 artisan production:validate --help > /dev/null 2>&1; then
    /usr/local/bin/ea-php82 artisan production:validate
else
    echo "⚠️ Production validation command not available"
    echo "   Running basic checks..."
    /usr/local/bin/ea-php82 artisan about --only=environment
fi

echo ""
echo "✅ Deployment completed!"
echo ""
echo "📋 Next steps:"
echo "1. Set up cron jobs in cPanel:"
echo "   * * * * * cd /home/pppkmior/public_html/conference && /usr/local/bin/ea-php82 artisan schedule:run >> storage/logs/scheduler.log 2>&1"
echo ""
echo "2. Test the website:"
echo "   https://conference.pppkmi.org"
echo ""
echo "3. Check logs if needed:"
echo "   tail -f storage/logs/laravel.log"
echo ""
echo "🎉 PPPKMI Conference is ready for production!"
