#!/usr/bin/env python3
"""
Batch SDXL generation against the style templates in ../prompts/funny_guy.yaml,
using the diffusers library directly on the local GPU rather than a ComfyUI
server.

Why diffusers here instead of ComfyUI: ../../stable-diffusion-xl-base-1.0
is checked out in native HuggingFace diffusers layout (model_index.json +
unet/text_encoder/vae/... subfolders) — diffusers loads that as-is.
ComfyUI's checkpoint loader wants a single merged .safetensors in the
original SD state-dict key naming, which this isn't; converting would be
extra fragile machinery just to run a batch script. ../workflows/ stays
for interactive ComfyUI sessions if that's wanted later — this script is
the actual generation pipeline for now.

Run with the ComfyUI venv's interpreter (torch+CUDA, diffusers, accelerate):
    /home/adamyoung/src/ComfyUI/.venv/bin/python3 generate_sdxl.py \
        --style comic_style --n 8 --seed-start 0

Output: ../output/generated/<style>/seed<N>.png
        ../output/generated/manifest.csv  (append-only, one row per image —
        seed/steps/cfg/prompt used, so any image can be reproduced exactly)
"""
import argparse
import csv
import sys
from pathlib import Path

import torch
import yaml
from diffusers import StableDiffusionXLPipeline

SCRIPT_DIR = Path(__file__).resolve().parent
REPO_ROOT = SCRIPT_DIR.parent
CHECKPOINT = REPO_ROOT.parent / "stable-diffusion-xl-base-1.0"
PROMPTS_FILE = REPO_ROOT / "prompts" / "funny_guy.yaml"
OUT_DIR = REPO_ROOT / "output" / "generated"

DEFAULT_STEPS = 30
DEFAULT_CFG = 7.0
DEFAULT_SIZE = 1024

MANIFEST_FIELDS = ["style", "seed", "steps", "cfg", "size", "path", "positive", "negative"]


def load_pipe():
    pipe = StableDiffusionXLPipeline.from_pretrained(
        CHECKPOINT, torch_dtype=torch.float16, variant="fp16", use_safetensors=True,
    )
    pipe = pipe.to("cuda")
    pipe.set_progress_bar_config(disable=True)
    return pipe


def main():
    parser = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument("--style", required=True, help="key under prompts/funny_guy.yaml, e.g. comic_style")
    parser.add_argument("--n", type=int, default=4, help="number of seeds to generate")
    parser.add_argument("--seed-start", type=int, default=0)
    parser.add_argument("--steps", type=int, default=DEFAULT_STEPS)
    parser.add_argument("--cfg", type=float, default=DEFAULT_CFG)
    parser.add_argument("--size", type=int, default=DEFAULT_SIZE)
    args = parser.parse_args()

    prompts = yaml.safe_load(PROMPTS_FILE.read_text())
    if args.style not in prompts:
        print(f"unknown style {args.style!r}; available: {list(prompts)}", file=sys.stderr)
        sys.exit(1)
    spec = prompts[args.style]
    positive = " ".join(spec["positive"].split())
    negative = " ".join(spec["negative"].split())

    style_dir = OUT_DIR / args.style
    style_dir.mkdir(parents=True, exist_ok=True)
    manifest_path = OUT_DIR / "manifest.csv"
    write_header = not manifest_path.exists()

    print(f"loading SDXL pipeline from {CHECKPOINT} ...", file=sys.stderr)
    pipe = load_pipe()
    print("loaded, generating...", file=sys.stderr)

    with manifest_path.open("a", newline="") as mf:
        writer = csv.DictWriter(mf, fieldnames=MANIFEST_FIELDS)
        if write_header:
            writer.writeheader()
        for i in range(args.n):
            seed = args.seed_start + i
            gen = torch.Generator(device="cuda").manual_seed(seed)
            image = pipe(
                prompt=positive,
                negative_prompt=negative,
                num_inference_steps=args.steps,
                guidance_scale=args.cfg,
                width=args.size,
                height=args.size,
                generator=gen,
            ).images[0]
            out_path = style_dir / f"seed{seed:05d}.png"
            image.save(out_path)
            writer.writerow({
                "style": args.style,
                "seed": seed,
                "steps": args.steps,
                "cfg": args.cfg,
                "size": args.size,
                "path": str(out_path.relative_to(REPO_ROOT)),
                "positive": positive,
                "negative": negative,
            })
            mf.flush()
            print(f"[{i + 1}/{args.n}] seed={seed} -> {out_path}", file=sys.stderr)


if __name__ == "__main__":
    main()
