#!/bin/bash
# Seed Source Game Products - LamGame.vn
# Usage: docker exec lg-php bash database/seeds/seed-all-source-games.sh

echo "=== SEEDING SOURCE GAMES ==="
echo ""

echo "--- [1/3] Free tier (10 products - $0) ---"
php artisan source-game:import --file=database/seeds/source-games.json
echo ""

echo "--- [2/3] Starter tier (10 products - 25,000 VND / ~$1) ---"
php artisan source-game:import --file=database/seeds/source-games-paid.json
echo ""

echo "--- [3/3] Pro tier (10 products - 50,000 VND / ~$2) ---"
php artisan source-game:import --file=database/seeds/source-games-premium.json
echo ""

echo "=== DONE ==="
