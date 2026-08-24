#!/usr/bin/env python3
"""
GPU triage pass over the legacy dump: run CLIP (local, on the RTX 3060)
over every thumbnail from render_thumbnails.py and produce, per image:

  - `category` / `category_score` — zero-shot match against a fixed set
    of content/style labels (comic panel, RPG sprite, sketch, scanned
    text, logo/UI, unrelated photo, digital painting).
  - `canon_match` / `canon_similarity` — which hand-picked canonical
    Funny Guy reference (see CANONICAL below, sourced from
    ../references/characters.md) the image's CLIP embedding is closest
    to, as a proxy for "looks like established character art."

This replaces eyeballing 1800+ files one at a time: sort the output by
canon_similarity and the top of the list is where a human should look
first for usable character reference material.

Run with the ComfyUI venv's interpreter (has torch+CUDA and transformers):
    /home/adamyoung/src/ComfyUI/.venv/bin/python3 classify_assets.py

Input:  ../output/thumbnails/index.csv (from render_thumbnails.py)
Output: ../references/legacy_triage.csv
"""
import csv
import sys
from pathlib import Path

import torch
from PIL import Image
from transformers import CLIPModel, CLIPProcessor

SCRIPT_DIR = Path(__file__).resolve().parent
REPO_ROOT = SCRIPT_DIR.parent
THUMB_INDEX = REPO_ROOT / "output" / "thumbnails" / "index.csv"
THUMB_DIR = REPO_ROOT / "output" / "thumbnails"
OUT_CSV = REPO_ROOT / "references" / "legacy_triage.csv"

MODEL_NAME = "openai/clip-vit-base-patch32"
BATCH_SIZE = 32

# Hand-picked "closest thing to official art" files from ../references/characters.md
CANONICAL = {
    "funnyguycomics/static/comics/001_the_funny_guy.png":
        REPO_ROOT.parent / "funnyguycomics" / "static" / "comics" / "001_the_funny_guy.png",
    "funnyguyrpg/CharSet/fg2.png":
        REPO_ROOT.parent / "funnyguyrpg" / "CharSet" / "fg2.png",
    "funnyguyrpg/CharSet/FG-GrayCharas.png":
        REPO_ROOT.parent / "funnyguyrpg" / "CharSet" / "FG-GrayCharas.png",
    "funnyguyrpg/FaceSet/fgs.png":
        REPO_ROOT.parent / "funnyguyrpg" / "FaceSet" / "fgs.png",
}

CATEGORY_LABELS = [
    "a crude hand-drawn stick-figure comic strip panel",
    "a 16-bit pixel art RPG character sprite sheet",
    "a pencil or pen sketch of a cartoon character",
    "a scanned page of handwritten or typed text",
    "a logo or user-interface graphic design",
    "an unrelated photograph, like a product or real-world object",
    "a digital painting or illustration",
]


def embed_images(model, processor, device, images):
    inputs = processor(images=images, return_tensors="pt").to(device)
    with torch.no_grad():
        # this transformers version returns BaseModelOutputWithPooling; the
        # projected embedding is in .pooler_output, not the return value itself
        feats = model.get_image_features(**inputs).pooler_output
    return feats / feats.norm(dim=-1, keepdim=True)


def embed_text(model, processor, device, texts):
    inputs = processor(text=texts, return_tensors="pt", padding=True).to(device)
    with torch.no_grad():
        feats = model.get_text_features(**inputs).pooler_output
    return feats / feats.norm(dim=-1, keepdim=True)


def main():
    device = "cuda" if torch.cuda.is_available() else "cpu"
    print(f"device: {device}", file=sys.stderr)

    model = CLIPModel.from_pretrained(MODEL_NAME).to(device).eval()
    processor = CLIPProcessor.from_pretrained(MODEL_NAME)

    canon_paths, canon_images = [], []
    for name, p in CANONICAL.items():
        if p.is_file():
            canon_paths.append(name)
            canon_images.append(Image.open(p).convert("RGB"))
        else:
            print(f"warning: canonical file missing, skipping: {p}", file=sys.stderr)
    canon_feats = embed_images(model, processor, device, canon_images) if canon_images else None
    text_feats = embed_text(model, processor, device, CATEGORY_LABELS)

    with THUMB_INDEX.open() as f:
        rows = [r for r in csv.DictReader(f) if r["status"] == "ok"]
    print(f"{len(rows)} thumbnails to classify", file=sys.stderr)

    out_rows = []
    for i in range(0, len(rows), BATCH_SIZE):
        batch = rows[i : i + BATCH_SIZE]
        images, valid = [], []
        for r in batch:
            try:
                images.append(Image.open(THUMB_DIR / r["thumb"]).convert("RGB"))
                valid.append(r)
            except Exception as e:
                print(f"warning: could not open thumb for {r['path']}: {e}", file=sys.stderr)
        if not images:
            continue

        img_feats = embed_images(model, processor, device, images)
        cat_sims = img_feats @ text_feats.T
        cat_best = cat_sims.argmax(dim=-1)

        if canon_feats is not None:
            canon_sims = img_feats @ canon_feats.T
            canon_best = canon_sims.argmax(dim=-1)

        for j, r in enumerate(valid):
            row = {
                "path": r["path"],
                "category": CATEGORY_LABELS[cat_best[j]],
                "category_score": round(float(cat_sims[j, cat_best[j]]), 4),
                "canon_match": canon_paths[canon_best[j]] if canon_feats is not None else "",
                "canon_similarity": round(float(canon_sims[j, canon_best[j]]), 4) if canon_feats is not None else "",
            }
            out_rows.append(row)

        if (i // BATCH_SIZE) % 5 == 0:
            print(f"[{min(i + BATCH_SIZE, len(rows))}/{len(rows)}]", file=sys.stderr)

    out_rows.sort(key=lambda r: -(r["canon_similarity"] or 0))
    with OUT_CSV.open("w", newline="") as f:
        writer = csv.DictWriter(
            f, fieldnames=["path", "category", "category_score", "canon_match", "canon_similarity"]
        )
        writer.writeheader()
        writer.writerows(out_rows)
    print(f"wrote {len(out_rows)} rows to {OUT_CSV}")


if __name__ == "__main__":
    main()
