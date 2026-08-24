# Funny Guy — Generative Content

AI-generated art and assets for the Funny Guy universe, built on top of the
existing archive and game rather than replacing them.

## Related repos

- [`../funnyguycomics`](../funnyguycomics) — Hugo archive of the original
  webcomic (2000–2003). Source of characters, lore, and transcripts.
- [`../funnyguyrpg`](../funnyguyrpg) — RPG Maker 2000/2003 game built on the
  Funny Guy IP. Source of sprites, tilesets, and battle assets.
- `/mnt/creative/projects/funnyguy` — legacy asset dump (PSDs, scans, raw
  art). Treated as read-only source material; nothing here copies it into
  git. Point tools at it directly, or mount/symlink locally if that's more
  convenient for a given session. Generated output goes in a `generated/`
  subfolder here too — see "Generated output location" below.

## Purpose

Generate new Funny Guy art (character portraits, comic panels, RPG-style
sprites/tilesets) that's consistent with the established look: crude,
kid-drawn stick-figure comics on one end, and RPG Maker sprite art on the
other. See `references/style.md` and `references/characters.md` before
generating anything — consistency with 20+ years of canon is the whole
point.

## Layout

- `references/` — character bible, style notes, and `asset_manifest.csv`
  (see below), pulled from the archive with pointers to real source files
  for visual reference.
- `prompts/` — reusable SDXL prompt templates per character/style.
- `workflows/` — ComfyUI workflow JSON exports.
- `scripts/` — pipeline helpers: `discover_assets.py`, `render_thumbnails.py`
  + `classify_assets.py` (legacy-dump triage), `segment_panels.py` +
  `make_slideshow.py` (comic pages → panels → slideshow video),
  `generate_sdxl.py` + `generate_sdxl_img2img.py` (SDXL generation — see
  "Generation stack" below).
- `output/` — local scratch/staging for generated results. Gitignored;
  nothing here is source of truth. **Intended long-term home for generated
  content is `/mnt/creative/projects/funnyguy/generated/`, not this repo**
  — see "Generated output location" below. `output/` stays as the working
  dir until that path is writable.

## Asset discovery

`scripts/discover_assets.py` walks the three source locations
(`../funnyguycomics`, `../funnyguyrpg`, `/mnt/creative/projects/funnyguy`)
and writes `references/asset_manifest.csv` — path, size, mtime, extension,
and an `origin` guess (`original-comic`, `original-rpg`, `stock-rpgmaker`,
`legacy-unsorted`, or `canonical` for the handful of files already named in
`references/characters.md`). It only reads file metadata, never opens or
hashes files, since the legacy dump is 21GB over a slow CIFS mount.

The manifest is checked into git (it's small, machine-readable, and useful
without the NAS mount online) — re-run the script and commit the diff when
source material changes. `stock-rpgmaker` rows are RPG Maker's bundled
resources (`DH-*`, `FX-*` in `funnyguyrpg/CharSet` etc.), not Funny Guy art
— don't use them as character style reference, per `references/style.md`.

## Generated output location

Generated content (rendered images, batches, anything `scripts/` produces)
should land on the NAS at `/mnt/creative/projects/funnyguy/generated/` —
alongside the existing legacy dump, not a new sibling location. Config —
prompts, workflows, the character bible, the asset manifest — stays in
git.

This isn't wired up yet: `/mnt/creative` is a CIFS mount owned by
`root:root` with `dir_mode=0755` (see `systemctl cat mnt-creative.mount`),
so the current user can't create `/mnt/creative/projects/funnyguy/generated/`
or write into it. Fixing this means adding `uid=`/`gid=` (or a looser
`dir_mode`) to the `mnt-creative.mount` unit — it's a local mount-option
fix, not a permissions change needed on the NAS itself. Until then,
generated output stays in the gitignored local `output/`.

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
