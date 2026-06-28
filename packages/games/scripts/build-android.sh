#!/bin/bash
# Build Android APK for all Tier S games
# Usage: ./scripts/build-android.sh [game-slug]
# Example: ./scripts/build-android.sh 2048-ghep-so

set -e

GAMES_DIR="$(cd "$(dirname "$0")/.." && pwd)/games"
TIER_S_GAMES=("2048-ghep-so" "xep-gach" "chim-bay" "ran-san-moi" "keo-ngot-xep-3")

build_game() {
  local slug="$1"
  local game_dir="$GAMES_DIR/$slug"

  echo "🎮 Building $slug..."

  # Web build
  cd "$game_dir"
  pnpm build

  # Init Capacitor if not done
  if [ ! -d "android" ]; then
    # Generate capacitor.config.ts from template
    local app_name
    app_name=$(node -e "console.log(require('./package.json').name.split('/').pop().replace(/-/g,' ').replace(/\b\w/g,c=>c.toUpperCase()))")
    sed -e "s/GAME_SLUG/$slug/g" -e "s/GAME_NAME/$app_name/g" \
      ../../scripts/capacitor.config.template.ts > capacitor.config.ts

    npx cap init "$app_name" "vn.lamgame.$slug" --web-dir dist
    npx cap add android
  fi

  # Sync and build
  npx cap sync android
  cd android
  ./gradlew assembleRelease

  local apk="app/build/outputs/apk/release/app-release-unsigned.apk"
  if [ -f "$apk" ]; then
    echo "✅ APK: $game_dir/android/$apk"
  else
    echo "❌ Build failed for $slug"
    return 1
  fi
}

# Build single or all
if [ -n "$1" ]; then
  build_game "$1"
else
  for game in "${TIER_S_GAMES[@]}"; do
    build_game "$game"
  done
fi

echo ""
echo "📋 Next steps:"
echo "  1. Sign APKs: jarsigner -keystore lamgame.keystore app-release-unsigned.apk lamgame"
echo "  2. Align: zipalign -v 4 app-release-unsigned.apk app-release.apk"
echo "  3. Upload to Play Console: https://play.google.com/console"
