#!/usr/bin/env python3
"""
Turn one comic's segmented panels (from segment_panels.py) into a rough
animated-short-style slideshow video: each panel held for a beat, gentle
zoom-in, crossfade between panels.

Panels within a comic have different aspect ratios (row heights/column
widths drift across a hand-drawn grid), so each is first letterboxed onto
a common white canvas — same background color as the notebook-paper
panels, so the padding is close to invisible — before ffmpeg assembles
them.

Run with the ComfyUI venv's interpreter (has PIL) — ffmpeg itself is a
system binary, no venv needed for it:
    /home/adamyoung/src/ComfyUI/.venv/bin/python3 make_slideshow.py <slug>

Input:  ../output/panels/<slug>/panel_NN.png  (from segment_panels.py)
Output: ../output/videos/<slug>.mp4
"""
import argparse
import shutil
import subprocess
import sys
import tempfile
from pathlib import Path

from PIL import Image

SCRIPT_DIR = Path(__file__).resolve().parent
REPO_ROOT = SCRIPT_DIR.parent
PANELS_DIR = REPO_ROOT / "output" / "panels"
OUT_DIR = REPO_ROOT / "output" / "videos"

CANVAS = (1080, 1080)
PANEL_SECONDS = 2.0
XFADE_SECONDS = 0.4
ZOOM_END = 1.06  # gentle zoom-in over each panel's hold time
FPS = 30


def letterbox(src: Path, dst: Path):
    im = Image.open(src).convert("RGB")
    im.thumbnail(CANVAS, Image.LANCZOS)
    canvas = Image.new("RGB", CANVAS, (255, 255, 255))
    x = (CANVAS[0] - im.width) // 2
    y = (CANVAS[1] - im.height) // 2
    canvas.paste(im, (x, y))
    canvas.save(dst, "PNG")


def build_video(slug: str, panel_seconds: float, xfade_seconds: float):
    comic_dir = PANELS_DIR / slug
    panels = sorted(comic_dir.glob("panel_*.png"))
    if not panels:
        print(f"no panels found for {slug!r} in {comic_dir}", file=sys.stderr)
        sys.exit(1)

    OUT_DIR.mkdir(parents=True, exist_ok=True)
    out_path = OUT_DIR / f"{slug}.mp4"

    with tempfile.TemporaryDirectory() as tmp:
        tmp = Path(tmp)
        norm_paths = []
        for i, p in enumerate(panels):
            dst = tmp / f"n{i:03d}.png"
            letterbox(p, dst)
            norm_paths.append(dst)

        clip_dur = panel_seconds + xfade_seconds
        n_frames = int(clip_dur * FPS)

        inputs = []
        for p in norm_paths:
            inputs += ["-loop", "1", "-t", f"{clip_dur:.3f}", "-i", str(p)]

        # per-clip: gentle zoom-in via zoompan, then xfade-chain them together
        filter_parts = []
        for i in range(len(norm_paths)):
            filter_parts.append(
                f"[{i}:v]scale={CANVAS[0]}:{CANVAS[1]},"
                f"zoompan=z='min(1+({ZOOM_END}-1)*on/{n_frames},{ZOOM_END})':"
                f"d=1:s={CANVAS[0]}x{CANVAS[1]},"
                f"fps={FPS},format=yuv420p[v{i}]"
            )

        chain = "[v0]"
        offset = clip_dur - xfade_seconds
        for i in range(1, len(norm_paths)):
            out_label = f"[x{i}]" if i < len(norm_paths) - 1 else "[vout]"
            filter_parts.append(
                f"{chain}[v{i}]xfade=transition=fade:duration={xfade_seconds:.3f}:offset={offset:.3f}{out_label}"
            )
            chain = out_label
            offset += clip_dur - xfade_seconds

        filter_complex = ";".join(filter_parts)

        cmd = [
            "ffmpeg", "-y", "-nostdin",
            *inputs,
            "-filter_complex", filter_complex,
            "-map", "[vout]",
            "-c:v", "h264_nvenc", "-pix_fmt", "yuv420p",
            str(out_path),
        ]
        result = subprocess.run(cmd, capture_output=True, text=True)
        if result.returncode != 0:
            # fall back to CPU encode if nvenc isn't usable in this environment
            cmd[cmd.index("h264_nvenc")] = "libx264"
            result = subprocess.run(cmd, capture_output=True, text=True)
            if result.returncode != 0:
                print(result.stderr[-4000:], file=sys.stderr)
                sys.exit(1)

    print(f"wrote {out_path} ({len(panels)} panels)")


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("slug", help="comic slug — subdirectory name under ../output/panels/")
    parser.add_argument("--panel-seconds", type=float, default=PANEL_SECONDS)
    parser.add_argument("--xfade-seconds", type=float, default=XFADE_SECONDS)
    args = parser.parse_args()

    if shutil.which("ffmpeg") is None:
        print("ffmpeg not found on PATH", file=sys.stderr)
        sys.exit(1)

    build_video(args.slug, args.panel_seconds, args.xfade_seconds)


if __name__ == "__main__":
    main()
