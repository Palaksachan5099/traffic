#!/bin/bash

# Traffic App - Server Deployment Script
# Usage: bash deploy.sh

set -e

echo "============================================"
echo "Traffic App - Deployment Script"
echo "============================================"
echo ""

# Check if .env file exists
if [ ! -f .env ]; then
    echo "❌ Error: .env file not found!"
    echo "Please run: cp .env.example .env"
    echo "Then configure your .env file and run this script again."
    exit 1
fi

echo "📦 Step 1: Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader
echo "✅ PHP dependencies installed"
echo ""

echo "📦 Step 2: Installing frontend dependencies..."
npm install
echo "✅ Frontend dependencies installed"
echo ""

echo "🔨 Step 3: Building frontend assets..."
npm run build
echo "✅ Frontend assets built"
echo ""

echo "🔑 Step 4: Generating application key..."
php artisan key:generate
echo "✅ Application key generated"
echo ""

echo "🔗 Step 5: Creating storage symlink..."
php artisan storage:link
echo "✅ Storage symlink created"
echo ""

echo "🧹 Step 6: Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
echo "✅ Caches cleared"
echo ""

echo "🔐 Step 7: Setting directory permissions..."
chmod -R 775 storage bootstrap/cache
echo "✅ Permissions set"
echo ""

echo "🗄️  Step 8: Running database migrations..."
php artisan migrate --force
echo "✅ Migrations completed"
echo ""

echo "🌱 Step 9: Seeding database (optional)..."
read -p "Do you want to seed the database? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan db:seed
    echo "✅ Database seeded"
else
    echo "⏭️  Skipped database seeding"
fi
echo ""

echo "============================================"
echo "✅ Deployment completed successfully!"
echo "============================================"
echo ""
echo "Next steps:"
echo "1. Verify .env configuration"
echo "2. Set up Nginx/Apache vhost"
echo "3. Configure SSL certificate"
echo "4. Set up MongoDB (if not already running)"
echo "5. Configure email settings in .env"
echo ""
echo "For more help, see deploy instructions in README.md"
