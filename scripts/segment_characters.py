#!/usr/bin/env python3
"""
Crop individual characters/objects out of segmented panels, as a first step
toward a master character "model sheet" -- since everything in this archive
was drawn from memory each time rather than traced from a reference, the
goal isn't one canonical drawing per character but a contact sheet of every
instance found, so the actual drift is visible.

Validated on a 3-comic sample before running at corpus scale -- see
docs/progress.md for the prompt-format fixes that took (a plain prose
description of the bbox format had a ~50% failure rate; a few-shot example
with clearly-fake, varied coordinates fixed it -- an early version of the
example anchored the model on the example's own numbers instead).

Uses the same local qwen2.5vl:7b model as describe_panels.py (already
running via Ollama, GPU-accelerated), but prompted for bounding boxes
instead of a description. Empirically confirmed (see docs/progress.md)
that this model returns coordinates on a 0-1000 normalized scale relative
to the image as shown, NOT raw pixels of the original file -- this script
converts accordingly.

Batches over every segmented comic by default, same resume-friendly
pattern as describe_panels.py -- skips comics already in the manifest
unless --force. Checkpoints the manifest after each comic, not just at
the end, since a full-corpus run is many hours.

Run with plain system python3 (stdlib only for the HTTP/JSON/crop parts) --
needs Pillow, so use a venv that has it if system python3 doesn't:
    python3 segment_characters.py                    # every segmented comic
    python3 segment_characters.py --comic 001_the_funny_guy
    python3 segment_characters.py --comics 001_the_funny_guy,002_foulness

Input:  ../output/panels/<slug>/panel_NN.png        (from segment_panels.py)
        ../output/panels/segmentation_manifest.csv  (to find eligible comics)
Output: ../output/character_crops/<slug>/panel_NN_<i>_<name>.png
        ../output/character_crops/manifest.csv
"""
import argparse
import base64
import csv
import json
import re
import sys
import time
import urllib.error
import urllib.request
from pathlib import Path

from PIL import Image, ImageStat

SCRIPT_DIR = Path(__file__).resolve().parent
REPO_ROOT = SCRIPT_DIR.parent
PANELS_DIR = REPO_ROOT / "output" / "panels"
SEGMENTATION_MANIFEST = PANELS_DIR / "segmentation_manifest.csv"
OUT_DIR = REPO_ROOT / "output" / "character_crops"
MANIFEST_FIELDS = ["slug", "panel", "label", "bbox_px", "crop_path", "px_stddev"]

OLLAMA_URL = "http://localhost:11434/api/generate"
MODEL = "qwen2.5vl:7b"
REQUEST_TIMEOUT = 180
MAX_RETRIES = 3

# Known cast, from references/characters.md + references/story_bible.md --
# giving the model real names to anchor on produces more useful labels than
# open-ended detection, per describe_panels.py's own experience with named
# labels. UNKNOWN covers unlabeled figures and non-cast objects/props.
KNOWN_CAST = [
    "Funny Guy", "Koven", "Repair Man", "Serious Man", "Junior", "Target",
    "Metal Funny Guy", "The Evil Scientist", "Bad Guy", "Sir Disaprine",
]

