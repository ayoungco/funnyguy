#!/usr/bin/env python3
"""
CLIP-embed every segmented panel to build a visual index of the whole
archive -- a "greater picture" pass that doesn't depend on the VLM's text
descriptions (describe_panels.py) at all, just raw visual similarity.
Answers questions text descriptions can't: which panels are near-visual-
duplicates (reused poses/layouts), which ones are outliers for a given
comic's era, whether art style drifted gradually or in discrete jumps
across 20+ years -- and gives a numeric feature per panel that later work
(smarter punchline-panel selection, "find similar panel" tooling for
remix work) can build on instead of re-deriving from scratch.

Model: openai/clip-vit-base-patch32 via `transformers` -- already cached
locally (~/.cache/huggingface), no download needed. Runs on GPU if
available, falls back to CPU.

Run with the ComfyUI venv's interpreter (has transformers + torch):
    /home/adamyoung/src/ComfyUI/.venv/bin/python3 embed_panels.py

Input:  ../output/panels/<slug>/panel_NN.png  (from segment_panels.py)
Output: ../output/embeddings/clip_vit_b32.npy   (float32, N x 512)
        ../output/embeddings/clip_vit_b32_index.csv  (row i -> slug, panel_num)
"""
import csv
import sys
import time
from pathlib import Path

import numpy as np
import torch
from PIL import Image
from transformers import CLIPModel, CLIPProcessor

SCRIPT_DIR = Path(__file__).resolve().parent
REPO_ROOT = SCRIPT_DIR.parent
PANELS_DIR = REPO_ROOT / "output" / "panels"
OUT_DIR = REPO_ROOT / "output" / "embeddings"

MODEL_NAME = "openai/clip-vit-base-patch32"
BATCH_SIZE = 64


def main():
    device = "cuda" if torch.cuda.is_available() else "cpu"
    print(f"loading {MODEL_NAME} on {device}...", file=sys.stderr)
    model = CLIPModel.from_pretrained(MODEL_NAME).to(device).eval()
    processor = CLIPProcessor.from_pretrained(MODEL_NAME)

    panel_paths = sorted(PANELS_DIR.glob("*/panel_*.png"))
    if not panel_paths:
        print(f"no panels found under {PANELS_DIR} -- run segment_panels.py first", file=sys.stderr)
        sys.exit(1)
    print(f"embedding {len(panel_paths)} panels...", file=sys.stderr)

    OUT_DIR.mkdir(parents=True, exist_ok=True)
    all_embeds = []
    index_rows = []
    t0 = time.time()

    for i in range(0, len(panel_paths), BATCH_SIZE):
        batch_paths = panel_paths[i : i + BATCH_SIZE]
        images = [Image.open(p).convert("RGB") for p in batch_paths]
        inputs = processor(images=images, return_tensors="pt").to(device)
        with torch.no_grad():
            # this transformers version wraps the projected embedding in a
            # BaseModelOutputWithPooling instead of returning it directly --
            # .pooler_output here is the actual (already-projected) 512-dim
            # image embedding, not the raw vision-tower pooler output the
            # name suggests. Confirmed via shape (512 == config.projection_dim).
            feats = model.get_image_features(**inputs).pooler_output
            feats = feats / feats.norm(dim=-1, keepdim=True)
        all_embeds.append(feats.cpu().numpy().astype(np.float32))
        for p in batch_paths:
            index_rows.append({"slug": p.parent.name, "panel": p.stem, "path": str(p.relative_to(REPO_ROOT))})
        done = min(i + BATCH_SIZE, len(panel_paths))
        print(f"  {done}/{len(panel_paths)} ({time.time() - t0:.0f}s)", file=sys.stderr)

    embeds = np.concatenate(all_embeds, axis=0)
    np.save(OUT_DIR / "clip_vit_b32.npy", embeds)
    with (OUT_DIR / "clip_vit_b32_index.csv").open("w", newline="") as f:
        writer = csv.DictWriter(f, fieldnames=["slug", "panel", "path"])
        writer.writeheader()
        writer.writerows(index_rows)

    print(f"wrote {embeds.shape} embeddings to {OUT_DIR} in {time.time() - t0:.0f}s")


if __name__ == "__main__":
    main()
