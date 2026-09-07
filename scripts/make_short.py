#!/usr/bin/env python3
"""
Cut a comic's segmented panels (from segment_panels.py) down to a short,
vertical (9:16) clip for TikTok/Instagram Reels/YouTube Shorts, instead of
`make_slideshow.py`'s full-length square slideshow.

Three changes from make_slideshow.py:
  1. Vertical 1080x1920 canvas instead of square 1080x1080.
  2. Only a handful of panels, not the whole comic -- these strips build to
     one punchline, so a full 6-20 panel slideshow already runs 15-45s of
     mostly setup. Default: keep the first panel (establishes the joke) and
     the last few (the punchline / "The End" beat), drop the middle. Faster
     pacing than the slideshow too, since short-form wants snappier cuts.
  3. Burns in the panel's dialogue as a caption card, pulled from
     describe_panels.py's transcript (the quoted speech-bubble text it
     already transcribed) -- most shorts get watched muted, and the
     hand-written lettering that reads fine on a full-page slideshow is too
     small to read at a glance in this cropped, scroll-past format.

Panel selection is a plain position-based heuristic, not semantic -- it
doesn't know which panel is actually the punchline, just assumes it's near
the end. A future version could use describe_panels.py's descriptions to
pick panels by content instead.

Batches over every comic with segmented panels by default, same pattern as
describe_panels.py -- skips comics that already have a short unless
--force. Needs a transcript from describe_panels.py for captions, but
runs (silently caption-free) without one.

Run with the ComfyUI venv's interpreter (has PIL):
    /home/adamyoung/src/ComfyUI/.venv/bin/python3 make_short.py
    /home/adamyoung/src/ComfyUI/.venv/bin/python3 make_short.py --comic 001_the_funny_guy

Input:  ../output/panels/<slug>/panel_NN.png        (from segment_panels.py)
        ../output/panels/segmentation_manifest.csv  (to find eligible comics)
        ../output/transcripts/<slug>.md             (from describe_panels.py, optional)
Output: ../output/shorts/<slug>.mp4
"""
import argparse
import csv
import re
import shutil
import subprocess
import sys
import tempfile
import textwrap
from pathlib import Path

from PIL import Image, ImageDraw, ImageFont

SCRIPT_DIR = Path(__file__).resolve().parent
REPO_ROOT = SCRIPT_DIR.parent
PANELS_DIR = REPO_ROOT / "output" / "panels"
SEGMENTATION_MANIFEST = PANELS_DIR / "segmentation_manifest.csv"
TRANSCRIPTS_DIR = REPO_ROOT / "output" / "transcripts"
OUT_DIR = REPO_ROOT / "output" / "shorts"

CANVAS = (1080, 1920)  # 9:16 vertical
PANEL_SECONDS = 1.1
XFADE_SECONDS = 0.25
ZOOM_END = 1.08
FPS = 30
KEEP_FIRST = 1
KEEP_LAST = 3
MAX_CAPTION_CHARS = 180

FONT_PATH = "/usr/share/fonts/liberation-sans-fonts/LiberationSans-Bold.ttf"
CAPTION_FONT_SIZE = 46


def eligible_slugs():
    if not SEGMENTATION_MANIFEST.exists():
        print(f"no segmentation manifest at {SEGMENTATION_MANIFEST} -- run segment_panels.py first", file=sys.stderr)
        sys.exit(1)
    with SEGMENTATION_MANIFEST.open() as f:
        return [row["comic"] for row in csv.DictReader(f) if row["status"] in ("ok", "ok_low_confidence")]


def select_panels(panels: list[Path], keep_first: int, keep_last: int) -> list[Path]:
    if keep_first + keep_last >= len(panels):
        return panels
    return panels[:keep_first] + panels[-keep_last:]


DIALOGUE_CONTEXT_WORDS = (
    "says", "say", "said", "asks", "ask", "asked", "reads", "read",
    "saying", "responding", "response", "replies", "reply", "shouts",
    "shout", "yells", "yell", "bubble", "text", "caption", "written",
    "writes", "states", "state", "exclaims", "exclaim",
)


