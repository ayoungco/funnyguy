#!/usr/bin/env python3
"""Add legacy-URL aliases (/N/ and /N/PAGE/) to each comic's front matter.

Idempotent: replaces any existing `aliases:` block. Source of truth for page
numbers is data/comics.json (from the original fgcomics.sql).
"""
import json
import re
import sys
from pathlib import Path

root = Path(__file__).resolve().parent.parent
rows = json.loads((root / "data/comics.json").read_text())

pages = {}
for r in rows:
    n = r["comic_number"]
    pages.setdefault(n, [])
    if r["page_number"] is not None:
        pages[n].append(r["page_number"])

changed = 0
for md in sorted((root / "content/comics").glob("*/index.md")):
    n = int(md.parent.name.split("-", 1)[0])
    aliases = [f"/{n}/"] + [f"/{n}/{p}/" for p in sorted(set(pages.get(n, [])))]
    text = md.read_text()
    m = re.match(r"---\n(.*?)\n---\n", text, re.S)
    if not m:
        sys.exit(f"no front matter: {md}")
    fm = re.sub(r"^aliases:\n(?:  - .*\n?)+", "", m.group(1) + "\n", flags=re.M).rstrip("\n")
    block = "aliases:\n" + "".join(f'  - "{a}"\n' for a in aliases)
    # keep `pages:` last so the list stays readable
    new = f"---\n{fm}\n{block.rstrip()}\n---\n" + text[m.end():]
    if new != text:
        md.write_text(new)
        changed += 1
print(f"updated {changed} files")
