Use a **2-stage workflow**:

1. **Fast OpenCV pass** for obvious comic panels.
2. **PyTorch/SAM cleanup pass** for weird layouts, broken borders, full-bleed art, ads, splash pages.

SAM 2 is the current Meta “Segment Anything” model for promptable image/video segmentation, and it works on still images too. ([GitHub][1]) Torchvision also has current transform support for images, boxes, masks, and segmentation workflows. ([PyTorch Docs][2])

Minimal pipeline:

```text
scans/
  issue001/page_001.jpg
  issue001/page_002.jpg

out/
  issue001/page_001_panel_001.png
  issue001/page_001_panel_002.png
```

Core approach:

```python
import cv2
from pathlib import Path

IN = Path("scans")
OUT = Path("out")
OUT.mkdir(exist_ok=True)

def detect_panels(img):
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

    # comics: panels are often separated by white gutters
    thresh = cv2.threshold(gray, 245, 255, cv2.THRESH_BINARY_INV)[1]

    # close ink regions together
    kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (15, 15))
    closed = cv2.morphologyEx(thresh, cv2.MORPH_CLOSE, kernel)

    contours, _ = cv2.findContours(
        closed, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE
    )

    boxes = []
    h, w = gray.shape
    page_area = h * w

    for c in contours:
        x, y, bw, bh = cv2.boundingRect(c)
        area = bw * bh

        if area < page_area * 0.01:
            continue
        if bw < w * 0.15 or bh < h * 0.10:
            continue

        boxes.append((x, y, bw, bh))

    boxes.sort(key=lambda b: (b[1], b[0]))
    return boxes

for page in IN.rglob("*.[jp][pn]g"):
    img = cv2.imread(str(page))
    if img is None:
        continue

    boxes = detect_panels(img)

    issue_dir = OUT / page.parent.name
    issue_dir.mkdir(parents=True, exist_ok=True)

    for i, (x, y, w, h) in enumerate(boxes, 1):
        panel = img[y:y+h, x:x+w]
        out = issue_dir / f"{page.stem}_panel_{i:03}.png"
        cv2.imwrite(str(out), panel)
```

Install:

```bash
python -m venv .venv
source .venv/bin/activate
pip install opencv-python pillow numpy torch torchvision
```

For mass processing, add:

```bash
pip install tqdm
```

Then wrap the loop:

```python
from tqdm import tqdm

for page in tqdm(list(IN.rglob("*.[jp][pn]g"))):
    ...
```

My recommendation: **do not start with neural segmentation**. Comic panel extraction is often more about **gutters, borders, whitespace, and layout geometry** than object segmentation. Use PyTorch/SAM only for pages where OpenCV returns bad crops.

Good refinement steps:

```text
1. Deskew scans.
2. Normalize page size.
3. Detect white gutters / black borders.
4. Extract candidate rectangles.
5. Sort reading order.
6. Save crops.
7. Generate contact sheet for QA.
8. Re-run failed pages with SAM/manual prompts.
```

For PyTorch/SAM integration later:

```text
Use OpenCV boxes as prompts → feed boxes to SAM → get cleaner masks/crops.
```

That gives you the best of both worlds: **fast bulk extraction** plus **AI-assisted cleanup** only where needed.

[1]: https://github.com/facebookresearch/sam2?utm_source=chatgpt.com "facebookresearch/sam2: The repository provides code for ..."
[2]: https://docs.pytorch.org/vision/main/transforms.html?utm_source=chatgpt.com "Transforming images, videos, boxes and more"
