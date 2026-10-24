#!/usr/bin/env python3
"""Flatten every PSD under a directory to a PNG preview and write a contact sheet.

Usage: psd_contact_sheet.py SRC_DIR OUT_DIR
Writes OUT_DIR/previews/*.png, OUT_DIR/index.tsv (path, size, blank?) and
OUT_DIR/index.html. Uses ImageMagick's flattened composite only; PSDs whose
composite is blank are flagged for layer extraction. Output stays out of the repo.
"""
import html, os, subprocess, sys

src, out = sys.argv[1], sys.argv[2]
prev = os.path.join(out, "previews"); os.makedirs(prev, exist_ok=True)
rows = []
for root, _, files in os.walk(src):
    for f in sorted(files):
        if not f.lower().endswith(".psd"):
            continue
        p = os.path.join(root, f); rel = os.path.relpath(p, src)
        png = rel.replace(os.sep, "__") + ".png"
        dest = os.path.join(prev, png)
        r = subprocess.run(["magick", p + "[0]", "-background", "white", "-flatten", "-resize", "400x400>", dest],
                           capture_output=True, text=True)
        if r.returncode:
            rows.append((rel, "", "ERR", None)); continue
        info = subprocess.run(["magick", "identify", "-format", "%wx%h", p + "[0]"], capture_output=True, text=True).stdout
        std = subprocess.run(["magick", dest, "-format", "%[fx:standard_deviation]", "info:"], capture_output=True, text=True).stdout
        rows.append((rel, info, "blank" if float(std or 0) < 0.003 else "ok", png))
with open(os.path.join(out, "index.tsv"), "w") as fh:
    fh.write("path\tsize\tstatus\n")
    for r in rows: fh.write("\t".join(map(str, r[:3])) + "\n")
with open(os.path.join(out, "index.html"), "w") as fh:
    fh.write("<meta charset=utf-8><style>body{font:12px sans-serif;display:flex;flex-wrap:wrap;gap:8px}"
             "figure{margin:0;width:260px}img{max-width:260px;background:#ccc}</style>")
    for rel, size, st, png in rows:
        if png: fh.write(f'<figure><img src="previews/{html.escape(png)}"><figcaption>{html.escape(rel)}<br>{size} {st}</figcaption></figure>')
print(len(rows), "psds;", sum(r[2] == "blank" for r in rows), "blank;", sum(r[2] == "ERR" for r in rows), "errors")
