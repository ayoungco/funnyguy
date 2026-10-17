#!/usr/bin/env bash
# Build www.fgcomics.com: the Hugo comics site plus the browser-playable
# FGRPG at /game/play/.
#
# Usage: scripts/build-site.sh [OUT]   (default: repo/funnyguycomics/public)
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
OUT="$(realpath -m "${1:-$ROOT/repo/funnyguycomics/public}")"

hugo --minify --cleanDestinationDir --source "$ROOT/repo/funnyguycomics" --destination "$OUT"
DIST_DIR="$OUT/game/play" "$ROOT/repo/funnyguyrpg/scripts/build-web.sh" >/dev/null
echo "built $OUT ($(find "$OUT" -type f | wc -l) files)"