# A first version of this prompt (just describing the desired "NAME:
# [x1,y1,x2,y2]" format in prose) had a ~50% failure rate: the model would
# sometimes output "NAME: Funny Guy" literally, or list quoted dialogue
# text as if it were a character. Adding a concrete few-shot example fixed
# format compliance, but the *first* attempt at that reused suspiciously
# identical coordinates from the example for a second real figure in the
# panel -- classic small-model anchoring on example numbers rather than
# re-deriving real ones. Fixed by using varied, clearly-fictional example
# coordinates and an explicit "don't reuse these numbers" instruction, then
# verified by actually cropping and looking at the result (a real, correctly
# isolated "Funny Guy" crop with his handwritten label visible) -- not just
# trusting that the output format looked plausible. See docs/progress.md.
PROMPT = (
    "This is one panel from a crude, hand-drawn stick-figure webcomic. "
    "Find every distinct character or notable prop/object DRAWN as a "
    "picture in this panel -- do not include speech-bubble text or "
    "captions as items, only actual drawn figures/objects. "
    "Known recurring character names in this comic: "
    + ", ".join(KNOWN_CAST) + ". "
    "If a figure has a handwritten name label near it, or clearly matches "
    "one of these known characters by appearance, use that name. "
    "Otherwise use UNKNOWN for an unidentified figure, or a short "
    "lowercase noun for a notable object/prop (e.g. 'boombox', 'sign'). "
    "The example numbers below are illustrative only -- always compute "
    "fresh coordinates from what you actually see in THIS image, never "
    "reuse the example numbers.\n\n"
    "Respond with ONLY lines in exactly this format (replace the "
    "placeholders, keep the brackets and commas):\n"
    "<name>: [<x1>,<y1>,<x2>,<y2>]\n\n"
    "Example of a correctly formatted response for a DIFFERENT panel with "
    "three figures:\n"
    "Funny Guy: [50,600,300,950]\n"
    "Koven: [640,120,900,500]\n"
    "boombox: [700,850,830,940]\n\n"
    "Now do the same for the actual image. Do not output anything else -- "
    "no explanation, no markdown, no numbered list."
)

BOX_RE = re.compile(r"^\s*([^:\[\]]+?)\s*:\s*\[\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\]", re.M)

PAD_FRAC = 0.04  # small padding around each detected box, as a fraction of panel size


def query_ollama(img_path: Path) -> str:
    b64 = base64.b64encode(img_path.read_bytes()).decode()
    payload = {"model": MODEL, "prompt": PROMPT, "images": [b64], "stream": False}
    req = urllib.request.Request(
        OLLAMA_URL, data=json.dumps(payload).encode(), headers={"Content-Type": "application/json"}
    )
    last_err = None
    for attempt in range(1, MAX_RETRIES + 1):
        try:
            with urllib.request.urlopen(req, timeout=REQUEST_TIMEOUT) as resp:
                return json.loads(resp.read())["response"]
        except (urllib.error.URLError, TimeoutError, KeyError, json.JSONDecodeError) as e:
            last_err = e
            if attempt < MAX_RETRIES:
                time.sleep(2)
    raise RuntimeError(f"ollama request failed after {MAX_RETRIES} attempts: {last_err}")


def slugify(name: str) -> str:
    return re.sub(r"[^a-z0-9]+", "_", name.strip().lower()).strip("_") or "unknown"


def process_panel(panel_path: Path, out_dir: Path) -> list[dict]:
    im = Image.open(panel_path).convert("RGB")
    w, h = im.size
    response = query_ollama(panel_path)

    rows = []
    for i, m in enumerate(BOX_RE.finditer(response)):
        name = m.group(1).strip()
        x1n, y1n, x2n, y2n = (int(g) for g in m.groups()[1:])
        x1, y1, x2, y2 = x1n / 1000 * w, y1n / 1000 * h, x2n / 1000 * w, y2n / 1000 * h
        if x2 <= x1 or y2 <= y1:
            continue
        pad_x, pad_y = (x2 - x1) * PAD_FRAC, (y2 - y1) * PAD_FRAC
        # Clamp both bounds, not just the lower one -- an out-of-range model
        # coordinate (e.g. an x1n/y1n above the documented 0-1000 scale) can
        # put x1/y1 past w/h while x2/y2 get correctly clamped down to w/h,
        # inverting the box and crashing im.crop() (hit in the overnight run,
        # see docs/progress.md). Re-check degeneracy after clamping since
        # clamping itself can collapse a box that looked valid before it.
        x1, x2 = max(0, min(w, x1 - pad_x)), max(0, min(w, x2 + pad_x))
        y1, y2 = max(0, min(h, y1 - pad_y)), max(0, min(h, y2 + pad_y))
        if x2 <= x1 or y2 <= y1:
            continue

        crop = im.crop((int(x1), int(y1), int(x2), int(y2)))
        out_dir.mkdir(parents=True, exist_ok=True)
        crop_name = f"{panel_path.stem}_{i}_{slugify(name)}.png"
        crop.save(out_dir / crop_name)
        # Grayscale pixel stddev as a cheap blank/junk-crop signal -- same
        # idea embed_panels.py's CLIP pass used for whole panels (116
        # near-blank crops flagged via low pixel variance, see
        # docs/progress.md), just without needing CLIP/torch for a plain
        # crop-level check. Not filtered here, just recorded -- a human (or
        # a later pass) decides the cutoff, same as legacy_triage.csv's
        # canon_similarity column.
        stddev = ImageStat.Stat(crop.convert("L")).stddev[0]
        rows.append({
            "slug": panel_path.parent.name,
            "panel": panel_path.stem,
            "label": name,
            "bbox_px": f"{int(x1)},{int(y1)},{int(x2)},{int(y2)}",
            "crop_path": str((out_dir / crop_name).relative_to(REPO_ROOT)),
            "px_stddev": f"{stddev:.1f}",
        })
    return rows


