#!/bin/bash

# Database Import Script for lamgame
# Usage: ./import_database.sh [database_name] [sql_file]

echo "🗄️  Lamgame Database Import Script"
echo "=================================="

# Default values
DEFAULT_DB="lamgame_import"
LATEST_EXPORT=$(ls -t database/lamgame_full_export_*.sql 2>/dev/null | head -1)
LATEST_COMPRESSED=$(ls -t database/lamgame_full_export_*.sql.gz 2>/dev/null | head -1)

# Parameters
DB_NAME=${1:-$DEFAULT_DB}
SQL_FILE=${2}

# Function to show usage
show_usage() {
    echo "Usage: $0 [database_name] [sql_file]"
    echo ""
    echo "Parameters:"
    echo "  database_name   Target database name (default: lamgame_import)"
    echo "  sql_file        SQL file to import (auto-detect if not specified)"
    echo ""
    echo "Examples:"
    echo "  $0                              # Import to lamgame_import using latest export"
    echo "  $0 my_database                  # Import to my_database using latest export"  
    echo "  $0 my_db export.sql            # Import specific file to my_db"
    echo ""
    exit 1
}

# Show help if requested
if [[ "$1" == "-h" ]] || [[ "$1" == "--help" ]]; then
    show_usage
fi

# Auto-detect SQL file if not provided
if [[ -z "$SQL_FILE" ]]; then
    if [[ -n "$LATEST_EXPORT" ]]; then
        SQL_FILE="$LATEST_EXPORT"
        echo "📁 Auto-detected SQL file: $SQL_FILE"
    elif [[ -n "$LATEST_COMPRESSED" ]]; then
        SQL_FILE="$LATEST_COMPRESSED" 
        echo "📁 Auto-detected compressed file: $SQL_FILE"
    else
        echo "❌ No SQL export files found in database/ directory"
        echo "   Run export first or specify SQL file path"
        exit 1
    fi
fi

# Check if SQL file exists
if [[ ! -f "$SQL_FILE" ]]; then
    echo "❌ SQL file not found: $SQL_FILE"
    exit 1
fi

echo "🎯 Target database: $DB_NAME"
echo "📄 SQL file: $SQL_FILE"
echo "💾 File size: $(du -sh "$SQL_FILE" | cut -f1)"

# Confirm import
read -p "❓ Continue with import? [y/N]: " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "⏹️  Import cancelled"
    exit 0
fi

echo ""
echo "🚀 Starting database import..."
echo "=============================="

# Check if we're using Docker
if command -v docker &> /dev/null && docker ps | grep -q lg-mysql; then
    echo "🐳 Using Docker MySQL container (lg-mysql)"
    
    # Create database
    echo "📋 Creating database: $DB_NAME"
    docker exec lg-mysql mysql -u lg -plg -e "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    
    if [[ $? -ne 0 ]]; then
        echo "❌ Failed to create database"
        exit 1
    fi
    
    # Import data
    echo "📥 Importing data..."
    if [[ "$SQL_FILE" == *.gz ]]; then
        # Handle compressed file
        echo "📦 Decompressing and importing..."
        zcat "$SQL_FILE" | docker exec -i lg-mysql mysql -u lg -plg "$DB_NAME"
    else
        # Handle regular SQL file
        docker exec -i lg-mysql mysql -u lg -plg "$DB_NAME" < "$SQL_FILE"
    fi
    
    IMPORT_RESULT=$?
    
else
    echo "💻 Using local MySQL"
    
    # Prompt for MySQL credentials
    read -p "MySQL username [root]: " MYSQL_USER
    MYSQL_USER=${MYSQL_USER:-root}
    
    read -s -p "MySQL password: " MYSQL_PASS
    echo ""
    
    # Create database
    echo "📋 Creating database: $DB_NAME"
    mysql -u "$MYSQL_USER" -p"$MYSQL_PASS" -e "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    
    if [[ $? -ne 0 ]]; then
        echo "❌ Failed to create database"
        exit 1
    fi
    
    # Import data
    echo "📥 Importing data..."
    if [[ "$SQL_FILE" == *.gz ]]; then
        # Handle compressed file
        echo "📦 Decompressing and importing..."
        zcat "$SQL_FILE" | mysql -u "$MYSQL_USER" -p"$MYSQL_PASS" "$DB_NAME"
    else
        # Handle regular SQL file  
        mysql -u "$MYSQL_USER" -p"$MYSQL_PASS" "$DB_NAME" < "$SQL_FILE"
    fi
    
    IMPORT_RESULT=$?
fi

# Check import result
if [[ $IMPORT_RESULT -eq 0 ]]; then
    echo ""
    echo "✅ Database import completed successfully!"
    echo "======================================"
    
    # Verify import
    echo "🔍 Verifying import..."
    
    if command -v docker &> /dev/null && docker ps | grep -q lg-mysql; then
        # Docker verification
        TABLE_COUNT=$(docker exec lg-mysql mysql -u lg -plg -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$DB_NAME';" 2>/dev/null)
        PRODUCT_COUNT=$(docker exec lg-mysql mysql -u lg -plg -N -e "SELECT COUNT(*) FROM products;" "$DB_NAME" 2>/dev/null)
        LOCALE_CHECK=$(docker exec lg-mysql mysql -u lg -plg -N -e "SELECT name FROM locales WHERE code = 'vi';" "$DB_NAME" 2>/dev/null)
    else
        # Local MySQL verification  
        TABLE_COUNT=$(mysql -u "$MYSQL_USER" -p"$MYSQL_PASS" -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$DB_NAME';" 2>/dev/null)
        PRODUCT_COUNT=$(mysql -u "$MYSQL_USER" -p"$MYSQL_PASS" -N -e "SELECT COUNT(*) FROM products;" "$DB_NAME" 2>/dev/null)
        LOCALE_CHECK=$(mysql -u "$MYSQL_USER" -p"$MYSQL_PASS" -N -e "SELECT name FROM locales WHERE code = 'vi';" "$DB_NAME" 2>/dev/null)
    fi
    
    echo "📊 Tables imported: $TABLE_COUNT"
    echo "🎮 Products imported: $PRODUCT_COUNT"
    echo "🇻🇳 Vietnamese locale: $LOCALE_CHECK"
    
    echo ""
    echo "🎯 Next Steps:"
    echo "=============="
    echo "1. Update .env file with new database name:"
    echo "   DB_DATABASE=$DB_NAME"
    echo ""
    echo "2. Clear Laravel cache:"
    echo "   php artisan cache:clear"
    echo "   php artisan config:clear"
    echo ""
    echo "3. Test the application:"
    echo "   - Check source game products"
    echo "   - Verify Vietnamese text display"
    echo "   - Test admin functionality"
    echo ""
    echo "✨ Import completed successfully!"
    
else
    echo ""
    echo "❌ Database import failed!"
    echo "========================"
    echo "Check the error messages above for details."
    echo ""
    echo "💡 Troubleshooting tips:"
    echo "- Ensure MySQL user has CREATE and INSERT privileges"
    echo "- Check if target database already exists"
    echo "- Verify SQL file is not corrupted"
    echo "- Check MySQL version compatibility"
    exit 1
fi
