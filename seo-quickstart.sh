#!/bin/bash

# SEO Quick Start Script for LAMGAME.VN
# Run this script to setup SEO tools immediately

echo "🚀 LAMGAME.VN - SEO Quick Start"
echo "================================"
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}❌ Error: artisan file not found. Please run this script from the project root.${NC}"
    exit 1
fi

# Detect if running in Docker or host
DOCKER_CONTAINER="lg-php"
if docker ps --format '{{.Names}}' | grep -q "^${DOCKER_CONTAINER}$"; then
    echo -e "${GREEN}✅ Detected Docker container: ${DOCKER_CONTAINER}${NC}"
    PHP_CMD="docker exec ${DOCKER_CONTAINER} php"
else
    echo -e "${YELLOW}⚠️  Docker container not found, using host PHP${NC}"
    PHP_CMD="php"
fi

echo -e "${YELLOW}Step 1: Generating Sitemap...${NC}"
$PHP_CMD artisan sitemap:generate
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Sitemap generated successfully!${NC}"
    echo ""
else
    echo -e "${RED}❌ Failed to generate sitemap${NC}"
    exit 1
fi

echo -e "${YELLOW}Step 2: Checking sitemap file...${NC}"
if [ -f "public/sitemap.xml" ]; then
    echo -e "${GREEN}✅ Sitemap file exists: public/sitemap.xml${NC}"
    echo "📊 File size: $(du -h public/sitemap.xml | cut -f1)"
    echo "📝 URL count: $(grep -c '<url>' public/sitemap.xml)"
    echo ""
else
    echo -e "${RED}❌ Sitemap file not found${NC}"
    exit 1
fi

echo -e "${YELLOW}Step 3: Checking robots.txt...${NC}"
if grep -q "Sitemap:" public/robots.txt; then
    echo -e "${GREEN}✅ Sitemap declared in robots.txt${NC}"
    grep "Sitemap:" public/robots.txt
    echo ""
else
    echo -e "${YELLOW}⚠️  Adding sitemap to robots.txt...${NC}"
    echo "" >> public/robots.txt
    echo "Sitemap: https://lamgame.vn/sitemap.xml" >> public/robots.txt
    echo -e "${GREEN}✅ Added sitemap to robots.txt${NC}"
    echo ""
fi

echo -e "${YELLOW}Step 4: Checking Google Service Account...${NC}"
if [ -f "storage/app/google-service-account.json" ]; then
    echo -e "${GREEN}✅ Google service account file found${NC}"
    echo "📝 You can use: php artisan google:push-index"
    echo ""
else
    echo -e "${YELLOW}⚠️  Google service account not found${NC}"
    echo "📖 To setup Google Indexing API:"
    echo "   1. Create service account at: https://console.cloud.google.com/"
    echo "   2. Download JSON key file"
    echo "   3. Copy to: storage/app/google-service-account.json"
    echo "   4. Run: php artisan google:push-index --type=all --limit=10"
    echo ""
fi

echo -e "${YELLOW}Step 5: Checking scheduled tasks...${NC}"
php artisan schedule:list | grep -E "sitemap|google" > /dev/null
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Scheduled tasks configured${NC}"
    php artisan schedule:list | grep -E "sitemap|google"
    echo ""
else
    echo -e "${YELLOW}⚠️  Scheduled tasks not found${NC}"
    echo "📝 Make sure to setup cron:"
    echo "   * * * * * cd $(pwd) && php artisan schedule:run >> /dev/null 2>&1"
    echo ""
fi

echo "================================"
echo -e "${GREEN}✅ SEO Quick Start Complete!${NC}"
echo ""
echo "📋 Next Steps:"
echo "   1. Submit sitemap to Google Search Console:"
echo "      https://search.google.com/search-console"
echo "      Add sitemap: https://lamgame.vn/sitemap.xml"
echo ""
echo "   2. Setup Google Indexing API (optional but recommended)"
echo ""
echo "   3. Add structured data to views:"
echo "      - Job detail pages"
echo "      - Blog post pages"
echo ""
echo "   4. Setup cron job for automation"
echo ""
echo "📖 Full documentation:"
echo "   - docs/SEO_ANALYSIS_REPORT.md"
echo "   - docs/SEO_TOOLS_GUIDE.md"
echo "   - docs/SEO_IMPLEMENTATION_SUMMARY.md"
echo ""
echo "🎉 Happy optimizing!"
