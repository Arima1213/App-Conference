#!/bin/bash

# PPPKMI Conference - Clear All Caches and Reset
echo "🧹 Clearing all caches and resetting Laravel..."

cd /home/pppkmior/public_html/conference

# 1. Clear all Laravel caches
echo "🗑️ Clearing Laravel caches..."
/usr/local/bin/ea-php82 artisan cache:clear
/usr/local/bin/ea-php82 artisan config:clear
/usr/local/bin/ea-php82 artisan route:clear
/usr/local/bin/ea-php82 artisan view:clear
/usr/local/bin/ea-php82 artisan event:clear

# 2. Clear compiled class cache
echo "🔄 Clearing compiled classes..."
/usr/local/bin/ea-php82 artisan clear-compiled

# 3. Regenerate autoload
echo "📦 Regenerating autoloader..."
/usr/local/bin/ea-php82 /usr/local/bin/composer dump-autoload

# 4. Cache for production
echo "⚡ Caching for production..."
/usr/local/bin/ea-php82 artisan config:cache
/usr/local/bin/ea-php82 artisan route:cache
/usr/local/bin/ea-php82 artisan view:cache

# 5. Test commands are available
echo "🧪 Testing commands..."
/usr/local/bin/ea-php82 artisan list | grep -E "(storage:link-cpanel|queue:monitor|production:validate)" || echo "   Custom commands need registration"

# 6. Manual storage setup (always works)
echo "📁 Setting up storage manually..."
mkdir -p public/storage
if [ -d "storage/app/public" ]; then
    cp -r storage/app/public/* public/storage/ 2>/dev/null || echo "   No files to copy"
fi

echo ""
echo "✅ Cache clearing and reset completed!"
echo ""
echo "📋 Next steps:"
echo "1. Check if custom commands are now available:"
echo "   /usr/local/bin/ea-php82 artisan list | grep storage"
echo ""
echo "2. If still not working, use basic cron job:"
echo "   * * * * * cd /home/pppkmior/public_html/conference && /usr/local/bin/ea-php82 artisan schedule:run"
echo ""
echo "3. Storage is set up manually and should work"
