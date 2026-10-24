#!/usr/bin/env python3
"""Export every layer of a PSD as a trimmed PNG plus a layers.json manifest.

Usage: psd_layers.py OUT_DIR file.psd [file.psd ...]   (run with psd-tools installed)
Writes OUT_DIR/<psd stem>/{composite.png,layers.json,layers/NNN_name.png}. Hidden
layers are exported too (flagged visible=false); some comps keep alternate colorways
hidden. Groups are listed but only pixel/shape layers produce PNGs.
"""
import json, os, re, sys
from psd_tools import PSDImage

out = sys.argv[1]
for path in sys.argv[2:]:
    stem = os.path.splitext(os.path.basename(path))[0]
    d = os.path.join(out, stem); os.makedirs(os.path.join(d, "layers"), exist_ok=True)
    psd = PSDImage.open(path)
    try: psd.composite().save(os.path.join(d, "composite.png"))
    except Exception as e: print(stem, "composite failed:", e)
    info = []
    for i, l in enumerate(psd.descendants()):
        rec = dict(i=i, name=l.name, kind=l.kind, visible=l.is_visible(), bbox=list(l.bbox), opacity=l.opacity)
        if not l.is_group() and l.width and l.height:
            try:
                img = l.composite()
                if img is not None:
                    fn = f"{i:03d}_{re.sub(r'[^A-Za-z0-9._-]+', '_', l.name)[:40]}.png"
                    img.save(os.path.join(d, "layers", fn)); rec["file"] = fn
            except Exception as e: rec["error"] = str(e)
        info.append(rec)
    json.dump(dict(source=os.path.basename(path), size=psd.size, layers=info), open(os.path.join(d, "layers.json"), "w"), indent=1)
    print(stem, psd.size, len(info), "layers,", sum("file" in r for r in info), "exported")
