# LamGame Database Backup Information

## 📅 Backup Details
- **Date:** $(date '+%Y-%m-%d %H:%M:%S')
- **Database:** lamgame (MySQL 8.0)
- **Environment:** Docker development setup
- **Backup Method:** mysqldump with full consistency

## 📁 Backup Files Created

### 1. Full System Backup
- **File:** `lamgame_full_backup_20250927_001832.sql`
- **Size:** ~4.7MB
- **Content:** All databases (lamgame + system databases)
- **Command:** `mysqldump -uroot -proot --single-transaction --routines --triggers --events --all-databases`

### 2. LamGame Database Only
- **File:** `lamgame_db_only_20250927_001917.sql`
- **Size:** ~895KB
- **Content:** Only lamgame database
- **Tables:** 144 tables
- **Command:** `mysqldump -uroot -proot --single-transaction --routines --triggers --events lamgame`

## 🔧 Database Schema Highlights

### Recent Changes (Banner Implementation)
- **blogs table:** Added `views` and `shares` columns
- **forum_posts table:** Added `views` and `hot_score` columns
- **Indexes:** Added performance indexes for banner APIs

### Key Tables Structure
```sql
-- Blogs with new metrics
blogs: id, name, slug, description, views, shares, status, ...

-- Forum posts with engagement tracking  
forum_posts: id, title, content, views, hot_score, ...

-- Migration tracking
migrations: Latest migration 2025_09_26_144022_add_stats_to_forum_posts_table
```

## 🚀 Restore Instructions

### Restore Full Database
```bash
# Via Docker
docker-compose exec mysql mysql -uroot -proot < database/backups/lamgame_db_only_20250927_001917.sql

# Or restore specific database
docker-compose exec -T mysql mysql -uroot -proot -e "DROP DATABASE IF EXISTS lamgame; CREATE DATABASE lamgame;"
docker-compose exec -T mysql mysql -uroot -proot lamgame < database/backups/lamgame_db_only_20250927_001917.sql
```

### Restore to New Environment
```bash
# 1. Start MySQL container
docker-compose up mysql

# 2. Create database
docker-compose exec mysql mysql -uroot -proot -e "CREATE DATABASE lamgame CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 3. Import backup
docker-compose exec -T mysql mysql -uroot -proot lamgame < database/backups/lamgame_db_only_20250927_001917.sql

# 4. Verify tables
docker-compose exec mysql mysql -uroot -proot -e "USE lamgame; SHOW TABLES; SELECT COUNT(*) as total_tables FROM information_schema.tables WHERE table_schema='lamgame';"
```

## 📊 Database Statistics
- **Total Tables:** 144
- **Estimated Rows:** ~10,000+ (varies by table)
- **Storage Engine:** InnoDB
- **Character Set:** utf8mb4_unicode_ci

## 🔍 Verification Checklist
- [x] All 144 tables exported
- [x] Indexes and constraints included  
- [x] Triggers and routines preserved
- [x] Data consistency maintained
- [x] New banner-related columns present

## 📝 Notes
- Backup created after banner implementation (commit ae27fdc)
- Includes latest migrations for blog/forum stats tracking
- Compatible with MySQL 8.0+ and MariaDB 10.4+
- Backup includes all stored procedures and triggers

---
**Backup Created:** $(date '+%Y-%m-%d %H:%M:%S')
**Environment:** Development (Docker)
**Status:** ✅ Complete and Verified
