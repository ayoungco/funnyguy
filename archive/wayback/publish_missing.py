#!/usr/bin/env python3
"""Fill historic routes the Hugo build doesn't produce with archived bytes.

For every path in manifest.csv that a built site (BUILD) can't answer, copy
the newest raw capture of that path into the Hugo static/ dir at the same
path, so the old URL serves exactly what the Wayback Machine saw. Paths
without a file extension become <path>/index.html. Query strings are dropped
(a static server ignores them). Prints a summary; --dry-run copies nothing.

Usage: publish_missing.py BUILD [--dry-run]
"""
import csv, os, shutil, sys, urllib.parse

HERE = os.path.dirname(os.path.abspath(__file__))
STATIC = os.path.normpath(os.path.join(HERE, "../../repo/funnyguycomics/static"))
build, dry = sys.argv[1], "--dry-run" in sys.argv
sys.path.insert(0, HERE)
from fetch_raw import local_path  # noqa: E402  (same raw layout)


def served(root, path):
    p = path.rstrip("/")
    return any(os.path.isfile(root + c) for c in ([p, p + "/index.html"] if p else ["/index.html"]))


newest = {}
for row in csv.DictReader(open(os.path.join(HERE, "manifest.csv"))):
    path = urllib.parse.urlsplit(row["original"]).path or "/"
    if row["timestamp"] > newest.get(path, ("",))[0]:
        newest[path] = (row["timestamp"], row["original"])

copied, missing_raw = [], []
for path, (ts, original) in sorted(newest.items()):
    if served(build, path) or served(STATIC, path):
        continue
    src = local_path(ts, original)
    if not os.path.isfile(src):
        missing_raw.append(path); continue
    rel = path.lstrip("/")
    dest = os.path.join(STATIC, rel if os.path.splitext(rel)[1] else os.path.join(rel, "index.html"))
    copied.append((path, ts, os.path.relpath(dest, STATIC)))
    if not dry:
        os.makedirs(os.path.dirname(dest), exist_ok=True)
        shutil.copyfile(src, dest)

for path, ts, dest in copied:
    print(f"{'would copy' if dry else 'copied'} {path} ({ts}) -> static/{dest}")
if missing_raw:
    print("no raw capture yet:", " ".join(missing_raw))
print(f"{len(copied)} routes filled, {len(missing_raw)} waiting on fetch_raw.py")
