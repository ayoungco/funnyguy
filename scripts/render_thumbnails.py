#!/usr/bin/env python3
"""
Render a small flattened PNG thumbnail for every legacy-dump asset listed
in ../references/asset_manifest.csv, so classify_assets.py has something
cheap and uniform to embed instead of re-decoding full PSDs/JPEGs (some
tens of MB each) over the CIFS mount on every run.

Run with the ComfyUI venv's interpreter (has PIL + psd-tools):
    /home/adamyoung/src/ComfyUI/.venv/bin/python3 render_thumbnails.py

Output: ../output/thumbnails/<sha1-of-source-path>.png, plus
../output/thumbnails/index.csv mapping source path -> thumbnail + status.
Re-running skips paths already present in index.csv.
"""
import argparse
import csv
import hashlib
import sys
import time
from pathlib import Path

from PIL import Image

Image.MAX_IMAGE_PIXELS = None  # legacy scans/PSDs can be huge; we downsize immediately

SCRIPT_DIR = Path(__file__).resolve().parent
REPO_ROOT = SCRIPT_DIR.parent
MANIFEST = REPO_ROOT / "references" / "asset_manifest.csv"
LEGACY_ROOT = Path("/mnt/creative/funnyguy")
OUT_DIR = REPO_ROOT / "output" / "thumbnails"
THUMB_SIZE = 384

RASTER_EXTS = {".png", ".jpg", ".jpeg", ".gif", ".bmp", ".webp"}
PSD_EXTS = {".psd"}


def thumb_path(rel_path: str) -> Path:
    h = hashlib.sha1(rel_path.encode()).hexdigest()
    return OUT_DIR / f"{h}.png"


def render_raster(src: Path, dst: Path):
    with Image.open(src) as im:
        im = im.convert("RGB")
        im.thumbnail((THUMB_SIZE, THUMB_SIZE))
        im.save(dst, "PNG")


def render_psd(src: Path, dst: Path):
    from psd_tools import PSDImage

    psd = PSDImage.open(src)
    im = psd.composite()
    if im is None:
        raise ValueError("empty composite")
    im = im.convert("RGB")
    im.thumbnail((THUMB_SIZE, THUMB_SIZE))
    im.save(dst, "PNG")


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--limit", type=int, default=None, help="only process the first N pending files")
    parser.add_argument("--skip-psd", action="store_true", help="skip slow PSD composite rendering")
    args = parser.parse_args()

    OUT_DIR.mkdir(parents=True, exist_ok=True)
    with MANIFEST.open() as f:
        rows = [r for r in csv.DictReader(f) if r["source"] == "legacy_dump"]
    rows = [r for r in rows if r["ext"] in RASTER_EXTS or (r["ext"] in PSD_EXTS and not args.skip_psd)]

    index_path = OUT_DIR / "index.csv"
    done = set()
    if index_path.exists():
        with index_path.open() as f:
            done = {r["path"] for r in csv.DictReader(f)}
    pending = [r for r in rows if r["path"] not in done]
    if args.limit:
        pending = pending[: args.limit]

    print(f"{len(rows)} candidates, {len(done)} already done, {len(pending)} to render", file=sys.stderr)

    mode = "a" if index_path.exists() else "w"
    n_ok = n_fail = 0
    t0 = time.time()
    with index_path.open(mode, newline="") as idxf:
        writer = csv.DictWriter(idxf, fieldnames=["path", "thumb", "status"])
        if mode == "w":
            writer.writeheader()
        for i, r in enumerate(pending):
            rel = r["path"]
            src = LEGACY_ROOT / rel
            dst = thumb_path(rel)
            try:
                if r["ext"] in RASTER_EXTS:
                    render_raster(src, dst)
                else:
                    render_psd(src, dst)
                writer.writerow({"path": rel, "thumb": dst.name, "status": "ok"})
                n_ok += 1
            except Exception as e:
                writer.writerow({"path": rel, "thumb": "", "status": f"error: {e}"})
                n_fail += 1
            if (i + 1) % 50 == 0:
                idxf.flush()
                elapsed = time.time() - t0
                print(f"[{i + 1}/{len(pending)}] ok={n_ok} fail={n_fail} elapsed={elapsed:.0f}s", file=sys.stderr)

    print(f"done: ok={n_ok} fail={n_fail}")


if __name__ == "__main__":
    main()
