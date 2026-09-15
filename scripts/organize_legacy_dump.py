#!/usr/bin/env python3
"""
Sort the ~690 loose files sitting at the top level of the legacy dump
(/mnt/creative/funnyguy) into folders, so the directory is
browsable again. The dump already has ~19 subfolders (Art, Books, Comics,
Covers, etc.) from earlier manual organizing; this only touches files
currently at the top level and either files into an existing folder when
the match is obvious, or creates a small number of new ones.

Classification is done by filename keyword, in priority order (first
match wins) — see RULES below — with an extension-based fallback for
document/audio/archive types, and anything left over goes to
Unsorted/<ext>/. Nothing is guessed from file *content*, since that would
require opening 21GB of PSDs; ambiguous names land in Unsorted rather
than a guessed bucket.

Two-phase by design:
  1. Dry run (default): writes the full (src, dst) mapping to
     ../references/legacy_reorg_plan.csv and prints a per-destination
     summary. Moves nothing.
  2. --execute: replays that CSV with shutil.move (no-clobber — a name
     collision is skipped and logged, never overwritten) and writes
     ../references/legacy_reorg_undo.csv alongside it, which --undo
     replays in reverse.

The dump is a CIFS mount usually owned root:root 755; if the invoking
user can't write to it, --execute fails immediately on a mkdir with a
clear permission error rather than partway through the move.

Usage:
    python3 organize_legacy_dump.py                 # dry run, writes plan CSV
    python3 organize_legacy_dump.py --execute        # perform the moves
    python3 organize_legacy_dump.py --undo           # reverse a previous --execute
"""
import argparse
import csv
import re
import shutil
import sys
from pathlib import Path

SCRIPT_DIR = Path(__file__).resolve().parent
REPO_ROOT = SCRIPT_DIR.parent
LEGACY_ROOT = Path("/mnt/creative/funnyguy")
PLAN_CSV = REPO_ROOT / "references" / "legacy_reorg_plan.csv"
UNDO_CSV = REPO_ROOT / "references" / "legacy_reorg_undo.csv"

# Keyword rules, checked in order against the lowercased filename.
# First match wins. Destination is relative to LEGACY_ROOT.
KEYWORD_RULES = [
    ("cover", "Covers"),
    ("fg-", "RPG Assets"),
    ("fgrpg", "RPG Assets"),
    ("rpg_rt", "RPG Assets"),
    ("title screen", "RPG Assets"),
    ("charset", "RPG Assets"),
    ("chipset", "RPG Assets"),
    ("battlesystem", "RPG Assets"),
    ("fg_system", "RPG Assets"),
    ("install_", "RPG Assets"),
    ("nav", "Website"),
    ("header", "Website"),
    ("footer", "Website"),
    ("favicon", "Website"),
    ("banner", "Website"),
    ("myspace", "Website"),
    ("splashscreen", "Website"),
    ("menububble", "Website"),
    ("userbar", "Website"),
    ("fgcomics.com", "Website"),
    ("logo", "Logos"),
    ("book", "Books"),
    ("fgcomix", "Comics"),
    ("funnyguycomix", "Comics"),
    ("comic", "Comics"),
    ("battle", "Screenshots"),  # after "battlesystem"/"cover" so RPG art wins first
]
# Filenames starting with these (post-lowercasing, stripped) go to Screenshots.
SCREENSHOT_PREFIXES = ("capture",)

EXTENSION_RULES = {
    ".mp3": "Audio",
    ".zip": "Archives",
    ".doc": "Documents", ".docx": "Documents", ".odt": "Documents",
    ".pdf": "Documents", ".txt": "Documents", ".eml": "Documents",
    ".pub": "Documents", ".textclipping": "Documents", ".md": "Documents",
    ".xls": "Documents", ".sql": "Documents",
}

UNSORTED = "Unsorted"


def _contains_word(lower: str, keyword: str) -> bool:
    # Plain substring matching lets "book" fire on "facebook"/"macbook"/
    # "notebook" and "cover" fire on "discover". Require the keyword not
    # be preceded by another letter. No trailing check: several keywords
    # are intentionally prefixes ("fg-" in "FG-BattleCharSet", "comic" in
    # "comics"/"fgcomics"), so a following letter must stay allowed.
    pattern = r"(?<![a-z])" + re.escape(keyword)
    return re.search(pattern, lower) is not None


