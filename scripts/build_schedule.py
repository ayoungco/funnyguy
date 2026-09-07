#!/usr/bin/env python3
"""
Turn the rendered shorts (make_short.py) into a dated posting queue:
which clip goes out on which day, in what order, with a suggested caption.

Doesn't touch any social platform -- posting itself stays manual (or
through each platform's own native scheduler, see docs/social_release_plan.md
for why). This just answers "what do I upload next, and what do I write."

Order: chronological by original comic_number. These are a 20+ year-old
webcomic archive, not disconnected gags -- releasing them in publication
order reads as "watch the series from the start," which is a stronger
hook than a shuffled grab-bag and costs nothing to do since the metadata's
already there.

Cadence: a fixed number of posts on fixed weekdays (default: Mon/Wed/Fri/Sun,
7pm), starting from the next occurrence of the first selected weekday.
Both are just defaults -- see docs/social_release_plan.md for the reasoning
and re-run with different --days/--time/--start once real engagement data
says otherwise.

Run with the ComfyUI venv's interpreter (has PIL, used only to read image
sizes... actually just stdlib + the funnyguycomics frontmatter):
    /home/adamyoung/src/ComfyUI/.venv/bin/python3 build_schedule.py

Input:  ../output/shorts/<slug>.mp4               (from make_short.py)
        ../output/panels/segmentation_manifest.csv (quality tier per comic)
        ../output/transcripts/<slug>.md            (dialogue hook line, optional)
        ../../funnyguycomics/content/comics/*/index.md (title/number/date)
Output: ../output/social_schedule.csv
"""
import argparse
import csv
import datetime
import re
import sys
from pathlib import Path

SCRIPT_DIR = Path(__file__).resolve().parent
REPO_ROOT = SCRIPT_DIR.parent
SHORTS_DIR = REPO_ROOT / "output" / "shorts"
SEGMENTATION_MANIFEST = REPO_ROOT / "output" / "panels" / "segmentation_manifest.csv"
TRANSCRIPTS_DIR = REPO_ROOT / "output" / "transcripts"
COMICS_MD_DIR = REPO_ROOT.parent / "funnyguycomics" / "content" / "comics"
OUT_PATH = REPO_ROOT / "output" / "social_schedule.csv"

WEEKDAYS = ["mon", "tue", "wed", "thu", "fri", "sat", "sun"]
DEFAULT_DAYS = ["mon", "wed", "fri", "sun"]
DEFAULT_TIME = "19:00"

HASHTAGS = "#funnyguy #webcomic #stickfigure #handdrawn #y2k #2000s #comics #ocarchive"

sys.path.insert(0, str(SCRIPT_DIR))
from make_short import load_captions, _panel_number  # reuse the same dialogue-extraction logic


def load_comic_metadata() -> dict[str, dict]:
    meta = {}
    for d in COMICS_MD_DIR.iterdir():
        idx = d / "index.md"
        if not idx.exists():
            continue
        text = idx.read_text(errors="ignore")
        title = (re.search(r'^title:\s*"(.*)"', text, re.M) or [None, ""])[1]
        num = (re.search(r"^comic_number:\s*(\d+)", text, re.M) or [None, None])[1]
        date = (re.search(r"^date:\s*([\d-]+)", text, re.M) or [None, ""])[1]
        for m_file in re.finditer(r'file:\s*"([^"]+)\.png"', text):
            meta[m_file.group(1)] = {
                "title": title,
                "comic_number": int(num) if num else None,
                "date": date,
            }
    return meta


def load_quality_tiers() -> dict[str, str]:
    with SEGMENTATION_MANIFEST.open() as f:
        return {row["comic"]: row["status"] for row in csv.DictReader(f)}


def hook_line(slug: str) -> str:
    """First captioned line in the clip, used as a caption teaser -- not
    the last one, so the on-platform caption doesn't spoil the punchline
    the clip itself is building to."""
    captions = load_captions(slug)
    for n in sorted(captions):
        if captions[n]:
            return captions[n].split(" / ")[0]
    return ""


