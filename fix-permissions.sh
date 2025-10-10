#!/bin/bash

# Laravel Storage Permissions Fix Script
# This script fixes storage and bootstrap cache permissions for both development and production

echo "🔧 Fixing Laravel Storage Permissions..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_step() {
    echo -e "${BLUE}[STEP]${NC} $1"
}

# Check if we're in a Laravel project
if [ ! -f "artisan" ]; then
    print_error "Not a Laravel project directory. Please run this script from your Laravel project root."
    exit 1
fi

print_step "Creating necessary directories..."
mkdir -p storage/framework/cache
mkdir -p storage/framework/views
mkdir -p storage/framework/sessions
mkdir -p storage/logs
mkdir -p storage/app/public
mkdir -p bootstrap/cache

print_step "Setting directory permissions..."

# Set permissions for storage
chmod -R 777 storage
chmod -R 777 bootstrap/cache

# Make sure the directories are writable by web server
if command -v chown > /dev/null 2>&1; then
    print_step "Setting ownership (if you have sudo access)..."
    
    # Try to detect web server user
    if id "_www" &>/dev/null; then
        WEB_USER="_www"
    elif id "www-data" &>/dev/null; then
        WEB_USER="www-data"
    elif id "apache" &>/dev/null; then
        WEB_USER="apache"
    elif id "nginx" &>/dev/null; then
        WEB_USER="nginx"
    else
        WEB_USER=$(whoami)
        print_warning "Could not detect web server user, using current user: $WEB_USER"
    fi
    
    print_status "Detected web server user: $WEB_USER"
    
    # For development, use current user but ensure group permissions allow web server access
    if [ "$1" = "--production" ]; then
        print_step "Setting production ownership..."
        sudo chown -R $WEB_USER:$WEB_USER storage bootstrap/cache
    else
        print_step "Setting development ownership..."
        # Keep current user as owner but set group permissions
        chown -R $(whoami):staff storage bootstrap/cache 2>/dev/null || true
    fi
fi

print_step "Clearing Laravel caches..."
php artisan view:clear 2>/dev/null || print_warning "Could not clear view cache (this is normal if database is not connected)"
php artisan config:clear 2>/dev/null || print_warning "Could not clear config cache"

print_step "Verifying permissions..."
if [ -w "storage/framework/views" ] && [ -w "storage/logs" ] && [ -w "bootstrap/cache" ]; then
    print_status "✅ All directories are writable!"
else
    print_error "❌ Some directories are still not writable. You may need to run this script with sudo."
    exit 1
fi

print_status "🎉 Laravel storage permissions fixed successfully!"

echo
echo "📋 Directory permissions:"
echo "   storage/framework/views: $(ls -ld storage/framework/views | cut -d' ' -f1)"
echo "   storage/logs:           $(ls -ld storage/logs | cut -d' ' -f1)"  
echo "   bootstrap/cache:        $(ls -ld bootstrap/cache | cut -d' ' -f1)"
echo

if [ "$1" != "--production" ]; then
    echo "💡 Tips:"
    echo "   - Run with --production flag for production deployment"
    echo "   - These permissions (777) are for development only"
    echo "   - For production, use more restrictive permissions (755/644)"
fi