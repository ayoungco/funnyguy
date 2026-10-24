#!/usr/bin/env python3
"""Deeper CDX inventory (downloads nothing) -> manifest_deep.csv.

Differs from cdx_inventory.py: matchType=domain (all subdomains), every status
code (redirects and errors show where things lived), no digest collapse up to
2009 (the era the first pass barely saw), digest collapse after. Resumable:
each (domain, era) query is cached in cdx_cache/ and skipped if present.
"""
import csv, json, os, sys, time, urllib.parse, urllib.request
HERE = os.path.dirname(os.path.abspath(__file__))
CACHE = os.path.join(HERE, "cdx_cache"); os.makedirs(CACHE, exist_ok=True)
DOMAINS = ["fgcomics.com", "funnyguycomics.com"]  # funnyguy.com is NOT ours (a stranger's site)
# freehostz.com is a shared free host full of strangers' sites: query ONLY our path prefix, never the domain
PREFIXES = ["www.freehostz.com/fgoac/*", "freehostz.com/fgoac/*", "fgoac.freehostz.com/*"]
FIELDS = ["urlkey", "timestamp", "original", "mimetype", "statuscode", "digest", "length"]
ERAS = [("early", {"to": "2009"}), ("late", {"from": "2009", "collapse": "digest"})]

def query(domain, extra):
    q = {"url": domain, "output": "json", "fl": ",".join(FIELDS)}
    if "/" not in domain: q["matchType"] = "domain"
    q.update(extra)
    req = urllib.request.Request("https://web.archive.org/cdx/search/cdx?" + urllib.parse.urlencode(q),
                                 headers={"User-Agent": "funnyguy-archive/1.0 (personal archive of my own site)"})
    wait = 90
    for _ in range(10):
        try:
            body = urllib.request.urlopen(req, timeout=300).read()
            return json.loads(body)[1:] if body.strip() else []
        except Exception as e:
            print(domain, "retry in", wait, "s:", e, flush=True); time.sleep(wait); wait = min(wait * 2, 900)
    raise RuntimeError("gave up " + domain)

rows = []
for d in DOMAINS + PREFIXES:
    for name, extra in ERAS:
        f = os.path.join(CACHE, f"{d.replace('/', '_').replace('*', '')}.{name}.json")
        if not os.path.exists(f):
            r = query(d, extra); json.dump(r, open(f, "w")); print(d, name, len(r), flush=True); time.sleep(30)
        rows += json.load(open(f))
seen, out = set(), []
for r in rows:
    k = (r[1], r[2])
    if k not in seen: seen.add(k); out.append(r)
with open(os.path.join(HERE, "manifest_deep.csv"), "w", newline="") as fh:
    w = csv.writer(fh); w.writerow(FIELDS); w.writerows(out)
print("done", len(out), flush=True)
