#!/bin/bash
# Remove macOS metadata files (._*) from the project
# These are AppleDouble resource fork files created when copying to non-HFS+ volumes
# Run: bash scripts/cleanup-macos-metadata.sh [--dry-run]

set -e

ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
DRY_RUN=false

if [[ "$1" == "--dry-run" ]]; then
    DRY_RUN=true
    echo "=== DRY RUN — no files will be deleted ==="
fi

echo "Scanning for ._ files in: $ROOT_DIR"
echo "Excluding: vendor/, node_modules/, .git/"

FILES=$(find "$ROOT_DIR" \
    -name "._*" \
    -not -path "*/vendor/*" \
    -not -path "*/node_modules/*" \
    -not -path "*/.git/*" \
    -type f)

COUNT=$(echo "$FILES" | grep -c "^" 2>/dev/null || echo 0)

if [[ "$COUNT" -eq 0 ]]; then
    echo "No ._ files found. Clean!"
    exit 0
fi

echo "Found $COUNT ._ files"

if [[ "$DRY_RUN" == true ]]; then
    echo "$FILES" | head -20
    [[ "$COUNT" -gt 20 ]] && echo "... and $((COUNT - 20)) more"
    echo ""
    echo "Run without --dry-run to delete these files."
else
    echo "$FILES" | xargs rm -f
    echo "✅ Deleted $COUNT ._ files"
fi
