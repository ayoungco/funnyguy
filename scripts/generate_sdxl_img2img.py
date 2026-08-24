#!/usr/bin/env python3
"""
img2img SDXL generation: start from a real Funny Guy reference image
(the `reference_image` in ../prompts/funny_guy.yaml) instead of noise, so
output keeps the actual character design/composition while SDXL restyles
it. Plain text-to-image (generate_sdxl.py) drifted off-model in testing —
a polished "coloring book" illustration instead of a crude stick figure,
a grid of generic anime sprites instead of one consistent character —
this is the fix ../references/style.md already called for.

Run with the ComfyUI venv's interpreter:
    /home/adamyoung/src/ComfyUI/.venv/bin/python3 generate_sdxl_img2img.py \
        --style comic_style --n 4 --strength 0.6

--strength controls how much the output can diverge from the init image:
0.0 = unchanged init image, 1.0 = same as pure txt2img (init ignored).
num_inference_steps is the step count as if starting from full noise;
the actual number of denoising steps run is roughly steps * strength, so
--steps is set higher by default than generate_sdxl.py to compensate.

Output: ../output/generated_img2img/<style>/seed<N>_s<strength>.png
        ../output/generated_img2img/manifest.csv
"""
import argparse
import csv
import sys
from pathlib import Path

import torch
import yaml
from diffusers import StableDiffusionXLImg2ImgPipeline
from PIL import Image

SCRIPT_DIR = Path(__file__).resolve().parent
REPO_ROOT = SCRIPT_DIR.parent
CHECKPOINT = REPO_ROOT.parent / "stable-diffusion-xl-base-1.0"
PROMPTS_FILE = REPO_ROOT / "prompts" / "funny_guy.yaml"
OUT_DIR = REPO_ROOT / "output" / "generated_img2img"

DEFAULT_STEPS = 40
DEFAULT_CFG = 7.0
DEFAULT_STRENGTH = 0.6
DEFAULT_SIZE = 1024

MANIFEST_FIELDS = ["style", "seed", "strength", "steps", "cfg", "size", "init_image", "path", "positive", "negative"]


def load_pipe():
    pipe = StableDiffusionXLImg2ImgPipeline.from_pretrained(
        CHECKPOINT, torch_dtype=torch.float16, variant="fp16", use_safetensors=True,
    ).to("cuda")
    pipe.set_progress_bar_config(disable=True)
    return pipe


def main():
    parser = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument("--style", required=True, help="key under prompts/funny_guy.yaml, e.g. comic_style")
    parser.add_argument("--init-image", default=None, help="override the YAML's reference_image path")
    parser.add_argument("--n", type=int, default=4)
    parser.add_argument("--seed-start", type=int, default=0)
    parser.add_argument("--strength", type=float, default=DEFAULT_STRENGTH)
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

    init_path = Path(args.init_image) if args.init_image else (PROMPTS_FILE.parent / spec["reference_image"]).resolve()
    if not init_path.is_file():
        print(f"init image not found: {init_path}", file=sys.stderr)
        sys.exit(1)
    init_image = Image.open(init_path).convert("RGB").resize((args.size, args.size), Image.LANCZOS)

    style_dir = OUT_DIR / args.style
    style_dir.mkdir(parents=True, exist_ok=True)
    manifest_path = OUT_DIR / "manifest.csv"
    write_header = not manifest_path.exists()

    print(f"loading SDXL img2img pipeline from {CHECKPOINT} ...", file=sys.stderr)
    pipe = load_pipe()
    print(f"init image: {init_path}", file=sys.stderr)

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
                image=init_image,
                strength=args.strength,
                num_inference_steps=args.steps,
                guidance_scale=args.cfg,
                generator=gen,
            ).images[0]
            out_path = style_dir / f"seed{seed:05d}_s{args.strength:.2f}.png"
            image.save(out_path)
            writer.writerow({
                "style": args.style,
                "seed": seed,
                "strength": args.strength,
                "steps": args.steps,
                "cfg": args.cfg,
                "size": args.size,
                "init_image": str(init_path),
                "path": str(out_path.relative_to(REPO_ROOT)),
                "positive": positive,
                "negative": negative,
            })
            mf.flush()
            print(f"[{i + 1}/{args.n}] seed={seed} -> {out_path}", file=sys.stderr)


if __name__ == "__main__":
    main()
