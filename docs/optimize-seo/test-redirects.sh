#!/bin/bash

echo "=== Testing SEO Redirects ==="
echo ""

# Test 1: index.php redirect
echo "Test 1: index.php redirect"
curl -I -s "https://lamgame.vn/index.php/blog" | grep -E "HTTP|Location"
echo ""

# Test 2: .html redirect
echo "Test 2: .html redirect"
curl -I -s "https://lamgame.vn/top-game-doanh-thu-khung-tren-steam-2018.html" | grep -E "HTTP|Location"
echo ""

# Test 3: Clean URL works
echo "Test 3: Clean URL (should return 200)"
curl -I -s "https://lamgame.vn/blog" | grep "HTTP"
echo ""

# Test 4: Auth page meta robots
echo "Test 4: Auth page (check for noindex in HTML)"
curl -s "https://lamgame.vn/auth/login" | grep -i "robots"
echo ""

# Test 5: Pagination page meta robots
echo "Test 5: Pagination page=2 (check for noindex in HTML)"
curl -s "https://lamgame.vn/blog?page=2" | grep -i "robots"
echo ""

echo "=== Test Complete ==="