def eligible_slugs() -> list[str]:
    """Every comic segment_panels.py actually produced panels for -- same
    eligibility rule describe_panels.py/make_short.py use."""
    if not SEGMENTATION_MANIFEST.exists():
        print(f"no segmentation manifest at {SEGMENTATION_MANIFEST} -- run segment_panels.py first", file=sys.stderr)
        sys.exit(1)
    with SEGMENTATION_MANIFEST.open() as f:
        return [row["comic"] for row in csv.DictReader(f) if row["status"] in ("ok", "ok_low_confidence")]


def load_existing_manifest(manifest_path: Path) -> tuple[list[dict], set[str]]:
    """Returns (rows, slugs-already-done) so an interrupted overnight run can
    resume instead of restarting from scratch or duplicating work."""
    if not manifest_path.exists():
        return [], set()
    with manifest_path.open() as f:
        rows = list(csv.DictReader(f))
    return rows, {row["slug"] for row in rows}


def main():
    parser = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument("--comic", default=None, help="process a single comic by slug")
    parser.add_argument("--comics", default=None, help="comma-separated list of slugs (default: every segmented comic)")
    parser.add_argument("--limit", type=int, default=None, help="cap total panels processed")
    parser.add_argument("--force", action="store_true", help="reprocess comics that already have crops in the manifest")
    args = parser.parse_args()

    if args.comics:
        slugs = args.comics.split(",")
    elif args.comic:
        slugs = [args.comic]
    else:
        slugs = eligible_slugs()

    OUT_DIR.mkdir(parents=True, exist_ok=True)
    manifest_path = OUT_DIR / "manifest.csv"
    manifest_rows, done_slugs = load_existing_manifest(manifest_path)
    if not args.force:
        skipped = [s for s in slugs if s in done_slugs]
        slugs = [s for s in slugs if s not in done_slugs]
        if skipped:
            print(f"skipping {len(skipped)} comics already in manifest (use --force to redo)", file=sys.stderr)

    def flush_manifest():
        with manifest_path.open("w", newline="") as f:
            writer = csv.DictWriter(f, fieldnames=MANIFEST_FIELDS)
            writer.writeheader()
            writer.writerows(manifest_rows)

    n_panels = 0
    t0 = time.time()
    for slug in slugs:
        comic_dir = PANELS_DIR / slug
        panels = sorted(comic_dir.glob("panel_*.png"))
        if not panels:
            print(f"no panels found for {slug!r} in {comic_dir}", file=sys.stderr)
            continue
        for panel_path in panels:
            if args.limit and n_panels >= args.limit:
                flush_manifest()
                print(f"hit --limit {args.limit}, stopping", file=sys.stderr)
                return
            try:
                rows = process_panel(panel_path, OUT_DIR / slug)
            except RuntimeError as e:
                print(f"  {slug}/{panel_path.name}: FAILED ({e}), skipping", file=sys.stderr)
                rows = []
            manifest_rows.extend(rows)
            n_panels += 1
            print(f"[{n_panels}] {slug}/{panel_path.name}: {len(rows)} crops", file=sys.stderr)
        flush_manifest()  # per-comic checkpoint -- a 17hr+ run shouldn't lose everything to one crash

    print(f"done: {n_panels} panels, {len(manifest_rows)} total crops in {time.time() - t0:.0f}s, manifest at {manifest_path}")


if __name__ == "__main__":
    main()
