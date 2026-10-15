#!/usr/bin/env python3
"""Inventory Wayback captures (CDX only, downloads nothing) -> manifest.csv."""
import csv, json, sys, time, urllib.request, urllib.parse
HOSTS = ["fgcomics.com", "funnyguycomics.com", "www.fgcomics.com", "www.funnyguycomics.com"]
FIELDS = ["urlkey", "timestamp", "original", "mimetype", "statuscode", "digest", "length"]
def fetch(host):
    q = urllib.parse.urlencode({"url": host + "/*", "output": "json", "fl": ",".join(FIELDS),
                                "collapse": "digest", "filter": "statuscode:200"})
    req = urllib.request.Request("https://web.archive.org/cdx/search/cdx?" + q,
                                 headers={"User-Agent": "funnyguy-archive/1.0"})
    for i in range(6):
        try:
            return json.load(urllib.request.urlopen(req, timeout=120))[1:]
        except Exception as e:
            print(host, "retry", e, file=sys.stderr); time.sleep(60 * (i + 1))
    return []
out = sys.argv[1] if len(sys.argv) > 1 else "manifest.csv"
rows = []
for h in HOSTS:
    r = fetch(h); print(h, len(r), file=sys.stderr); rows += r; time.sleep(30)
with open(out, "w", newline="") as f:
    w = csv.writer(f); w.writerow(FIELDS); w.writerows(rows)