def _extract_dialogue_quotes(body: str) -> list[str]:
    """describe_panels.py's prompt asks the VLM for both a free-text
    description and a verbatim quote of any dialogue in one flowing
    response, so quoted spans in the output are a mix of actual dialogue
    ("Ash is dumb.") and quoted character-name labels ("Funny Guy") --
    e.g. '...a stick figure labeled "Funny Guy"... has a thought bubble
    that says "Ash is dumb."' has both in one sentence. Only the ones
    preceded by a speech/text verb within a short window are dialogue;
    plain 'labeled "X"' mentions are names, not something to caption."""
    quotes = []
    for m in re.finditer(r'"([^"]{1,200})"', body):
        context = body[max(0, m.start() - 45): m.start()].lower()
        if any(w in context for w in DIALOGUE_CONTEXT_WORDS):
            quotes.append(m.group(1).strip())
    return quotes


def load_captions(slug: str) -> dict[int, str]:
    """Pulls the speech-bubble/handwritten text describe_panels.py already
    transcribed per panel, keyed by panel number. Panels with no dialogue
    (or no transcript yet) just get no caption -- the drawing speaks for
    itself in that case."""
    path = TRANSCRIPTS_DIR / f"{slug}.md"
    if not path.exists():
        return {}
    text = path.read_text()
    captions = {}
    for m in re.finditer(r"\*\*Panel (\d+)\.\*\*\s*(.*?)(?=\n\*\*Panel \d+\.\*\*|\Z)", text, re.S):
        n = int(m.group(1))
        quotes = _extract_dialogue_quotes(m.group(2))
        seen, deduped = set(), []
        for q in quotes:
            key = q.lower().rstrip(".!? ")
            if q and key not in seen:
                seen.add(key)
                deduped.append(q)
        caption = " / ".join(deduped)
        if len(caption) > MAX_CAPTION_CHARS:
            caption = caption[: MAX_CAPTION_CHARS - 1].rsplit(" ", 1)[0] + "…"
        captions[n] = caption
    return captions


def _panel_number(path: Path) -> int:
    return int(path.stem.split("_")[-1])


