#!/bin/bash

# CBT System - Update Dependencies for PHP 8.0+ Hosting
# This script updates composer dependencies for hosting compatibility

echo "🚀 CBT System - Updating Dependencies for Hosting"
echo "=================================================="

# Remove existing composer.lock and vendor directory
echo "📦 Cleaning existing dependencies..."
rm -rf composer.lock
rm -rf vendor/

# Update composer to latest version
echo "🔄 Updating Composer..."
composer self-update

# Install dependencies with PHP 8.0+ compatibility
echo "📥 Installing dependencies for PHP 8.0+..."
composer install --no-dev --optimize-autoloader --prefer-dist

# Check if installation was successful
if [ $? -eq 0 ]; then
    echo "✅ Dependencies updated successfully!"
    echo ""
    echo "📋 Summary:"
    echo "- PHP: ^8.0 (compatible with 8.0, 8.1, 8.2, 8.3, 8.4)"
    echo "- CodeIgniter: ^4.3.0 (PHP 8.0+ compatible)"
    echo "- PHPSpreadsheet: ^1.29 (PHP 8.0+ compatible)"
    echo "- PHPUnit: ^9.5 (PHP 8.0+ compatible)"
    echo ""
    echo "🌐 Ready for hosting deployment!"
else
    echo "❌ Failed to update dependencies!"
    echo "Please check the error messages above."
    exit 1
fi

# Create deployment info
echo "📝 Creating deployment info..."
cat > deployment_info.txt << EOF
CBT System - Deployment Information
==================================

Generated: $(date)
PHP Version Required: 8.0+
CodeIgniter Version: 4.3.x
Environment: Production Ready

Dependencies:
- codeigniter4/framework: ^4.3.0
- phpoffice/phpspreadsheet: ^1.29

Hosting Requirements:
- PHP 8.0 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Apache mod_rewrite or Nginx
- Memory: 128MB minimum, 256MB recommended

Files to Upload:
- All files except:
  * .git/
  * tests/
  * .env (create new on server)
  * writable/ (set permissions 755)

Post-Upload Steps:
1. Copy .env.example to .env
2. Configure database settings
3. Set writable/ permissions to 755
4. Run: php spark migrate
5. Test application

EOF

echo "✅ Deployment info created: deployment_info.txt"
echo ""
echo "🎯 Next Steps:"
echo "1. Upload all files to hosting"
echo "2. Configure .env file"
echo "3. Set proper permissions"
echo "4. Test the application"
