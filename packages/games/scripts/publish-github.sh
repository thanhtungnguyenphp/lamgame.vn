#!/bin/bash
# Push games monorepo to public GitHub repository
# Run from packages/games/ directory

set -e

REPO_URL="git@github.com:lamgame/games.git"
BRANCH="main"

echo "🚀 Preparing to push to GitHub: $REPO_URL"

# Init git if not exists
if [ ! -d ".git" ]; then
  git init
  git remote add origin "$REPO_URL"
fi

# Ensure .gitignore
cat > .gitignore << 'EOF'
node_modules/
dist/
*.tsbuildinfo
.DS_Store
android/
ios/
capacitor.config.ts
EOF

# Stage all
git add -A
git status

echo ""
echo "📋 Files staged. To push:"
echo "  git commit -m 'feat: initial open source release'"
echo "  git push -u origin $BRANCH"
echo ""
echo "⚠️  Ensure GitHub repo '$REPO_URL' is created first!"
echo "    gh repo create lamgame/games --public --description '🎮 Open Source Mini Games — Phaser 3 + TypeScript'"
