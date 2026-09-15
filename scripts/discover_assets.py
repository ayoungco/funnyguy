#!/usr/bin/env python3
"""
Walk the three Funny Guy source locations and write a flat manifest of what
exists, so prompt/reference work doesn't require the NAS mount to be online
or a human to re-browse 21GB of legacy files each time.

Sources (see ../README.md):
  - ../../funnyguycomics   (Hugo archive, original webcomic)
  - ../../funnyguyrpg      (RPG Maker 2000/2003 game)
  - /mnt/creative/funnyguy   (legacy raw dump, read-only)

Usage:
    python3 discover_assets.py [--out ../references/asset_manifest.csv]

Only collects metadata (path, size, mtime, extension, origin guess) — never
hashes or opens files, since the legacy dump is a slow CIFS mount.
"""
import argparse
import csv
import os
import sys
from pathlib import Path

SCRIPT_DIR = Path(__file__).resolve().parent
REPO_ROOT = SCRIPT_DIR.parent

SOURCES = {
    "funnyguycomics": REPO_ROOT.parent / "funnyguycomics",
    "funnyguyrpg": REPO_ROOT.parent / "funnyguyrpg",
    "legacy_dump": Path("/mnt/creative/funnyguy"),
}

# Legacy-dump top-level dirs that are backups/junk/unrelated, not source
# material worth cataloging for generation reference.
NOISE_DIRS = {"Trash", "Website Backups", "Playthrough", "@Recycle"}

# RPG Maker stock-resource filename prefixes seen in CharSet/Battle/etc —
# these ship with RPG Maker and are NOT Funny Guy-original art. See
# ../references/style.md.
STOCK_PREFIXES = ("DH-", "FX-")

# Hand-picked canonical files called out in ../references/characters.md —
# the closest thing to "official" art for the cast in each style.
CANONICAL_PATHS = {
    "funnyguycomics/static/comics/001_the_funny_guy.png",
    "funnyguyrpg/CharSet/fg2.png",
    "funnyguyrpg/CharSet/FG-GrayCharas.png",
    "funnyguyrpg/FaceSet/fgs.png",
}

ASSET_EXTS = {
    ".png", ".jpg", ".jpeg", ".gif", ".bmp", ".webp",
    ".psd", ".ai", ".svg", ".pdf",
}


def origin_for(source: str, rel_path: str, name: str) -> str:
    if f"{source}/{rel_path}" in CANONICAL_PATHS:
        return "canonical"
    if source == "funnyguycomics":
        return "original-comic"
    if source == "funnyguyrpg":
        if name.startswith(STOCK_PREFIXES):
            return "stock-rpgmaker"
        return "original-rpg"
    if source == "legacy_dump":
        return "legacy-unsorted"
    return "unknown"


def iter_files(source: str, root: Path):
    for dirpath, dirnames, filenames in os.walk(root):
        rel_dir = Path(dirpath).relative_to(root)
        if source == "legacy_dump":
            # prune noise dirs at the top level
            if rel_dir == Path("."):
                dirnames[:] = [d for d in dirnames if d not in NOISE_DIRS]
        for name in filenames:
            full = Path(dirpath) / name
            rel = (rel_dir / name).as_posix() if rel_dir != Path(".") else name
            yield full, rel, name


def build_manifest(only_assets: bool):
    rows = []
    for source, root in SOURCES.items():
        if not root.is_dir():
            print(f"warning: {source} root not found or not mounted: {root}", file=sys.stderr)
            continue
        for full, rel, name in iter_files(source, root):
            ext = Path(name).suffix.lower()
            if only_assets and ext not in ASSET_EXTS:
                continue
            try:
                st = full.stat()
            except OSError as e:
                print(f"warning: could not stat {full}: {e}", file=sys.stderr)
                continue
            rows.append({
                "source": source,
                "path": rel,
                "ext": ext,
                "size_bytes": st.st_size,
                "mtime": int(st.st_mtime),
                "origin": origin_for(source, rel, name),
            })
    rows.sort(key=lambda r: (r["source"], r["path"]))
    return rows


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument(
        "--out",
        default=str(REPO_ROOT / "references" / "asset_manifest.csv"),
        help="output CSV path (default: ../references/asset_manifest.csv)",
    )
    parser.add_argument(
        "--all-files",
        action="store_true",
        help="include non-image/design files too (default: image/design assets only)",
    )
    args = parser.parse_args()

    rows = build_manifest(only_assets=not args.all_files)

    out_path = Path(args.out)
    out_path.parent.mkdir(parents=True, exist_ok=True)
    with out_path.open("w", newline="") as f:
        writer = csv.DictWriter(f, fieldnames=["source", "path", "ext", "size_bytes", "mtime", "origin"])
        writer.writeheader()
        writer.writerows(rows)

    by_source = {}
    for r in rows:
        by_source[r["source"]] = by_source.get(r["source"], 0) + 1
    print(f"wrote {len(rows)} rows to {out_path}")
    for source, count in by_source.items():
        print(f"  {source}: {count}")


if __name__ == "__main__":
    main()