def classify(name: str) -> str:
    lower = name.lower()
    if lower.startswith(SCREENSHOT_PREFIXES):
        return "Screenshots"
    for keyword, dest in KEYWORD_RULES:
        if _contains_word(lower, keyword):
            return dest
    ext = Path(name).suffix.lower()
    if ext in EXTENSION_RULES:
        return EXTENSION_RULES[ext]
    if ext:
        return f"{UNSORTED}/{ext.lstrip('.')}"
    return f"{UNSORTED}/no_ext"


def build_plan():
    if not LEGACY_ROOT.is_dir():
        print(f"error: legacy dump not found or not mounted: {LEGACY_ROOT}", file=sys.stderr)
        sys.exit(1)
    rows = []
    for entry in sorted(LEGACY_ROOT.iterdir()):
        if not entry.is_file():
            continue  # existing subfolders untouched
        dest_dir = classify(entry.name)
        rows.append({"name": entry.name, "dest_dir": dest_dir})
    return rows


def write_plan(rows):
    PLAN_CSV.parent.mkdir(parents=True, exist_ok=True)
    with PLAN_CSV.open("w", newline="") as f:
        writer = csv.DictWriter(f, fieldnames=["name", "dest_dir"])
        writer.writeheader()
        writer.writerows(rows)
    print(f"wrote {len(rows)} rows to {PLAN_CSV}")

    counts = {}
    for r in rows:
        counts[r["dest_dir"]] = counts.get(r["dest_dir"], 0) + 1
    print(f"\n{len(counts)} destinations:")
    for dest, count in sorted(counts.items(), key=lambda kv: -kv[1]):
        print(f"  {count:4d}  {dest}")


def execute(rows):
    undo_rows = []
    moved, skipped = 0, 0
    for r in rows:
        src = LEGACY_ROOT / r["name"]
        dest_dir = LEGACY_ROOT / r["dest_dir"]
        dest = dest_dir / r["name"]
        try:
            dest_dir.mkdir(parents=True, exist_ok=True)
        except OSError as e:
            print(f"error: cannot create {dest_dir}: {e}", file=sys.stderr)
            sys.exit(1)
        if dest.exists():
            print(f"skip (destination exists): {r['name']} -> {r['dest_dir']}/", file=sys.stderr)
            skipped += 1
            continue
        if not src.exists():
            print(f"skip (source vanished): {r['name']}", file=sys.stderr)
            skipped += 1
            continue
        shutil.move(str(src), str(dest))
        undo_rows.append({"name": r["name"], "dest_dir": r["dest_dir"]})
        moved += 1

    with UNDO_CSV.open("w", newline="") as f:
        writer = csv.DictWriter(f, fieldnames=["name", "dest_dir"])
        writer.writeheader()
        writer.writerows(undo_rows)
    print(f"\nmoved {moved}, skipped {skipped}")
    print(f"undo manifest: {UNDO_CSV}")


def undo():
    if not UNDO_CSV.is_file():
        print(f"error: no undo manifest at {UNDO_CSV}", file=sys.stderr)
        sys.exit(1)
    with UNDO_CSV.open() as f:
        rows = list(csv.DictReader(f))
    restored, skipped = 0, 0
    for r in rows:
        src = LEGACY_ROOT / r["dest_dir"] / r["name"]
        dest = LEGACY_ROOT / r["name"]
        if not src.exists():
            print(f"skip (not found): {r['dest_dir']}/{r['name']}", file=sys.stderr)
            skipped += 1
            continue
        if dest.exists():
            print(f"skip (top-level name reappeared): {r['name']}", file=sys.stderr)
            skipped += 1
            continue
        shutil.move(str(src), str(dest))
        restored += 1
    print(f"restored {restored}, skipped {skipped}")


def main():
    parser = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument("--execute", action="store_true", help="perform the moves from a fresh plan")
    parser.add_argument("--undo", action="store_true", help="reverse the last --execute using the undo manifest")
    args = parser.parse_args()

    if args.undo:
        undo()
        return

    rows = build_plan()
    write_plan(rows)
    if args.execute:
        execute(rows)
    else:
        print("\ndry run only — nothing moved. Review the CSV, then re-run with --execute.")


if __name__ == "__main__":
    main()
