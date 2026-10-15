# Funny Guy — Franchise Root

This is the home for the Funny Guy franchise: shared canon, extracted game data,
asset catalogs, creative tools, and future games. The original RPG and comic
archive remain in their sibling repositories.

## Comic ratings pilot

[Read the 20-comic editorial pilot](docs/editorial/comic-ratings-pilot.md), covering
all eight books with separate comic-quality and adaptation medals. Ratings are
provisional pending creator calibration; the remaining archive is unscored.
[Structured ratings and override fields](data/editorial/comic-ratings-pilot.json)
preserve evidence and original-page references.

## Imported source documents and reference art

[Browse 35 source documents and 28 small reference images](references/imports/hdd-2026-09-27-catalog.md),
including searchable text, handwritten character sheets, and card-game sketches.
[Import methods and recovery instructions](docs/asset-import.md) document the verified
move of the asset-drive originals into recoverable trash.

## RPG extraction

The original RPG is now exported into reusable JSON and searchable text under
[`data/rpg/`](data/rpg/): 315 maps, 2,945 message blocks (4,808 lines), six playable
actors, 190 skills, 140 items, 121 enemy records, and 17,098 event commands.

- [Methods, reproducibility, schema, and limitations](docs/rpg-extraction.md)
- [Characters and artwork links](data/rpg/characters.json)
- [Readable dialogue](data/rpg/dialogue.txt)
- [Grouped messages](data/rpg/messages.jsonl)
- [Extraction summary](data/rpg/summary.json)

Run `python3 scripts/extract_rpg.py` from this repository to regenerate. Run
`python3 -m unittest discover -s tests -v` to validate.

Legacy assets are now at **`/mnt/hdd/assets`**. Older documents may cite a mounted
share and folder layout that no longer exists. Use the current
[`legacy_asset_index.json`](data/rpg/legacy_asset_index.json) and
[historical-reference candidates](data/rpg/legacy_reference_resolution.json)
instead of reconstructing old paths. New generated media belongs in `output/`;
shared, reproducible data and methods belong in `data/` and `docs/`.

> **Plan:** see [PLAN.md](PLAN.md). The sibling repos below now live under `repo/`.

## Related repos

- [`../funnyguycomics`](../funnyguycomics) — Hugo archive of the original
  webcomic (2000–2003). Source of characters, lore, and transcripts.
- [`../funnyguyrpg`](../funnyguyrpg) — RPG Maker 2000/2003 game built on the
  Funny Guy IP. Source of sprites, tilesets, and battle assets.
- `/mnt/hdd/assets` — current legacy asset collection (PSDs, scans, raw art).
  Source material stays in place; generated output belongs in this repository's
  ignored `output/` directory.

## Creative tooling

Generate new Funny Guy art (character portraits, comic panels, RPG-style
sprites/tilesets) that's consistent with the established look: crude,
kid-drawn stick-figure comics on one end, and RPG Maker sprite art on the
other. See `references/style.md` and `references/characters.md` before
generating anything — consistency with 20+ years of canon is the whole
point.

## Layout

- `data/rpg/` — reproducible RPG content exports with source IDs and asset links.
- `docs/` — franchise methods, extraction notes, and project planning.

- `references/` — character bible, style notes, and `asset_manifest.csv`
  (see below), pulled from the archive with pointers to real source files
  for visual reference. Also `story_bible.md` (fuller lore recovered from
  unread legacy-dump documents — history, full cast bios, the official
  book/season structure, unproduced scripts), `remix_material_audit.md`
  (historical audit of reusable legacy material),
  and `remix_style.md` (target tone for remixed/generated content).
- `prompts/` — reusable SDXL prompt templates per character/style.
- `workflows/` — ComfyUI workflow JSON exports.
- `scripts/` — pipeline helpers: `discover_assets.py`, `render_thumbnails.py`
  + `classify_assets.py` (legacy-dump triage), `segment_panels.py` +
  `make_slideshow.py` (comic pages → panels → slideshow video),
  `generate_sdxl.py` + `generate_sdxl_img2img.py` (SDXL generation — see
  "Generation stack" below), `describe_panels.py` (local VLM → per-panel
  descriptive transcript), `make_short.py` (panels → captioned vertical
  short for TikTok/Reels/Shorts), `build_schedule.py` (shorts → dated
  posting queue — see `docs/social_release_plan.md`), `remix_psd.py`
  (swaps real dialogue on layered `Raw/*.psd` originals for generated
  lines — see `references/remix_style.md`), `embed_panels.py` (CLIP
  visual-similarity index over every panel), `transcribe_playthrough.py`
  (Whisper transcription of `Playthrough/` gameplay recordings).
- `output/` — local scratch/staging for generated results. Gitignored;
  nothing here is source of truth. Generated media stays here; reproducible extracted records live in `data/`.

## Asset discovery

`scripts/discover_assets.py` walks the franchise reference images and three external source locations
(`assets/reference`, `../funnyguycomics`, `../funnyguyrpg`, `/mnt/hdd/assets`)
and writes `references/asset_manifest.csv` — path, size, mtime, extension,
and an `origin` guess (`original-comic`, `rpg-unreviewed`, `stock-rpgmaker`,
`legacy-unsorted`, `franchise-reference`, or `canonical` for the handful of files already named in
`references/characters.md`). It only reads file metadata, never opens or
hashes files, to keep discovery lightweight.

The manifest is checked into git (it's small, machine-readable, and useful
without the NAS mount online) — re-run the script and commit the diff when
source material changes. `stock-rpgmaker` is a filename-prefix heuristic for `DH-*` / `FX-*` resources;
other game media is `rpg-unreviewed`, not automatically original artwork.
See `references/style.md` before selecting character references.

## Generated output location

Use `output/` for generated media and scratch results (gitignored). Keep shared
canon in `references/`, reproducible extracted data in `data/`, and methods in
`docs/`. The `/mnt/hdd/assets` collection is an input, not an output destination.
Historical audits retain original paths as provenance; see the extraction methods
for migration limits and tools that still need individual path updates.

## Generation stack

Uses [stable-diffusion-xl-base-1.0](../stable-diffusion-xl-base-1.0) via
the `diffusers` library directly (`scripts/generate_sdxl.py` /
`generate_sdxl_img2img.py`), not a ComfyUI server — the local checkpoint
is checked out in native diffusers layout, and converting it to the
single-file format ComfyUI's loader expects would be extra machinery just
to run a batch script. `workflows/` stays around for an interactive
ComfyUI session later if that's wanted, but it's not part of the current
pipeline.

Plain text-to-image drifts off-model (SDXL wasn't trained on this IP —
see `references/style.md`), confirmed in testing: a polished
illustration instead of a crude stick figure, a grid of generic sprites
instead of one consistent character. `generate_sdxl_img2img.py` fixes
this by generating from a real reference image (a single comic panel from
`scripts/segment_panels.py`'s output, or an existing sprite) instead of
noise, at a tunable `--strength`. Both scripts run on the ComfyUI venv's
Python (`../ComfyUI/.venv/bin/python3` — has torch+CUDA, diffusers, and
accelerate already installed) and log every generation (seed, strength,
steps, prompt) to a manifest CSV under `output/generated*/` for
reproducibility.
