# Database Export Information

## 📊 Latest Export Details

**Export File:** `lamgame_full_export_20250906_204641.sql`  
**Compressed:** `lamgame_full_export_20250906_204641.sql.gz`  
**Export Date:** September 6, 2025 - 20:46:41  
**Database:** lamgame  
**MySQL Version:** 8.0.43  

### 📈 Export Statistics:
- **Original Size:** 760K
- **Compressed Size:** 164K (78% compression)
- **Tables Exported:** 137 tables
- **Data Rows:** 42 INSERT statements
- **Character Set:** utf8mb4 (Unicode support)

## 🗂️ Export Options Used:
```bash
mysqldump \
  --single-transaction \    # Consistent snapshot
  --routines \             # Export stored procedures
  --triggers \             # Export triggers
  --complete-insert \      # Full INSERT statements
  --add-drop-table \       # Drop table before create
  --default-character-set=utf8mb4 \  # Unicode support
  --hex-blob \             # Binary data as hex
  lamgame
```

## ✅ Verified Content:
- ✅ **Core Tables:** products, categories, users, locales, attributes
- ✅ **Source Game Data:** super-mario-clone, space-shooter-2d, rpg-inventory-system
- ✅ **Unicode Data:** Vietnamese text properly encoded
- ✅ **Categories:** Fixed hierarchy with proper nested set values
- ✅ **Products:** Source game products with correct attributes

## 📋 Key Tables Included:

### **Core Bagisto Tables:**
- `products` - Product catalog
- `categories` - Category hierarchy  
- `attributes` - Product attributes
- `locales` - Language support
- `users` - Admin users
- `channels` - Sales channels
- `currencies` - Currency configuration

### **Source Game Specific:**
- Product data with Vietnamese descriptions
- Fixed Unicode encoding
- Category hierarchy (Root → Source Code Game → Unity/Mobile/Web/PC Games)
- Custom attributes for source games

### **Blog System:**
- Blog posts and categories
- Comments and tags
- Complete blog data structure

### **Job System:**
- Job postings
- Job categories
- Application system

## 🔄 Import Instructions:

### **Full Restore:**
```bash
# Import to new database
mysql -u username -p new_database < lamgame_full_export_20250906_204641.sql

# Or import from compressed file
zcat lamgame_full_export_20250906_204641.sql.gz | mysql -u username -p new_database
```

### **Docker Import:**
```bash
# Copy to container
docker cp lamgame_full_export_20250906_204641.sql lg-mysql:/tmp/

# Import via container
docker exec lg-mysql mysql -u root -p new_database < /tmp/lamgame_full_export_20250906_204641.sql
```

## ⚠️ Important Notes:

### **Before Import:**
1. Create target database: `CREATE DATABASE target_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`
2. Ensure MySQL supports utf8mb4 character set
3. Check MySQL version compatibility (exported from 8.0.43)

### **After Import:**
1. Update `.env` file with new database credentials
2. Run `php artisan cache:clear`
3. Run `php artisan config:clear`
4. Verify Unicode text displays correctly
5. Test source game products display

## 🧪 Verification Commands:

```bash
# Check table count
mysql -e "SELECT COUNT(*) as table_count FROM information_schema.tables WHERE table_schema = 'your_db';"

# Verify source game products
mysql -e "SELECT sku, name FROM products WHERE sku LIKE '%mario%' OR sku LIKE '%shooter%';" your_db

# Check Vietnamese text
mysql -e "SELECT name FROM locales WHERE code = 'vi';" your_db
```

## 📦 Backup Strategy:

### **Current Files:**
- `lamgame_full_export_20250906_204641.sql` - Full export (760K)
- `lamgame_full_export_20250906_204641.sql.gz` - Compressed (164K)
- Previous backups in database/ directory

### **Retention:**
- Keep latest 3 exports
- Monthly compressed archives
- Production exports stored separately

## 🚀 Production Deployment:

1. **Export** current production data (if any)
2. **Import** this export file to staging
3. **Test** all functionality
4. **Deploy** to production with this data
5. **Verify** Unicode and source game data

---
*Export completed: September 6, 2025*  
*Status: Full database export successful ✅*  
*Ready for production deployment or backup*
