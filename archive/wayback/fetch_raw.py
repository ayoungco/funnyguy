#!/usr/bin/env python3
"""Download the raw (id_) bytes of every capture in manifest.csv.

Writes raw/<timestamp>/<host><path>[?query -> __query], skipping files that
already exist, so it can be stopped and re-run. Sequential and slow on
purpose: the Wayback Machine answers 429 when pushed, so every request is
spaced out and a 429 backs off (Retry-After, else doubling from 90 s).

Usage: fetch_raw.py [manifest.csv] [raw_dir]
"""
import csv, os, sys, time, urllib.error, urllib.parse, urllib.request

HERE = os.path.dirname(os.path.abspath(__file__))
args = sys.argv[1:] if __name__ == "__main__" else []
manifest = args[0] if len(args) > 0 else os.path.join(HERE, "manifest.csv")
raw = args[1] if len(args) > 1 else os.path.join(HERE, "raw")
SPACING, UA = 6, "funnyguy-archive/1.0 (personal archive of my own site)"


def local_path(ts, original):
    u = urllib.parse.urlsplit(original)
    path = u.path or "/"
    if path.endswith("/"):
        path += "index.html"
    if u.query:
        path += "__" + urllib.parse.quote(u.query, safe="=&")
    return os.path.join(raw, ts, u.hostname + path)


def get(url):
    backoff = 90
    for _ in range(8):
        try:
            req = urllib.request.Request(url, headers={"User-Agent": UA})
            with urllib.request.urlopen(req, timeout=120) as r:
                return r.read()
        except urllib.error.HTTPError as e:
            if e.code in (429, 503):
                wait = int(e.headers.get("Retry-After") or backoff)
                print(f"  {e.code}, sleeping {wait}s", flush=True)
                time.sleep(wait); backoff = min(backoff * 2, 900); continue
            raise
        except (urllib.error.URLError, TimeoutError) as e:
            print(f"  {e}, sleeping 60s", flush=True); time.sleep(60)
    raise RuntimeError("gave up: " + url)


def main():
    rows = list(csv.DictReader(open(manifest)))
    done = failed = 0
    for i, row in enumerate(rows, 1):
        dest = local_path(row["timestamp"], row["original"])
        if os.path.exists(dest):
            done += 1; continue
        url = f"https://web.archive.org/web/{row['timestamp']}id_/{row['original']}"
        try:
            data = get(url)
        except Exception as e:
            failed += 1; print(f"[{i}/{len(rows)}] FAIL {row['original']} {e}", flush=True); continue
        os.makedirs(os.path.dirname(dest), exist_ok=True)
        with open(dest, "wb") as f:
            f.write(data)
        done += 1
        print(f"[{i}/{len(rows)}] {len(data):>7} {row['timestamp']} {row['original']}", flush=True)
        time.sleep(SPACING)
    print(f"done {done}/{len(rows)}, failed {failed}")


if __name__ == "__main__":
    main()