def build_caption(slug: str, meta: dict) -> str:
    num = meta.get("comic_number")
    title = meta.get("title") or slug.replace("_", " ").title()
    header = f"Funny Guy #{num} — {title}" if num else title
    hook = hook_line(slug)
    parts = [header]
    if hook:
        parts.append(f'"{hook}"')
    parts.append(HASHTAGS)
    return "\n".join(parts)


def schedule_dates(n: int, days: list[str], time_str: str, start: datetime.date):
    day_idxs = sorted(WEEKDAYS.index(d) for d in days)
    week_start = start - datetime.timedelta(days=start.weekday())  # Monday of start's week
    out, week = [], 0
    while len(out) < n:
        for idx in day_idxs:
            candidate = week_start + datetime.timedelta(weeks=week, days=idx)
            if candidate >= start:
                out.append(candidate)
        week += 1
    out = sorted(out)[:n]
    return [datetime.datetime.combine(d, datetime.time.fromisoformat(time_str)) for d in out]


def main():
    parser = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument("--days", default=",".join(DEFAULT_DAYS), help=f"comma-separated weekdays, e.g. {','.join(DEFAULT_DAYS)}")
    parser.add_argument("--time", default=DEFAULT_TIME, help="HH:MM local post time")
    parser.add_argument("--start", default=None, help="YYYY-MM-DD to start from (default: today)")
    args = parser.parse_args()

    days = [d.strip().lower() for d in args.days.split(",")]
    for d in days:
        if d not in WEEKDAYS:
            print(f"unknown weekday {d!r} -- use {WEEKDAYS}", file=sys.stderr)
            sys.exit(1)

    shorts = sorted(p.stem for p in SHORTS_DIR.glob("*.mp4"))
    if not shorts:
        print(f"no rendered shorts in {SHORTS_DIR} -- run make_short.py first", file=sys.stderr)
        sys.exit(1)

    # Re-running this after a later make_short.py batch must not reshuffle
    # dates or wipe the manually-tracked `posted` column for slugs already
    # in the queue -- only genuinely new slugs get scheduled, appended
    # after whatever's already queued.
    existing_rows: dict[str, dict] = {}
    if OUT_PATH.exists():
        with OUT_PATH.open() as f:
            existing_rows = {row["slug"]: row for row in csv.DictReader(f)}

    new_slugs = [s for s in shorts if s not in existing_rows]

    meta_by_slug = load_comic_metadata()
    quality_by_slug = load_quality_tiers()

    def sort_key(slug):
        num = meta_by_slug.get(slug, {}).get("comic_number")
        return (num is None, num, slug)

    ordered_new = sorted(new_slugs, key=sort_key)

    if args.start:
        start = datetime.date.fromisoformat(args.start)
    elif existing_rows:
        last_dt = max(datetime.datetime.fromisoformat(r["post_datetime"]) for r in existing_rows.values())
        start = last_dt.date() + datetime.timedelta(days=1)
    else:
        start = datetime.date.today()

    dates = schedule_dates(len(ordered_new), days, args.time, start) if ordered_new else []

    new_rows = []
    for slug, when in zip(ordered_new, dates):
        meta = meta_by_slug.get(slug, {})
        new_rows.append({
            "post_datetime": when.isoformat(sep=" "),
            "slug": slug,
            "comic_number": meta.get("comic_number", ""),
            "title": meta.get("title", ""),
            "original_date": meta.get("date", ""),
            "quality_tier": quality_by_slug.get(slug, ""),
            "video_path": str((SHORTS_DIR / f"{slug}.mp4").resolve()),
            "posted": "",
            "suggested_caption": build_caption(slug, meta),
        })

    fieldnames = ["post_datetime", "slug", "comic_number", "title", "original_date", "quality_tier", "video_path", "posted", "suggested_caption"]
    all_rows = list(existing_rows.values()) + new_rows
    all_rows.sort(key=lambda r: r["post_datetime"])

    with OUT_PATH.open("w", newline="") as f:
        writer = csv.DictWriter(f, fieldnames=fieldnames)
        writer.writeheader()
        writer.writerows(all_rows)

    print(f"{len(new_rows)} new posts scheduled, {len(existing_rows)} already queued unchanged -- {len(all_rows)} total in {OUT_PATH}")


if __name__ == "__main__":
    main()