def _sample_paper_color(im: Image.Image) -> tuple[int, int, int]:
    """Average color along the panel's border -- these are near-white
    notebook paper, so this gives a background tint that matches the page
    instead of a jarring pure white or black."""
    w, h = im.size
    px = im.load()
    step_x, step_y = max(1, w // 30), max(1, h // 30)
    pts = [(x, 0) for x in range(0, w, step_x)] + [(x, h - 1) for x in range(0, w, step_x)]
    pts += [(0, y) for y in range(0, h, step_y)] + [(w - 1, y) for y in range(0, h, step_y)]
    samples = [px[x, y] for x, y in pts]
    return tuple(sum(c[i] for c in samples) // len(samples) for i in range(3))


def _draw_caption(canvas: Image.Image, caption: str, below_y: int):
    """Caption card in the space below the panel: dark rounded pill, bold
    white text -- legible regardless of the tinted card background behind
    it, same convention as TikTok/Reels burned-in captions."""
    if not caption:
        return
    font = ImageFont.truetype(FONT_PATH, CAPTION_FONT_SIZE)
    draw = ImageDraw.Draw(canvas)
    max_text_width = int(CANVAS[0] * 0.86)
    avg_char_w = font.getlength("n") or 1
    wrap_width = max(10, int(max_text_width / avg_char_w))
    lines = textwrap.wrap(caption, width=wrap_width) or [caption]

    line_height = CAPTION_FONT_SIZE + 14
    text_h = line_height * len(lines)
    pad_x, pad_y = 36, 24
    box_w = max(font.getlength(line) for line in lines) + pad_x * 2
    box_h = text_h + pad_y * 2
    box_top = below_y + (CANVAS[1] - below_y - box_h) // 2
    box_left = (CANVAS[0] - box_w) // 2

    draw.rounded_rectangle(
        [box_left, box_top, box_left + box_w, box_top + box_h],
        radius=24, fill=(20, 20, 20),
    )
    y = box_top + pad_y
    for line in lines:
        w = font.getlength(line)
        draw.text((box_left + (box_w - w) / 2, y), line, font=font, fill=(255, 255, 255))
        y += line_height


def letterbox(src: Path, dst: Path, caption: str):
    """Fit the panel into the vertical canvas without shrinking it to a
    postage stamp: these are landscape-ish panels going into a 9:16 frame,
    so plain white letterboxing (make_slideshow.py's approach, fine for its
    square canvas) would leave most of the screen blank. A blurred,
    cropped-to-cover copy of the same panel as background was tried first,
    but panels are landscape and the canvas is portrait, so "cover" means a
    ~6-7x crop-and-zoom -- it blows up pencil texture into an unrecognizable,
    faintly unsettling blob instead of a background. Using a plain tinted
    card (color sampled from the panel's own paper) avoids that and stays
    closer to the source material's plain-notebook-paper look. Leftover
    space below the card holds the caption, if there is one.
    """
    im = Image.open(src).convert("RGB")
    paper = _sample_paper_color(im)
    bg_color = tuple(max(0, c - 35) for c in paper)

    fg = im.copy()
    fg.thumbnail((int(CANVAS[0] * 0.92), int(CANVAS[1] * 0.55)), Image.LANCZOS)
    x = (CANVAS[0] - fg.width) // 2
    y = int(CANVAS[1] * 0.38) - fg.height // 2

    canvas = Image.new("RGB", CANVAS, bg_color)
    pad = 14
    draw = ImageDraw.Draw(canvas)
    draw.rectangle([x - pad, y - pad, x + fg.width + pad, y + fg.height + pad], fill=paper, outline=(50, 50, 50), width=3)
    canvas.paste(fg, (x, y))

    _draw_caption(canvas, caption, below_y=y + fg.height + pad)
    canvas.save(dst, "PNG")


def build_video(slug: str, keep_first: int, keep_last: int, panel_seconds: float, xfade_seconds: float) -> bool:
    comic_dir = PANELS_DIR / slug
    all_panels = sorted(comic_dir.glob("panel_*.png"))
    if not all_panels:
        return False

    panels = select_panels(all_panels, keep_first, keep_last)
    captions = load_captions(slug)

    OUT_DIR.mkdir(parents=True, exist_ok=True)
    out_path = OUT_DIR / f"{slug}.mp4"

    with tempfile.TemporaryDirectory() as tmp:
        tmp = Path(tmp)
        norm_paths = []
        for i, p in enumerate(panels):
            dst = tmp / f"n{i:03d}.png"
            letterbox(p, dst, captions.get(_panel_number(p), ""))
            norm_paths.append(dst)

        clip_dur = panel_seconds + xfade_seconds
        n_frames = int(clip_dur * FPS)

        inputs = []
        for p in norm_paths:
            inputs += ["-loop", "1", "-t", f"{clip_dur:.3f}", "-i", str(p)]

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
            # nvenc intermittently fails to init when contending with other GPU
            # work (e.g. the describe_panels.py VLM batch) -- retry once.
            result = subprocess.run(cmd, capture_output=True, text=True)
        if result.returncode != 0:
            cmd[cmd.index("h264_nvenc")] = "libx264"
            result = subprocess.run(cmd, capture_output=True, text=True)
            if result.returncode != 0:
                print(result.stderr[-4000:], file=sys.stderr)
                return False

    n_captioned = sum(1 for p in panels if captions.get(_panel_number(p)))
    print(f"wrote {out_path} ({len(panels)}/{len(all_panels)} panels kept, {n_captioned} captioned)", file=sys.stderr)
    return True


def main():
    parser = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument("--comic", default=None, help="process a single comic by slug (default: every segmented comic)")
    parser.add_argument("--limit", type=int, default=None, help="only process the first N comics")
    parser.add_argument("--force", action="store_true", help="re-render comics that already have a short")
    parser.add_argument("--keep-first", type=int, default=KEEP_FIRST)
    parser.add_argument("--keep-last", type=int, default=KEEP_LAST)
    parser.add_argument("--panel-seconds", type=float, default=PANEL_SECONDS)
    parser.add_argument("--xfade-seconds", type=float, default=XFADE_SECONDS)
    args = parser.parse_args()

    if shutil.which("ffmpeg") is None:
        print("ffmpeg not found on PATH", file=sys.stderr)
        sys.exit(1)

    slugs = eligible_slugs()
    if args.comic:
        slugs = [s for s in slugs if s == args.comic]
        if not slugs:
            print(f"no eligible (segmented) comic found matching slug {args.comic!r}", file=sys.stderr)
            sys.exit(1)
    if not args.force:
        slugs = [s for s in slugs if not (OUT_DIR / f"{s}.mp4").exists()]
    if args.limit:
        slugs = slugs[: args.limit]

    if not slugs:
        print("nothing to do -- all eligible comics already have shorts (use --force to redo)")
        return

    n_ok = n_fail = 0
    for i, slug in enumerate(slugs):
        ok = build_video(slug, args.keep_first, args.keep_last, args.panel_seconds, args.xfade_seconds)
        print(f"[{i + 1}/{len(slugs)}] {slug}: {'ok' if ok else 'FAILED'}", file=sys.stderr)
        n_ok += ok
        n_fail += not ok

    print(f"done: {n_ok} ok, {n_fail} failed, output in {OUT_DIR}")


if __name__ == "__main__":
    main()
