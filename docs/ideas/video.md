For a storyboard/comic pipeline, you want three stages:

1. Panel detection / slicing
2. Asset cleanup + ordering
3. Automatic video generation with motion

Your “30 frames per page” detail matters a lot — that’s dense enough that manual slicing becomes miserable quickly.

Best modern pipeline

1. Use automated panel extraction, not Photoshop slices

Photoshop slicing works, but it becomes:

* tedious
* brittle
* hard to batch
* annoying when gutters vary

Instead, use comic/manga panel extraction tools or CV-based segmentation.

Good options:

Fastest turnkey:

* Manga Image Translator panel segmentation repo￼
* ComicPanelSegmentation dataset/tools￼

These use contour detection / ML to find rectangular panels automatically.

For your use case (clean school storyboard pages), even simple CV works very well.

⸻

Probably the ideal approach for you

Given your Linux/admin background:

Use Python + OpenCV

This is extremely automatable.

Pipeline:

* scan or photograph pages
* threshold image
* detect rectangular contours
* sort panels top-left → bottom-right
* export numbered PNGs
* feed to FFmpeg for Ken Burns slideshow video

This is maybe 100–150 lines of Python total.

The reason this wins:

* repeatable
* batchable
* future-proof
* no Adobe dependency
* easy to tweak

⸻

Detection approach

Typical algorithm:

1. grayscale
2. adaptive threshold
3. morphological close
4. contour detect
5. rectangularity filter
6. minimum area filter
7. sort by reading order
8. crop/export

Storyboard frames are MUCH easier than manga because:

* usually rectangular
* consistent gutters
* less overlapping art

So CV works extremely well.

⸻

Then: automatic “movie”

After extraction:

0001.png
0002.png
0003.png
...

Feed into FFmpeg.

You can automatically:

* pan slowly
* zoom slightly
* crossfade
* add soundtrack
* add subtitles/captions
* generate voiceover later

⸻

FFmpeg Ken Burns effect

Example concept:

ffmpeg -framerate 1/4 -i %04d.png \
-vf "zoompan=z='min(zoom+0.0005,1.1)':d=125" \
-c:v libx264 output.mp4

That gives:

* slow cinematic zoom
* slideshow pacing
* automatic movie feel

⸻

If you want the FASTEST no-code route

Use:

* Photoshop batch slice OR
* ImageMagick￼
* then FFmpeg

If your pages are perfectly gridded:

* ImageMagick can literally crop by fixed coordinates automatically.

Example:

magick page.png -crop 5x6@ +repage frame_%03d.png

That splits into a 5×6 grid instantly.

For uniformly spaced storyboard pages, this is absurdly efficient.

⸻

My recommendation by page consistency

If panels are perfectly grid-aligned

Use:

* ImageMagick crop grid
* FFmpeg

Fastest possible solution.

⸻

If panels vary slightly

Use:

* OpenCV contour detection
* FFmpeg

Best balance.

⸻

If you want cinematic motion/comic animation later

Use:

* OpenCV extraction
* then import into:
    * DaVinci Resolve￼
    * or Blender￼

Then automate:

* camera motion
* parallax
* speech bubbles
* sound
* transitions

⸻

The hidden important detail

Your biggest future problem is NOT segmentation.

It’s:

* ordering
* naming
* metadata

You want deterministic filenames:

chapter01_page03_panel05.png

That turns the entire archive into programmable media.

Then:

* AI voiceover
* automatic subtitles
* searchable archive
* web viewer
* animated remaster
    become trivial later.

That structure matters more than the exact slicer.

## Repo bootstrap

The first pass lives in `tools/video/` and is intentionally separate from the
Hugo site. It gives the repo a repeatable Python environment for panel
segmentation without changing the web build.

### VS Code

Workspace settings in `.vscode/` theme the editor chrome after the current site
palette:

* Funny Guy red: `#b21a1a`
* ink black: `#1d1b1b`
* paper: `#f8f5ef`

The source editor stays on a readable monospace font, while the surrounding
workbench, status bar, terminal, selections, and tabs use the comic palette.

### Python environment

Create the local environment:

```sh
python3 -m venv .venv
.venv/bin/python -m pip install --upgrade pip
.venv/bin/python -m pip install -r tools/video/requirements.txt
```

Or run the VS Code task:

```text
Python: create video venv
```

The requirements include:

* `opencv-python` and `numpy` for the current contour-based segmentation pass
* `torch` and `torchvision` so learned segmentation can be added later
* `pillow` and `tqdm` for image IO helpers and batch progress

### Panel extraction

Run the detector against a page or a directory of scans:

```sh
.venv/bin/python tools/video/segment_panels.py path/to/scans \
  --output panel-output \
  --debug-dir panel-output/debug
```

The script:

1. thresholds each page
2. closes gaps with morphology
3. detects rectangular contours
4. filters likely page borders and tiny artifacts
5. sorts panels top-left to bottom-right
6. exports crops plus a JSON manifest

Outputs are ignored by git:

```text
panel-output/
video-output/
```

For tuned scans, adjust:

```sh
--min-area-ratio 0.015
--max-area-ratio 0.92
--padding 8
--row-tolerance 0.45
```

The manifest keeps the deterministic metadata needed by the later FFmpeg or
voiceover steps:

```json
{
  "page": "path/to/page.png",
  "index": 1,
  "x": 120,
  "y": 80,
  "width": 640,
  "height": 480,
  "score": 0.42,
  "output": "panel-output/page/page_panel001.png"
}
```
