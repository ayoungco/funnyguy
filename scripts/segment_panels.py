#!/usr/bin/env python3
"""
Split a Funny Guy comic page into its individual panels, so downstream
tools (make_slideshow.py, prompt/reference work) can operate on one panel
at a time instead of a whole page.

The grid isn't uniform across 20+ years of hand-drawn pages (see
../references/style.md) — panel counts, row heights, and column widths
all drift, and some pages merge cells irregularly. So instead of assuming
a fixed N-column layout, this recursively finds actual drawn border lines
(rows/columns where the fraction of dark pixels crosses a threshold) and
cuts on those — a "guillotine" panel split, same idea used by manga/comic
panel-extraction tools. It tries a few thresholds per page (lower ones
catch fainter/thinner pencil borders) and keeps the first one that yields
a plausible split.

Known limitation: an ornate title/logo box sharing row 1 with real panels
can get fragmented into extra junk crops, since its internal linework can
look like more border cuts. Not worth solving generally — this is a rough
pipeline, not a production one; see the per-comic threshold in the
manifest to spot pages worth a manual look.

Run with the ComfyUI venv's interpreter (has numpy + PIL):
    /home/adamyoung/src/ComfyUI/.venv/bin/python3 segment_panels.py

Input:  ../../funnyguycomics/static/comics/*.png
Output: ../output/panels/<slug>/panel_NN.png
        ../output/panels/segmentation_manifest.csv
"""
import argparse
import csv
import sys
from pathlib import Path

import numpy as np
from PIL import Image

SCRIPT_DIR = Path(__file__).resolve().parent
REPO_ROOT = SCRIPT_DIR.parent
COMICS_DIR = REPO_ROOT.parent / "funnyguycomics" / "static" / "comics"
OUT_DIR = REPO_ROOT / "output" / "panels"

THRESHOLDS = (0.85, 0.6, 0.4)
MIN_SIZE = 40  # px — regions smaller than this in either dimension become leaf panels
INSET = 3  # px trimmed off each panel edge so the border line itself isn't included


def _runs(idxs, gap=3):
    if len(idxs) == 0:
        return []
    out = []
    start = prev = idxs[0]
    for i in idxs[1:]:
        if i - prev > gap:
            out.append((start, prev))
            start = i
        prev = i
    out.append((start, prev))
    return out


def _find_lines(frac, thresh):
    return _runs(np.where(frac > thresh)[0])


def _segment_region(dark, x0, y0, x1, y1, thresh, depth=0):
    w, h = x1 - x0, y1 - y0
    if w < MIN_SIZE or h < MIN_SIZE or depth > 8:
        return [(x0, y0, x1, y1)]

    row_frac = dark[y0:y1, x0:x1].mean(axis=1)
    row_runs = _find_lines(row_frac, thresh)
    if len(row_runs) >= 2:
        mids = [y0 + (a + b) // 2 for a, b in row_runs]
        bounds = [y0] + mids + [y1]
        bands = [(bounds[i], bounds[i + 1]) for i in range(len(bounds) - 1) if bounds[i + 1] - bounds[i] >= MIN_SIZE]
        if len(bands) >= 2:
            out = []
            for b0, b1 in bands:
                out.extend(_segment_region(dark, x0, b0, x1, b1, thresh, depth + 1))
            return out

    col_frac = dark[y0:y1, x0:x1].mean(axis=0)
    col_runs = _find_lines(col_frac, thresh)
    if len(col_runs) >= 2:
        mids = [x0 + (a + b) // 2 for a, b in col_runs]
        bounds = [x0] + mids + [x1]
        bands = [(bounds[i], bounds[i + 1]) for i in range(len(bounds) - 1) if bounds[i + 1] - bounds[i] >= MIN_SIZE]
        if len(bands) >= 2:
            out = []
            for b0, b1 in bands:
                out.extend(_segment_region(dark, b0, y0, b1, y1, thresh, depth + 1))
            return out

    return [(x0, y0, x1, y1)]


def segment_comic(path: Path):
    """Returns (threshold_used, grid_box, panels) or (None, None, []) on failure."""
    im = Image.open(path).convert("L")
    arr = np.array(im)
    h, w = arr.shape
    dark = arr < 128

    for thresh in THRESHOLDS:
        row_runs = _find_lines(dark.mean(axis=1), thresh)
        if len(row_runs) < 2:
            continue
        grid_top = row_runs[0][0] + (row_runs[0][1] - row_runs[0][0]) // 2
        grid_bottom = row_runs[-1][0] + (row_runs[-1][1] - row_runs[-1][0]) // 2

        col_runs = _find_lines(dark[grid_top:grid_bottom, :].mean(axis=0), thresh)
        grid_left = col_runs[0][0] + (col_runs[0][1] - col_runs[0][0]) // 2 if col_runs else 0
        grid_right = col_runs[-1][0] + (col_runs[-1][1] - col_runs[-1][0]) // 2 if col_runs else w

        panels = _segment_region(dark, grid_left, grid_top, grid_right, grid_bottom, thresh)
        if len(panels) >= 2:
            return thresh, (grid_left, grid_top, grid_right, grid_bottom), panels

    return None, None, []


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--limit", type=int, default=None, help="only process the first N comics")
    parser.add_argument("--comic", default=None, help="process a single comic by filename stem")
    args = parser.parse_args()

    OUT_DIR.mkdir(parents=True, exist_ok=True)
    comics = sorted(COMICS_DIR.glob("*.png"))
    if args.comic:
        comics = [c for c in comics if c.stem == args.comic]
        if not comics:
            print(f"no comic found matching stem {args.comic!r}", file=sys.stderr)
            sys.exit(1)
    if args.limit:
        comics = comics[: args.limit]

    manifest_rows = []
    for i, path in enumerate(comics):
        slug = path.stem
        thresh, grid, panels = segment_comic(path)
        comic_dir = OUT_DIR / slug
        if thresh is None:
            manifest_rows.append({"comic": slug, "n_panels": 0, "threshold": "", "status": "failed"})
            print(f"[{i + 1}/{len(comics)}] {slug}: FAILED to find any grid lines", file=sys.stderr)
            continue

        comic_dir.mkdir(parents=True, exist_ok=True)
        im = Image.open(path).convert("RGB")
        for n, (x0, y0, x1, y1) in enumerate(panels, start=1):
            crop = im.crop((x0 + INSET, y0 + INSET, x1 - INSET, y1 - INSET))
            crop.save(comic_dir / f"panel_{n:02d}.png")

        status = "ok" if thresh == THRESHOLDS[0] else "ok_low_confidence"
        manifest_rows.append({"comic": slug, "n_panels": len(panels), "threshold": thresh, "status": status})
        print(f"[{i + 1}/{len(comics)}] {slug}: {len(panels)} panels (thresh={thresh})", file=sys.stderr)

    with (OUT_DIR / "segmentation_manifest.csv").open("w", newline="") as f:
        writer = csv.DictWriter(f, fieldnames=["comic", "n_panels", "threshold", "status"])
        writer.writeheader()
        writer.writerows(manifest_rows)
    print(f"wrote manifest for {len(manifest_rows)} comics to {OUT_DIR / 'segmentation_manifest.csv'}")


if __name__ == "__main__":
    main()
